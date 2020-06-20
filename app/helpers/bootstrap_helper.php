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
 * Fatal error, show message and die
 *
 * @author AvB
 **/
function fatal($msg)
{
    include(APP_ROOT . 'public/assets/html/fatal_error.html');
    exit(1);
}


/**
 * Get session item
 * @param string session item
 * @param string default value (optional)
 * @author AvB
 **/
function sess_get($sess_item, $default = '')
{
    if (! isset($_SESSION))
    {
        return $default;
    }

    return array_key_exists($sess_item, $_SESSION) ? $_SESSION[$sess_item] : $default;
}

/**
 * Set session item
 * @param string session item
 * @param string value
 * @author AvB
 **/
function sess_set($sess_item, $value)
{
    if (! isset($_SESSION))
    {
        return false;
    }

    $_SESSION[$sess_item] = $value;

    return true;
}
