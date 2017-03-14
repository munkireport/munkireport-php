Homebrew Info module
==============

Gathers and returns information on the client's Homebrew installation

Database:
* core_tap_head - varchar(255) - hash of the core tap head
* core_tap_origin - varchar(255) - URI of the core tap origin
* core_tap_last_commit - varchar(255) - static local time of when brew last checked for updates
* head - varchar(255) - hash of the head
* last_commit - varchar(255) - when brew itself was last updated
* origin - varchar(255) - Git repo used for origin pulls
* homebrew_bottle_domain - varchar(255) - domain used for making bottles
* homebrew_cellar - varchar(255) - path to cellar
* homebrew_prefix - varchar(255) - Homebrew prefix
* homebrew_repository - varchar(255) - path to Homebrew repository
* homebrew_version - varchar(255) - version of Homebrew currently installed
* homebrew_ruby - varchar(255) - version of Ruby currently used by Homebrew
* command_line_tools - varchar(255) - command line tools version
* cpu - varchar(255) - quick general information about the CPU
* git - varchar(255) - version and path of Git used by brew
* clang - varchar(255) - Clang version
* java - varchar(255) - Java path and version
* perl - varchar(255) - path to perl
* python - varchar(255) - path to Python
* ruby - varchar(255) - path to Ruby
* x11 - varchar(255) - version of X11 installed
* xcode - varchar(255) - Xcode version
* macos - varchar(255) - macOS version and type detected by Homebrew

This module requires Homebrew to be installed [https://brew.sh/](https://brew.sh/)

