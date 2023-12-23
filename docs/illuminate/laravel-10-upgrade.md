# Laravel 10 Upgrade Process #

As per https://laravel.com/docs/10.x/upgrade

- Update core dependencies
- (Deferred) Upgrade Sanctum https://github.com/laravel/sanctum/blob/3.x/UPGRADE.md
- (Deferred) PHPUnit 10 updates
- https://laravel.com/docs/10.x/upgrade#public-path-binding no occurrences found
- https://laravel.com/docs/10.x/upgrade#redis-cache-tags not using redis (yet)
- https://laravel.com/docs/10.x/upgrade#database-expressions usage of DB::raw only in ProvidesHistogram trait.
- https://laravel.com/docs/10.x/upgrade#query-exception-constructor not affected
- https://laravel.com/docs/10.x/upgrade#model-dates-property not in use
- https://laravel.com/docs/10.x/upgrade#language-directory not affected
- https://laravel.com/docs/10.x/upgrade#monolog-3 (Deferred) Refer to monolog 3 upgrade guide https://github.com/Seldaek/monolog/blob/main/UPGRADE.md
- https://laravel.com/docs/10.x/upgrade#dispatch-now not using bus yet
- https://laravel.com/docs/10.x/upgrade#middleware-aliases we will not be updating this yet
- https://laravel.com/docs/10.x/upgrade#rate-limiter-return-values not being used
- https://laravel.com/docs/10.x/upgrade#redirect-home not being used
- https://laravel.com/docs/10.x/upgrade#service-mocking was imported but not used?
- https://laravel.com/docs/10.x/upgrade#closure-validation-rule-messages was not used
- https://laravel.com/docs/10.x/upgrade#form-request-after-method was not used

- Had to remove "fruitcake/laravel-cors": "^2.0",
- Bumped for compat with laravel 10 "inertiajs/inertia-laravel": "^0.6.11",
- Remove fideloper/proxy because its folded into v10 https://github.com/fideloper/TrustedProxy/issues/152
- "nunomaduro/larastan": "^1.0", abandoned for larastan/larastan

## Dependencies

### Sanctum 3.x

### PHPUnit 10.x

### Monolog 3

### Jetstream 3 -> 4

As per https://github.com/laravel/jetstream/blob/4.x/UPGRADE.md
Nothing to note (?)

### Scout 9.x -> 10.x

Since there arent any published features in MunkiReport with Scout support, we can just upgrade it.

### GraphiQL / Lighthouse

As per https://github.com/nuwave/lighthouse/blob/master/UPGRADE.md

- laravel-graphiql https://github.com/mll-lab/laravel-graphiql no change required, still broken(?)
- 
