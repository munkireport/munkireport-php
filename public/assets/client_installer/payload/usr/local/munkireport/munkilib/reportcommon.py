#!/usr/local/munkireport/munkireport-python2
# encoding: utf-8

from . import display
from . import prefs
from . import constants
from . import FoundationPlist
from munkilib.purl import Purl
from munkilib.phpserialize import *

import subprocess
import pwd
import sys
import hashlib
import platform
from urllib import urlencode
import re
import time
import os

# PyLint cannot properly find names inside Cocoa libraries, so issues bogus
# No name 'Foo' in module 'Bar' warnings. Disable them.
# pylint: disable=E0611
from Foundation import NSArray, NSDate, NSMetadataQuery, NSPredicate
from Foundation import CFPreferencesAppSynchronize
from Foundation import CFPreferencesCopyAppValue
from Foundation import CFPreferencesCopyKeyList
from Foundation import CFPreferencesSetValue
from Foundation import kCFPreferencesAnyUser
from Foundation import kCFPreferencesCurrentUser
from Foundation import kCFPreferencesCurrentHost
from Foundation import NSHTTPURLResponse
from SystemConfiguration import SCDynamicStoreCopyConsoleUser

# pylint: enable=E0611

# our preferences "bundle_id"
BUNDLE_ID = "MunkiReport"


class CurlError(Exception):
    def __init__(self, status, message):
        display_error(message)
        finish_run()


def set_verbosity(level):
    """Set verbosity level."""
    display.verbose = int(level)


def display_error(msg, *args):
    """Call display error msg handler."""
    display.display_error("%s" % msg, *args)


def display_warning(msg, *args):
    """Call display warning msg handler."""
    display.display_warning("%s" % msg, *args)


def display_detail(msg, *args):
    """Call display detail msg handler."""
    display.display_detail("%s" % msg, *args)


def finish_run():
    remove_run_file()    
    display_detail("## Finished run")
    exit(0)

def remove_run_file():
    touchfile = '/Users/Shared/.com.github.munkireport.run'
    if os.path.exists(touchfile):
        os.remove(touchfile)

def curl(url, values):

    options = dict()
    options["url"] = url
    options["method"] = "POST"
    options["content_type"] = "application/x-www-form-urlencoded"
    options["body"] = urlencode(values)
    options["logging_function"] = display_detail
    options["connection_timeout"] = 60
    if pref("UseMunkiAdditionalHttpHeaders"):
        custom_headers = prefs.pref(constants.ADDITIONAL_HTTP_HEADERS_KEY)
        if custom_headers:
            options["additional_headers"] = dict()
            for header in custom_headers:
                m = re.search(r"^(?P<header_name>.*?): (?P<header_value>.*?)$", header)
                if m:
                    options["additional_headers"][m.group("header_name")] = m.group(
                        "header_value"
                    )
        else:
            raise CurlError(
                -1,
                "UseMunkiAdditionalHttpHeaders defined, "
                "but not found in Munki preferences",
            )

    # Build Purl with initial settings
    connection = Purl.alloc().initWithOptions_(options)
    connection.start()
    try:
        while True:
            # if we did `while not connection.isDone()` we'd miss printing
            # messages if we exit the loop first
            if connection.isDone():
                break

    except (KeyboardInterrupt, SystemExit):
        # safely kill the connection then re-raise
        connection.cancel()
        raise
    except Exception, err:  # too general, I know
        # Let us out! ... Safely! Unexpectedly quit dialogs are annoying...
        connection.cancel()
        # Re-raise the error as a GurlError
        raise CurlError(-1, str(err))

    if connection.error != None:
        # Gurl returned an error
        display.display_detail(
            "Download error %s: %s",
            connection.error.code(),
            connection.error.localizedDescription(),
        )
        if connection.SSLerror:
            display_detail("SSL error detail: %s", str(connection.SSLerror))
        display_detail("Headers: %s", connection.headers)
        raise CurlError(
            connection.error.code(), connection.error.localizedDescription()
        )

    if connection.response != None and connection.status != 200:
        display.display_detail("Status: %s", connection.status)
        display.display_detail("Headers: %s", connection.headers)
    if connection.redirection != []:
        display.display_detail("Redirection: %s", connection.redirection)

    connection.headers["http_result_code"] = str(connection.status)
    description = NSHTTPURLResponse.localizedStringForStatusCode_(connection.status)
    connection.headers["http_result_description"] = description

    if str(connection.status).startswith("2"):
        return connection.get_response_data()
    else:
        # there was an HTTP error of some sort.
        raise CurlError(
            connection.status,
            "%s failed, HTTP returncode %s (%s)"
            % (
                url,
                connection.status,
                connection.headers.get("http_result_description", "Failed"),
            ),
        )


