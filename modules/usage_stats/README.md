Usage Stats module
==============

Presents information about network, disk, CPU, and GPU activity

This module is only supported on 10.10 and higher

Database:
* timestamp - BIGINT - timestamp of when stats were last pulled
* thermal_pressure - varchar(255) - String detailing thermal pressure of the system
* backlight_max - INT(11) - Maximum value of backlight
* backlight_min - INT(11) - Minimum value of backlight
* backlight - INT(11) - Current value of backlight
* keyboard_backlight - INT(11) - Current value of the keyboard backlight
* ibyte_rate - FLOAT - Network incoming bytes rate
* ibytes - FLOAT - Total of network incoming bytes
* ipacket_rate - FLOAT - Network incoming packets rate
* ipackets - FLOAT - Total of network incoming packets
* obyte_rate - FLOAT - Network outgoing bytes rate
* obytes - FLOAT - Total of network outgoing bytes
* opacket_rate - FLOAT - Network outgoing packets rate
* opackets - FLOAT - Total of network outgoing packets
* rbytes_per_s - FLOAT - Bytes read per second
* rops_per_s - FLOAT - Read operations per second
* wbytes_per_s - FLOAT - Bytes written per second
* wops_per_s - FLOAT - Write operations per second
* rbytes_diff - FLOAT - Total of bytes read
* rops_diff - FLOAT - Total of read operations
* wbytes_diff - FLOAT - Total of bytes written
* wops_diff - FLOAT - Total of write operations
* package_watts - FLOAT - Watts used by CPU+SA+iGPU package
* package_joules - FLOAT - Joules used by CPU+SA+iGPU package
* freq_hz - FLOAT - CPU Speed in hertz
* freq_ratio - FLOAT - CPU Fraction of Nominal Speed
* gpu_name - varchar(255) - Name of GPU in use
* gpu_freq_hz - FLOAT - GPU Speed in hertz
* gpu_freq_mhz - FLOAT - GPU Speed in megahertz
* gpu_freq_ratio - FLOAT - GPU Fraction of Nominal Speed
* gpu_busy - FLOAT - GPU cycles used
* kern_bootargs - VARCHAT(255) - boot flags used by the kernel on last boot

