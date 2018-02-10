Memory module
==============

Provides information about installed memory.

Data can be viewed under the memory tab on the client details page or using the memory list view 

Database:
* name - varchar(255) - name of memory bank
* dimm_size - varchar(255) - size of the memory module
* dimm_speed - varchar(255) - module speed
* dimm_type - varchar(255) - type of memory
* dimm_status - varchar(255) - memory status
* dimm_manufacturer - varchar(255) - manufacturer of memory
* dimm_part_number - varchar(255)(255) - memory module part number
* dimm_serial_number - varchar(255) - memory serial number
* dimm_ecc_errors - varchar(255) - reported ECC errors of module
* global_ecc_state - int - current ECC state, can be 0, 1, or 2
* is_memory_upgradeable - boolean - is memory user upgradable
