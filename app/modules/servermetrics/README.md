Servermetrics module
==============

Reports on Server Statistics by parsing asl logfiles found in /var/log/servermetricsd/
Log entries occur once per 5 minutes

The table stores the following information per log entry:

* afp_sessions - number of afp connections
* smb_sessions'] - number of smb connections
* caching_cache_toclients
* caching_origin_toclients
* caching_peers_toclients
* cpu_user - CPU usage
* cpu_idle'] - CPU usage
* cpu_system'] - CPU usage
* cpu_nice'] - CPU usage
* memory_wired'] - Memory Usage
* memory_active'] - Memory Usage
* memory_inactive'] - Memory Usage
* memory_free'] - Memory Usage
* memory_pressure'] - Memory Usage
* network_in'] - Network Usage
* network_out'] - Network Usage
* datetime - Datetime from record