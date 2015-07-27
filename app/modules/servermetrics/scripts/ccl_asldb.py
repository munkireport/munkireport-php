#!/usr/bin/env python

"""
Copyright (c) 2012, CCL Forensics
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:
    * Redistributions of source code must retain the above copyright
      notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright
      notice, this list of conditions and the following disclaimer in the
      documentation and/or other materials provided with the distribution.
    * Neither the name of the CCL Forensics nor the
      names of its contributors may be used to endorse or promote products
      derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL CCL FORENSICS BE LIABLE FOR ANY
DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
"""

import sys
import struct
import datetime
import argparse
import os
import os.path

__version__ = "0.3"
__description__ = "Parses Apple .asl log files"
__contact__ = "acaithness@ccl-forensics.com"

_FILES_HAVE_MASK = True

_MAGIC = b"ASL DB\x00\x00\x00\x00\x00\x00"

_MESSAGE_LEVELS = ["Emergency", "Alert", "Critical", "Error", "Warning", "Notice", "Info", "Debug"]

_UNIX_EPOCH = datetime.datetime(1970,1,1)
_COCOA_EPOCH = datetime.datetime(2001,1,1)

def parse_epoch_value(epoch, sec):
    return epoch + datetime.timedelta(seconds=sec)

class AslDbError(Exception):
    pass

class AslRecord:
    """
    Class representing a log record entry in an ASL file. 
    """

    def __init__(self, offset, id, timestamp, level, flags, pid, gid, ruid, rgid, refpid, host, sender, facility, message, refproc, session, key_value_dict):
        """
        Constructor, should really only be called from AslDb._parse_record
        """
        self.offset = offset
        self.id = id
        self.timestamp = timestamp
        self.level = level
        self.level_str = _MESSAGE_LEVELS[level]
        self.flags = flags
        self.pid = pid
        self.gid = gid
        self.ruid = ruid
        self.rgid = rgid
        self.refpid = refpid
        self.host = host
        self.sender = sender
        self.facility = facility
        self.message = message
        self.refproc = refproc
        self.session = session
        self.key_value_dict = key_value_dict


    def __repr__(self):
        return "\t".join((self.timestamp.isoformat(), "id={0}".format(self.id), "level={0}".format(self.level_str), "pid={0}".format(self.pid), "gid={0}".format(self.gid), "read uid={0}".format(self.ruid),
                          "read gid={0}".format(self.rgid), "host={0}".format(self.host), "RefProc={0}".format(self.refproc), "session={0}".format(self.session), "sender={0}".format(self.sender), 
                          "facility={0}".format(self.facility), "message={0}".format(self.message), "\t".join(["{0}={1}".format(key, self.key_value_dict[key]) for key in self.key_value_dict])))

    def __str__(self):
        return self.__repr__()

