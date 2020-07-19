<?php
/**
 * Various functions imported from bootstrap/app.php prior to moving across to Laravel's bootstrap/app.php.
 * Maintained here for backwards compatibility.
 */

/**
 * Check if Request is secure
 *
 * We cannot replace this with request()->isSecure() yet, because it is called before
 *  the Request object is constructed.
 *
 * @return bool
 */
function SslRequest() {
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
