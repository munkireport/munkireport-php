# Adding Illuminate/Database (Laravel) to MunkiReport PHP #

[Illuminate/Database](https://packagist.org/packages/illuminate/database) is the database package included in the Laravel framework.
It can also be used in a standalone mode, usually called a *Capsule*.


## Features ##

Some of the features won't work using the Capsule, but we can use the features below.
Generally the console commands arent available to us.

- [Query Builder](https://laravel.com/docs/5.5/queries)
- [Schema Builder](https://laravel.com/docs/5.5/migrations)
- [Eloquent ORM](https://laravel.com/docs/5.5/eloquent)

## Usage in MRPHP ##

A new method has been added to the **KISS_Controller** class in `kissmvc.php` called `connectDB()`. Normally laravel
would load your config.php automatically but we needed to bootstrap it in our own way.

Whenever you want to use the Capsule, call this method first.

