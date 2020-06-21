<?php


namespace MR\Kiss\Core;

//===============================================================
// View
// For plain .php templates
//===============================================================
use Symfony\Component\Yaml\Yaml;

abstract class View
{
    protected $file='';
    protected $vars=array();

    public function __construct($file = '', $vars = '')
    {
        if ($file) {
            $this->file = $file;
        }
        if (is_array($vars)) {
            $this->vars=$vars;
        }
        return $this;
    }

    public function __set($key, $var)
    {
        return $this->set($key, $var);
    }

    public function set($key, $var)
    {
        $this->vars[$key]=$var;
        return $this;
    }

    //for adding to an array
    public function add($key, $var)
    {
        $this->vars[$key][]=$var;
    }

    public function view($file = '', $vars = '', $view_path = '')
    {
        //Bluebus addition
        if(empty($view_path)){
            $view_path = conf('view_path');
        }

        if (is_array($vars)) {
            $this->vars=array_merge($this->vars, $vars);
        }
        extract($this->vars);

        if (! @include($view_path.$file.'.php')) {
            echo '<!-- Could not open '.$view_path.$file.'.php -->';
        }
    }

    public function viewWidget($vars){
        if(isset($vars['type'])){
            $file = 'widgets/'.$vars['type'].'_widget';
        }else{
            $file = 'widgets/unknown_widget';
        }
        mr_view($file, $vars);
    }

    public function viewDetailWidget($data){

        $view = $data['view'];
        $view_path = $data['view_path'] ?? conf('view_path');
        $view_vars = $data['view_vars'] ?? [];

        // Check if Yaml
        if(is_readable($view_path . $view . '.yml')){
            $view_vars = Yaml::parseFile($view_path . $view . '.yml');
            $view = 'detail_widgets/' . $view_vars['type'] . '_widget';
            $view_path = conf('view_path');
        }

        mr_view($view, $view_vars, $view_path);

    }

    public function fetch($vars = '')
    {
        if (is_array($vars)) {
            $this->vars=array_merge($this->vars, $vars);
        }
        extract($this->vars);
        ob_start();
        require($this->file);
        return ob_get_clean();
    }

    public function dump($vars = '')
    {
        if (is_array($vars)) {
            $this->vars=array_merge($this->vars, $vars);
        }
        extract($this->vars);
        require($this->file);
    }

    public static function doFetch($file = '', $vars = '')
    {
        if (is_array($vars)) {
            extract($vars);
        }
        ob_start();
        require($file);
        return ob_get_clean();
    }

    public static function doDump($file = '', $vars = '')
    {
        if (is_array($vars)) {
            extract($vars);
        }
        require($file);
    }

    public static function doFetchStr($str, $vars = '')
    {
        if (is_array($vars)) {
            extract($vars);
        }
        ob_start();
        eval('?>'.$str);
        return ob_get_clean();
    }

    public static function doDumpStr($str, $vars = '')
    {
        if (is_array($vars)) {
            extract($vars);
        }
        eval('?>'.$str);
    }
}
