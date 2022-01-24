<?php
/**
 * Various functions imported from bootstrap/app.php prior to moving across to Laravel's bootstrap/app.php.
 * Maintained here for backwards compatibility.
 */

use Illuminate\Support\Facades\Log;

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
 * Get session item.
 *
 * @param string session item
 * @param string default value (optional)
 * @deprecated Use session() helper.
 * @todo Still used in laps module, cannot be fully deprecated.
 * @author AvB
 **/
function sess_get($sess_item, $default = '')
{
    Log::channel('deprecations')
        ->warning('This function will be deprecated in future, please use session()->get() in your modules.', [
            'function' => __FUNCTION__,
            'file' => __FILE__,
            'line' => __LINE__,
        ]);
    return request()->session()->get($sess_item, $default);
}
