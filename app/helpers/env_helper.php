<?php

// Legacy function name
function getenv_default($key, $default = null, $typehint = null)
{
    env($key, $default, $typehint);
}

// Get a value from $_ENV with fallback to default
// typehint parameter processes env var as the suggested type.
function env($key, $default = null, $typehint = null) {
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
