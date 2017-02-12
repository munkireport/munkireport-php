<?php

class Settings extends Controller
{
    public function __construct()
    {
        if (! $this->authorized()) {
            $obj = new View();
            $obj->view('json', array('msg' => 'Not authorized'));

            die(); 
        }
    }
    
    //===============================================================
    
    /**
     * Set
     *
     * Set/Get theme value in $_SESSION
     *
     */
    public function theme()
    {
        if(isset($_POST['set']))
        {
            include_once(APP_PATH . '/lib/munkireport/Themes.php');
            
            // Check if valid theme
            $themeObj = new munkireport\Themes();
            if(in_array($_POST['set'], $themeObj->get_list()))
            {
                sess_set('theme', $_POST['set']);
            }
            else
            {
                $obj = new View();
                $obj->view('json', array('msg' => sprintf('Error: theme %s unknown', $_POST['set'])));
            }
        }
        
        $obj = new View();
        $obj->view('json', array('msg' => sess_get('theme', 'Default')));
    }
    
}