def get_hardware_info():
    """Uses system profiler to get hardware info for this machine."""
    cmd = ["/usr/sbin/system_profiler", "SPHardwareDataType", "-xml"]
    proc = subprocess.Popen(
        cmd,
        shell=False,
        bufsize=-1,
        stdin=subprocess.PIPE,
        stdout=subprocess.PIPE,
        stderr=subprocess.PIPE,
    )
    (output, dummy_error) = proc.communicate()
    try:
        plist = FoundationPlist.readPlistFromString(output)
        # system_profiler xml is an array
        sp_dict = plist[0]
        items = sp_dict["_items"]
        sp_hardware_dict = items[0]
        return sp_hardware_dict
    except BaseException:
        return {}


def get_long_username(username):
    try:
        long_name = pwd.getpwnam(username)[4]
    except:
        long_name = ""
    return long_name.decode("utf-8")


def get_uid(username):
    try:
        uid = pwd.getpwnam(username)[2]
    except:
        uid = ""
    return uid


def get_computername():
    cmd = ["/usr/sbin/scutil", "--get", "ComputerName"]
    proc = subprocess.Popen(
        cmd,
        shell=False,
        bufsize=-1,
        stdin=subprocess.PIPE,
        stdout=subprocess.PIPE,
        stderr=subprocess.PIPE,
    )
    (output, unused_error) = proc.communicate()
    output = output.strip()
    return output.decode("utf-8")


def get_cpuinfo():
    cmd = ["/usr/sbin/sysctl", "-n", "machdep.cpu.brand_string"]
    proc = subprocess.Popen(
        cmd,
        shell=False,
        bufsize=-1,
        stdin=subprocess.PIPE,
        stdout=subprocess.PIPE,
        stderr=subprocess.PIPE,
    )
    (output, unused_error) = proc.communicate()
    output = output.strip()
    return output.decode("utf-8")


def get_buildversion():
    cmd = ["/usr/bin/sw_vers", "-buildVersion"]
    proc = subprocess.Popen(
        cmd,
        shell=False,
        bufsize=-1,
        stdin=subprocess.PIPE,
        stdout=subprocess.PIPE,
        stderr=subprocess.PIPE,
    )
    (output, unused_error) = proc.communicate()
    output = output.strip()
    return output.decode("utf-8")


def get_uptime():
    cmd = ["/usr/sbin/sysctl", "-n", "kern.boottime"]
    proc = subprocess.Popen(
        cmd,
        shell=False,
        bufsize=-1,
        stdin=subprocess.PIPE,
        stdout=subprocess.PIPE,
        stderr=subprocess.PIPE,
    )
    (output, unused_error) = proc.communicate()
    sec = int(re.sub(".*sec = (\d+),.*", "\\1", output))
    up = int(time.time() - sec)
    return up if up > 0 else -1


def set_pref(pref_name, pref_value):
    """Sets a preference, See prefs.py for details."""
    CFPreferencesSetValue(
        pref_name,
        pref_value,
        BUNDLE_ID,
        kCFPreferencesAnyUser,
        kCFPreferencesCurrentHost,
    )
    CFPreferencesAppSynchronize(BUNDLE_ID)
    print "set pref"
    try:
        CFPreferencesSetValue(
            pref_name,
            pref_value,
            BUNDLE_ID,
            kCFPreferencesAnyUser,
            kCFPreferencesCurrentHost,
        )
        CFPreferencesAppSynchronize(BUNDLE_ID)
    except Exception:
        pass


