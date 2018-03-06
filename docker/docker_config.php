<?php 

$conf['index_page'] = '';
$conf['auth']['auth_noauth'] = [];
$conf['hide_inactive_modules'] = true;

// Sitename
if($var = envGet('MR_SITENAME')){
    $conf['sitename'] = $var;
}

// Modules
if($var = envGet('MR_MODULES', 'array')){
    $conf['modules'] = $var;
}

// Look up environment variable and return value if found or false if not found
function envGet($envVar, $type = 'string'){
    if(isset($_ENV[$envVar])){
        if($type == 'array'){
            return array_map('trim', explode(',', $_ENV[$envVar]));
        }
        return $_ENV[$envVar];
    }
    return false;
}