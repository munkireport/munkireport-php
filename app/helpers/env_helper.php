<?php
/**
 * Get the value of an environment variable.
 *
 * This function, unlike the Laravel config() function, allows you to typehint the return value which might be casted
 * to or converted from its string representation as an environment variable.
 *
 * @param string $key The environment variable to read
 * @param mixed|Closure|null $default The default value to return if the environment variable is not found. Defaults to null.
 *                                    You may supply a closure if required.
 * @param mixed|null $typehint Type hint the return type to perform a cast or conversion. Valid type hints are one of
 *                             'array','bool','boolean','int','integer'.
 * @return array|bool|int|mixed|string|null The return value, optionally casted to the type hinted type.
 * @deprecated Try to use Laravel's config() if possible. It does not support the typehint feature so it may not be possible.
 */
function mr_env(string $key, $default = null, $typehint = null) {
    if ( getenv($key) !== false ) {
        $v = getenv($key);
        if ( $typehint === null && $default !== null) {
            $typehint = gettype($default);
        }
        switch ($typehint) {
            case 'array':
                if(empty(trim($v))){
                    return [];
                }
                return array_map('trim', explode(',', $v));
            case 'bool':
            case 'boolean':
                return $v == 'TRUE' || $v == '1';
            case 'int':
            case 'integer':
                return (int)$v;
            default:
                return $v;
        }
    } else {
        return $default instanceof Closure ? $default() : $default;
    }
}