def pref(pref_name):
    """Return a preference.

    See prefs.py for details
    """
    pref_value = CFPreferencesCopyAppValue(pref_name, BUNDLE_ID)
    return pref_value


def process(serial, items):
    """Process receives a list of items, checks if they need updating and
    updates them if necessary."""

    # Sanitize serial
    serial = "".join([c for c in serial if c.isalnum()])

    # Get prefs
    baseurl = pref("BaseUrl") or prefs.pref("SoftwareRepoURL") + "/report/"

    hashurl = baseurl + "index.php?/report/hash_check"
    checkurl = baseurl + "index.php?/report/check_in"

    # Get passphrase
    passphrase = pref("Passphrase")

    # Get hashes for all scripts
    for key, i in items.items():
        if i.get("path"):
            i["hash"] = getmd5hash(i.get("path"))

    # Check dict
    check = {}
    for key, i in items.items():
        if i.get("hash"):
            check[key] = {"hash": i.get("hash")}

    # Send hashes to server
    values = {"serial": serial, "items": serialize(check), "passphrase": passphrase}
    server_data = curl(hashurl, values)
    # = response.read()

    # Decode response
    try:
        result = unserialize(server_data)
    except Exception, e:
        display_error("Could not unserialize server data: %s" % str(e))
        display_error("Request: %s" % str(values))
        display_error("Response: %s" % str(server_data))
        return -1

    if result.get("error") != "":
        display_error("Server error: %s" % result["error"])
        return -1

    if result.get("info") != "":
        display_detail("Server info: %s" % result["info"])

    # Retrieve hashes that need updating
    total_size = 0
    for i in items.keys():
        if i in result:
            if items[i].get("path"):
                try:
                    f = open(items[i]["path"], "r")
                    items[i]["data"] = f.read()
                except:
                    display_warning("Can't open %s" % items[i]["path"])
                    del items[i]
                    continue
            size = len(items[i]["data"])
            display_detail("Need to update %s (%s)" % (i, sizeof_fmt(size)))
            total_size = total_size + size
        else:  # delete items that don't have to be uploaded
            del items[i]

    # Send new files with hashes
    if len(items):
        display_detail("Sending items (%s)" % sizeof_fmt(total_size))
        response = curl(
            checkurl,
            {"serial": serial, "items": serialize(items), "passphrase": passphrase},
        )
        display_detail(response)
    else:
        display_detail("No changes")


def runExternalScriptWithTimeout(
    script, allow_insecure=False, script_args=(), timeout=30
):
    """Run a script (e.g. preflight/postflight) and return its exit status.

    Args:
      script: string path to the script to execute.
      allow_insecure: bool skip the permissions check of executable.
      args: args to pass to the script.
    Returns:
      Tuple. (integer exit status from script, str stdout, str stderr).
    Raises:
      ScriptNotFoundError: the script was not found at the given path.
      RunExternalScriptError: there was an error running the script.
    """
    from munkilib import utils

    if not os.path.exists(script):
        raise ScriptNotFoundError("script does not exist: %s" % script)

    if not allow_insecure:
        try:
            utils.verifyFileOnlyWritableByMunkiAndRoot(script)
        except utils.VerifyFilePermissionsError, e:
            msg = (
                "Skipping execution due to failed file permissions "
                "verification: %s\n%s" % (script, str(e))
            )
            raise utils.RunExternalScriptError(msg)

    if os.access(script, os.X_OK):
        cmd = [script]
        if script_args:
            cmd.extend(script_args)
        proc = subprocess.Popen(
            cmd,
            shell=False,
            stdin=subprocess.PIPE,
            stdout=subprocess.PIPE,
            stderr=subprocess.PIPE,
        )
        while timeout > 0:
            if proc.poll() is not None:
                (stdout, stderr) = proc.communicate()
                return (
                    proc.returncode,
                    stdout.decode("UTF-8", "replace"),
                    stderr.decode("UTF-8", "replace"),
                )
            time.sleep(0.1)
            timeout -= 0.1
        else:
            try:
                proc.kill()
            except OSError, e:
                if e.errno != 3:
                    raise
            raise utils.RunExternalScriptError("%s timed out" % script)
        return (0, None, None)

    else:
        raise utils.RunExternalScriptError("%s not executable" % script)


