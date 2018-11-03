Homebrew module
==============

Module provides a breakdown on everything currently installed by Homebrew. It can be installed separately from the homebrew_info module.

Database:
* name - varchar(255) - name of item installed by brew
* full_name - varchar(255) - full name of item installed by brew
* oldname - varchar(255) - previous name of item, if renamed by brew
* aliases - varchar(255) - aliases of item
* desc - varchar(255) - discription of item
* homepage - varchar(255) - homepage of item
* installed_versions - varchar(255) - all versions currently installed
* versions_stable - varchar(255) - current stable version
* linked_keg - varchar(255) - version linked for use
* dependencies - text - dependencies of the item
* build_dependencies - text - dependencies required to build
* recommended_dependencies - text - recommended dependencies
* runtime_dependencies - text - dependencies needed to execute binary
* optional_dependencies - text - optional dependencies
* requirements - varchar(255) - requirements of the item
* options - text - options available at install/build time
* used_options - text - options used when installing/building item
* caveats - text - caveats of using/installing/building the item
* conflicts_with - varchar(255) - other items this item conflicts with
* homepage - int - 0/1 for internal USB device
* built_as_bottle - int - 0/1 was built as a bottle
* installed_as_dependency - int - 0/1 installed as a dependency of another item
* installed_on_request - int - 0/1 installed on request
* poured_from_bottle - int - 0/1 poured from a bottle
* versions_bottle - int - 0/1 is available as a bottle
* keg_only - int - 0/1 is only a keg (not link)
* outdated - int - 0/1 was detected as outdated last brew run
* pinned - int - 0/1 version pinned
* versions_devel - int - 0/1 developmental version available
* versions_head - int - 0/1 head available to build from 



This module requires Homebrew to be installed [https://brew.sh/](https://brew.sh/)