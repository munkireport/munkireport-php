<?php
// Relative redirect - could not find an apache rule that could do this.

$webhost = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://'.$_SERVER[ 'HTTP_HOST' ];
$subdirectory = substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], basename(__FILE__)));

header("Location: ${webhost}${subdirectory}public/");