class AslDb:
    """
    Class representing an ASL file.
    """

    def _parse_asl_str(self, val):
        """
        Takes an 64bit integer and depending on the top bit, either extracts the string encoded in 
        the integer, or takes the value as an offset and grabs the string from that position in the 
        file. 

        Should only really be called by AslDb._parse_record
        """
        if val == 0:
            string = ""
        elif val & 0x8000000000000000 == 0:
            # is a reference
            self.f.seek(val)
            str_tag = self.f.read(2)
            if str_tag != b"\x00\x01":
                raise AslDbError("String field does not begin with \x00\x01")
            str_len, = struct.unpack(">I", self.f.read(4))
            string = self.f.read(str_len - 1).decode(encoding='UTF-8',errors='strict') # minus 1 as it is nul-terminated
        else:
            # is embedded
            str_bytes = struct.pack(">Q", val)
            str_len = ord(str_bytes[0]) & 0x7F
            string = str_bytes[1:1+str_len].decode()
        return string

    def _parse_record(self, offset):
        """
        Parses the record at offset. 
        """
        # Get the data
        self.f.seek(offset)
        rec_len, next_rec, id, timestamp_seconds, timestamp_nano, level, flags, pid, uid, gid, ruid, rgid, refpid, kv_count, host_ref, sender_ref, facility_ref, message_ref, refproc_ref, session_ref = struct.unpack(">2xI3QI2H7I6Q", self.f.read(114))
        key_value_refs = []
        
        for i in range(kv_count // 2):
            key_value_refs.append(struct.unpack(">2Q", self.f.read(16)))

        prev_rec = struct.unpack(">Q", self.f.read(8))

        # Parse the data
        timestamp = parse_epoch_value(_UNIX_EPOCH, timestamp_seconds + (timestamp_nano / 1000000000))
        host = self._parse_asl_str(host_ref)
        sender = self._parse_asl_str(sender_ref)
        refproc = self._parse_asl_str(refproc_ref)
        facility = self._parse_asl_str(facility_ref)
        message = self._parse_asl_str(message_ref)
        session = self._parse_asl_str(session_ref)

        kv_dict = {}
        for key, value in key_value_refs:
            kv_dict[self._parse_asl_str(key)] = self._parse_asl_str(value)

        return AslRecord(offset, id, timestamp, level, flags, pid, gid, ruid, rgid, refpid, host, sender, facility, message, refproc, session, kv_dict)
    
    def __init__(self, stream):
        """Constructor: 'stream' should be a file-like object in binary mode"""
        self.f = stream
        
        # Read header magic
        magic = self.f.read(12)
        if magic != _MAGIC:
            raise AslDbError("Invalid header (Expected: '{0}' Received: '{1}'".format(_MAGIC, magic))

        self.version, = struct.unpack(">I", self.f.read(4))
        first_record_offset, = struct.unpack(">Q", self.f.read(8))
        self.timestamp = _UNIX_EPOCH + datetime.timedelta(seconds=struct.unpack(">q", self.f.read(8))[0])
        self.string_cache_size, = struct.unpack(">I", self.f.read(4))
        if _FILES_HAVE_MASK:
            filter_mask = self.f.read(1)[0] # WUT??!
        self.last_record_offset, = struct.unpack(">Q", self.f.read(8))
        self.f.read(36) # should be all 0x00 - maybe worth checking?

        # initialise offset list - just read the "next" field from each record so that initialisation is speedy
        self._record_offsets = []
        self._record_offsets.append(first_record_offset)
        next_offset = first_record_offset

        while next_offset != self.last_record_offset:
            #print(next_offset)
            self.f.seek(next_offset + 6) # first 6 bytes of a record: 2 bytes of 0x00 followed by 32bit int for length
            n, = struct.unpack(">Q", self.f.read(8))
            # AvB last_record_offset not reached?
            if n <= next_offset:
                break
            self._record_offsets.append(n)
            next_offset = n

    def __iter__(self):
        for o in self._record_offsets:
            yield self._parse_record(o)

    def __getitem__(self, index):
        if index > self.__len__() - 1 or index < 0:
            raise IndexError("Index must be greater than 0 and less that the number of records - 1")
        self._parse_record(self._record_offsets[index])

    def __len__(self):
        return self._record_offsets.__len__()



# ----------------Command line stuff----------------

_TSV_HEADER_ROW = "Timestamp\tHost\tSender\tPID\tReference Process\tReference PID\tFacility\tLevel\tMessage\tOther details\n"
def record_to_tsv(record):
    # header row:
    # Timestamp, Host, Sender[PID], PID, Reference Sender, Reference PI Facility, Level, Message, Other details
    # Newlines in the message field get replaced by single spaces and tabs by 4 spaces for ease of viewing
    return "\t".join((record.timestamp.isoformat(), record.host, record.sender,  
                      str(record.pid), str(record.refproc), str(record.refpid), record.facility, record.level_str, record.message.replace("\n", " ").replace("\t", "    "), 
                      "; ".join(["{0}='{1}'".format(key, record.key_value_dict[key]) for key in record.key_value_dict]).replace("\n", " ").replace("\t", "    ")))

def main():
    # Parse arguments
    parser = argparse.ArgumentParser(
            description="Python based apple ASL log parser",
            formatter_class=argparse.ArgumentDefaultsHelpFormatter)

    parser.add_argument("-v", "--version", action="version", version=__version__)
    parser.add_argument("-q", "--quiet", action="store_true", dest="quiet", default=False, help="if present, suppresses log messages being shown")
    parser.add_argument("-o", "--outputlocation", type=str, action="store", dest="output_location", default=None, help="path of output file (stdout if none defined)")
    parser.add_argument("-t", "--outputformat", type=str, choices=["tsv"], action="store", dest="output_type", default="tsv", help="format of output")
    parser.add_argument("-a", "--append", action="store_true", dest="append_data", default=False, help="if present, the output will be appended to the file specified (by default, any existing files will be overwritten)")
    parser.add_argument("-i", "--inputtype", type=str, choices=["file", "dir"], action="store", dest="input_type", default="file", help="defines whether the input is a single ASL file or a folder of ASL files")
    parser.add_argument("inputpath", nargs="+", help="ASL files/directory containing ASL files (depending on inputtype)")

    args = parser.parse_args()
    input_path = args.inputpath
    quiet = args.quiet
    output_type = args.output_type
    output_location = args.output_location
    append_data = args.append_data

    # Logging functions
    def print_log(s):
        if not quiet:
            print("{0}\t{1}".format(datetime.datetime.now().isoformat(), s))

    def print_err(s):
        if quiet:
            sys.stderr.write("{0}\t{1}\n".format(datetime.datetime.now().isoformat(), s))
        else:
            print_log(s)

    if args.input_type == "dir":
        files_to_process = [os.path.join(input_path[0], p) for p in os.listdir(input_path[0])]
    else:
        files_to_process = input_path


    # set up output
    if output_type == "tsv":
        if output_location:
            file_mode = "a" if append_data else "w"
            out_f = open(output_location, file_mode)
        else:
            out_f = sys.stdout

        out_f.write(_TSV_HEADER_ROW)
    else:
        raise ValueError("Output Type {0} is unknown".format(output_type))

    for file in files_to_process:
        print_log("Processing: {0}".format(file))
        try:
            f = open(file, "rb")
        except IOError as e:
            print_err("Could not open file '{0}' ({1}): Skipping this file".format(file, e))
            continue

        try:
            db = AslDb(f)
        except AslDbError as e:
            print_err("Could not read file as ASL DB '{0}' ({1}): Skipping this file".format(file, e))
            f.close()
            continue

        for record in db:
            if output_type == "tsv":
                out_f.write(record_to_tsv(record))
                out_f.write("\n")
            else:
                raise ValueError("Output Type {0} is unknown".format(output_type))

        f.close()


if __name__ == "__main__":
    main()