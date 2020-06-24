<?php
/**
 * Various functions imported from bootstrap/app.php prior to moving across to Laravel's bootstrap/app.php.
 * Maintained here for backwards compatibility.
 */

// Pass on https forward to $_SERVER['HTTPS'] todo: check if from trusted proxy
if(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
{
    $_SERVER['HTTPS'] = 'on';
}

// Check if Request is secure
function SslRequest(){
    return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
}

/**
 * Get session item
 * @param string session item
 * @param string default value (optional)
 * @author AvB
 **/
function sess_get($sess_item, $default = '')
{
    return request()->session()->get($sess_item, $default);
}

/**
 * Set session item
 * @param string session item
 * @param string value
 * @author AvB
 **/
function sess_set($sess_item, $value)
{
    request()->session()->put($sess_item, $value);
}
