
====================================================
USING MUNKI TO DEPLOY MUNKIREPORT TO CLIENT MACHINES
====================================================


Step 1: Edit the BASEURL variable in client_installer.sh (be sure to include a trailing '/'

Step 2: Edit assets/client_installer/MunkiReport.plist
        All you need to do here is edit the preinstall_script to use a valid URL

Step 3: Copy MunkiReport.plist file into your munki repo

Step 4: Add "munkireport" to the 'managed_installs' array of at least one
        manifest

Step 5: Run /usr/local/makecatalogs

Step 6: Drink coffee. Watch data. Rule all that you survey.
