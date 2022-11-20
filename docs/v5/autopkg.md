AutoPkg for munkireport
=====

If your munkireport server is running fine and you can create an install package from it, you can automate the process of creating installation packages using the munkireport autopkg recipe. This will help you create a new package suited for your organization when you update your munkireport server.

Thi guide assumes you have installed [AutoPkg](https://github.com/autopkg/autopkg) and you have some experience working with AutoPkg.

First add the munkireport recipe repository:

```sh
autopkg repo-add munkireport-recipes
```

This will give you two recipes, munkireport.pkg.recipe and munkireport.munki.recipe. The first will create a package, the second will add it to your munki repository. You cannot use the recipes at the moment, because you have to add some information about your munkireport installation. To add that, you'll need to make an override:

```sh
autopkg make-override munkireport.munki
```

AutoPkg has created an override file for you: ~/Library/AutoPkg/RecipeOverrides/munkireport.munki.recipe. Now you can edit the file with your favorite editor.

The most important setting you have to change is

```xml
<key>BASEURL</key>
<string>http://localhost:8888</string>
```

This has to be the url that you use to access your munkireport server minus /index.php?etc.

You could also change MUNKI_REPO_SUBDIR, NAME and modules. If you add modules here, you will force the modules that get packaged into the munkireport package, the modules you set in config.php will be ignored. Add modules in the following way:

```xml
<key>modules</key>
<array>
  <string>localadmin</string>
  <string>bluetooth</string>
</array>
```


You can test your new recipe by running

```sh
autopkg run -v munkireport.munki.recipe
```

If all goes well, you'll have imported a new Munkireport package into your munki repository.
