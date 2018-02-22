<?php

namespace munkireport\lib;

/**
* Themes class
* 
* Retrieves thems from theme folder
*
*/
class Themes
{
    
    private $themes = array();

    public function __construct()
    {
        foreach(scandir(PUBLIC_ROOT.'assets/themes') AS $theme)
        {
            if( $theme != 'fonts' && strpos($theme, '.') === false)
            {
                $this->themes[] = $theme;
            }
        }
    }

    public function get_list()
    {
        return $this->themes;
    }
    
}
