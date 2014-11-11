Localizing
==========

If you are developing for MunkiReport, please make sure the strings you use are provided via the i18n localization framework.

Files
-----

The localization files are located in `assets/locales/`. These are JSON files. The JSON format has some restrictions in the use of quotations etc. so please make sure the locale files are valid JSON, you can check your file using a validator like [jsonlint.com](http://jsonlint.com).

dataTables
----------

When you're adding an additional language, make sure you add the appropriate localization file for `assets/locales/dataTables` as well, the tables won't load if it can't find the locale file. You can find dataTables locale files in the [dataTables github repo](https://github.com/DataTables/Plugins/tree/master/i18n).
Make sure you:

* remove the comments at the beginning of the file
* remove the sProcessing property (MunkiReport shows a spinner instead)
* remove the colon (:) from sSearch (MunkiReport moves this into the placeholder)

Some things to keep in mind
---------------------------

* Place generic words like 'computer', 'memory', 'hour' in the root of the JSON object
* Try to find an appropriate place for other words and sentences (look at what's already localized)
* English is the fallback language, so make sure the strings are at least available in en.json
* Try to keep the JSON files alphabetically organized, this will make it a lot easier for people maintaining localization files.