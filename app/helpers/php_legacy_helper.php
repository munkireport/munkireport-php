<?php

// Polyfill for older php versions

// array_column (PHP 5 >= 5.5.0, PHP 7)
if(!function_exists("array_column"))
{

    function array_column($array,$column_name)
    {

        return array_map(function($element) use($column_name){return $element[$column_name];}, $array);

    }

}
