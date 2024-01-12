
# Breaking Changes #

These changes *COULD* break your installation because a feature was removed or rewritten from v6 onwards.

### Authentication ###

#### AUTH_METHODS=NOAUTH no longer available ####

The framework does not allow any user to be "unauthenticated" because so much of the architecture depends on identity.
There are no plans to re-add unauthenticated access.

#### Local .yml users ####

Limited backwards-compatible support has been provided for YAML based users.

You cannot generate users using the URL `/auth/create_local_user`.
Some backwards compatibility has been provided to convert these users into database users when they log in.

If you need to create a local database user interactively, you could use the `php please make:user` command. Only database
users are supported going forward for `AUTH_METHODS=LOCAL`.

### URL access ###

#### `SUBDIRECTORY` configuration ####

The Laravel framework exclusively uses rewrite rules to generate easy to read URLs. By default, we can only support
the application being available at the root directory (/). You can use your web server VirtualHost functionality to
set up a resolvable virtual domain name for your application (or the equivalent cloud hosting functionality).

#### Removal of `index.php?` ####

For module developers:

The `index.php?` section of the URL is no longer required when using the rewrite rules. We have provided backwards
compatibility for scripts which use `index.php?` by stripping it out when the request arrives. In future, do not
refer to the URL prefix `index.php?`.

#### Removal of config `URI_PROTOCOL` ####

The URL of the application is determined by reading a new configuration variable, `APP_URL`.
The environment variable `URI_PROTOCOL` won't do anything.

### ReCaptcha will be replaced by NoCaptcha ###

Captcha won't be available until a later release. The new framework includes a rate limit for attempts to bruteforce
local accounts which will mitigate *SOME* of this.

### Widgets / Views ###

#### Event filter configuration via event.yml no longer available ####

In version 5.x you were able to place an event.yml file in the module config directory to make the messages widget
filter events by module or severity. This has been removed, with plans to re-add the functionality as a per-user
filter.

#### Custom views via /show/custom no longer available ####

This feature was not documented anywhere and has been removed.

### Deprecated API ###

- `mr_secure_url()` or `secure_url()` is replaced by the Laravel `url()` helper.

### Localization ###

While MunkiReport v5 always used a client-side only i18n solution, Laravel also brings its own on the backend.
This brings a new behaviour: When a locale is not set, the system locale is used. This is configurable in `config/app.php`.

This means that the application does not need to default to English.