def rundir(scriptdir, runtype, abort=False, submitscript=""):
    """Run scripts in directory scriptdir runtype is passed to the script if
    abort is True, a non-zero exit status will abort munki submitscript is put
    at the end of the scriptlist."""
    if os.path.exists(scriptdir):

        from munkilib import utils

        # Get timeout for scripts
        scriptTimeOut = 30
        if pref("scriptTimeOut"):
            scriptTimeOut = int(pref("scriptTimeOut"))
            display_detail("# Set custom script timeout to %s seconds" % scriptTimeOut)

        # Directory containing the scripts
        parentdir = os.path.basename(scriptdir)
        display_detail("# Executing scripts in %s" % parentdir)

        # Get all files in scriptdir
        files = os.listdir(scriptdir)

        # Sort files
        files.sort()

        # Find submit script and stick it on the end of the list
        if submitscript:
            try:
                sub = files.pop(files.index(submitscript))
                files.append(sub)
            except Exception, e:
                display_error("%s not found in %s" % (submitscript, parentdir))

        for script in files:

            # Skip files that start with a period
            if script.startswith("."):
                continue

            # Concatenate dir and filename
            scriptpath = os.path.join(scriptdir, script)

            # Skip directories
            if os.path.isdir(scriptpath):
                continue

            try:
                # Attempt to execute script
                display_detail("Running %s" % script)
                result, stdout, stderr = runExternalScriptWithTimeout(
                    scriptpath,
                    allow_insecure=False,
                    script_args=[runtype],
                    timeout=scriptTimeOut,
                )
                if stdout:
                    display_detail(stdout)
                if stderr:
                    display_detail("%s Error: %s" % (script, stderr))
                if result:
                    if abort:
                        display_detail("Aborted by %s" % script)
                        exit(1)
                    else:
                        display_warning("%s return code: %d" % (script, result))

            except utils.ScriptNotFoundError:
                pass  # Script has disappeared - pass.
            except Exception, e:
                display_warning("%s: %s" % (script, str(e)))


def sizeof_fmt(num):
    for unit in ["B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB"]:
        if abs(num) < 1000.0:
            return "%.0f%s" % (num, unit)
        num /= 1000.0
    return "%.1f%s" % (num, "YB")


def gethash(filename, hash_function):
    """Calculates the hashvalue of the given file with the given hash_function.

    Args:
      filename: The file name to calculate the hash value of.
      hash_function: The hash function object to use, which was instantiated
          before calling this function, e.g. hashlib.md5().

    Returns:
      The hashvalue of the given file as hex string.
    """
    if not os.path.isfile(filename):
        return "NOT A FILE"

    fileref = open(filename, "rb")
    while 1:
        chunk = fileref.read(2 ** 16)
        if not chunk:
            break
        hash_function.update(chunk)
    fileref.close()
    return hash_function.hexdigest()


def getmd5hash(filename):
    """Returns hex of MD5 checksum of a file."""
    hash_function = hashlib.md5()
    return gethash(filename, hash_function)


def getOsVersion(only_major_minor=True, as_tuple=False):
    """Returns an OS version.

    Args:
      only_major_minor: Boolean. If True, only include major/minor versions.
      as_tuple: Boolean. If True, return a tuple of ints, otherwise a string.
    """
    os.environ["SYSTEM_VERSION_COMPAT"] = '0'
    cmd = ["/usr/bin/sw_vers -productVersion"]
    proc = subprocess.Popen(
        cmd,
        shell=True,
        bufsize=-1,
        stdin=subprocess.PIPE,
        stdout=subprocess.PIPE,
        stderr=subprocess.PIPE,
    )
    (output, unused_error) = proc.communicate()
    output = output.strip()
    os_version_tuple = output.split(".")
    if only_major_minor:
        os_version_tuple = os_version_tuple[0:2]
    if as_tuple:
        return tuple(map(int, os_version_tuple))
    else:
        return ".".join(os_version_tuple)


def getconsoleuser():
    """Return console user."""
    cfuser = SCDynamicStoreCopyConsoleUser(None, None, None)
    return cfuser[0]


# End of reportcommon
