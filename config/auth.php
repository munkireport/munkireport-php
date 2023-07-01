<?php
use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Methods
    |--------------------------------------------------------------------------
    |
    | This option controls the behaviour of the MunkiReport Multi-Auth Guard to
    | support multiple different authentication methods.
    |
    | The currently supported methods are LOCAL, SAML, NOAUTH, and AD which can
    | be combined by separating them with commas.
    */
    'methods' => env('AUTH_METHODS', 'LOCAL'),

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session", "token"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            // You must use `ldap_users` if AUTH_METHODS is an ldap type to have local user fallback.
            'provider' => Str::contains('AD', env('AUTH_METHODS', 'LOCAL')) ? 'ldap_users' : 'users',
        ],

        'api' => [
            'driver' => 'token',
            'provider' => 'users',
            'hash' => false,
        ],

        'osquery' => [
            'driver' => 'nodekey',
            'provider' => 'nodes',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        // For Local Authentication
        'users' => [
            'driver' => 'eloquent',
            'model' => App\User::class,
        ],

        // For LDAP or Active Directory Auth
        'ldap_users' => [
            'driver' => 'ldap',
            'model' => App\User::class,
        ],

        // Backwards compatibility for YAML users
        'yaml_users' => [
            'driver' => 'yaml',
            'model' => App\User::class,
        ],

        'nodes' => [
            'driver' => 'eloquent',
            'model' => Munkireport\Osquery\Node::class,
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of seconds before a password confirmation
    | times out and the user is prompted to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => 10800,

];
