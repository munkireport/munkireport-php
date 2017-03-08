<?php
class Locale extends Controller
{
    private $modules;

    public function __construct()
    {
        if (! $this->authorized()) {
            header('Content-Type: application/json;charset=utf-8');
            echo '{"error": "Not Authorized"}';
            exit;
        }

        $this->modules = $modules = getMrModuleObj()->loadInfo();
    }


    public function get($lang = 'en')
    {
        header('Content-Type: application/json;charset=utf-8');
        echo $this->modules->getModuleLocales($lang);
    }

}
