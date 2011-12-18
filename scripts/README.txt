To create a munki dmg with the preflight, postflight and report_broken_client scripts:

Open Terminal.app and issue the following commands:

cd this/directory
./make_script_dmg.sh http://your-report-php-server 1.0

This will create a disk image and a corresponding pkginfo file.