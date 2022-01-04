# Laravel 8 Upgrade Progress #

https://laravel.com/docs/8.x/upgrade

- Core dependencies updated
- Check isset on collect() collections
- Add laravel/legacy-factories to support seeders and factories in the old style
- (TODO) Update seeders and factories to use new class-style
- We are not impacted by `castUsing()`
- EventServiceProvider register() - N/A
- Dispatcher listen() updated 1 func in EventFake
- Updated index.php to support Laravel 8 maintenance mode
- Laravel 8 prefers PHP Callable route actions instead of Strings see https://laravel.com/docs/8.x/upgrade#automatic-controller-namespace-prefixing
- 
