<?php

namespace munkireport\controller;

use \Controller, \View;

class Locale extends Controller
{
    private $modules;
    private $fallBackLang = 'en';
    private $langFilter = '/^[a-z]+$/';

    public function __construct()
    {
        // if (! $this->authorized()) {
        //     header('Content-Type: application/json;charset=utf-8');
        //     echo '{"error": "Not Authorized"}';
        //     exit;
        // }

        $this->modules = getMrModuleObj()->loadInfo();
    }

    /**
     * Get locale strings
     *
     * This function returns a JSON object containing 4 language objects and a messages object
     * The function just concatenates the various JSON files together instead of parsing
     * This is done for performance reasons.
     *
     * @param string $lang Locale definition, defaults to 'en'
     */
    public function get($lang = 'en', $load = 'enabled_modules_only')
    {
        // Check if we should load all modules' locales
        if($load == 'all_modules'){
            $this->modules = getMrModuleObj()->loadInfo(True);
        }
        
        $locales = array(
            'messages' => '{}',
            'fallback_main' => '{}',
            'fallback_module' => '{}',
            'lang_main' => '{}',
            'lang_module' => '{}',
        );

        // Load fallback language files
        $locales['fallback_main'] = file_get_contents(PUBLIC_ROOT.'assets/locales/'.$this->fallBackLang.'.json');
        $locales['fallback_module'] = $this->modules->getModuleLocales($this->fallBackLang);

        if ($lang == $this->fallBackLang) {
            $locales['messages'] = '{"info": "requested language is fallback language"}';
        } elseif ( ! preg_match($this->langFilter, $lang)) {
            $locales['messages'] = sprintf('{"error": "requested language is not valid: %s"}', $lang);
        } else {
            if (is_file(PUBLIC_ROOT.'assets/locales/'.$lang.'.json')) {
                $locales['lang_main'] = file_get_contents(PUBLIC_ROOT.'assets/locales/'.$lang.'.json');
            } else {
                $locales['messages'] = sprintf('{"error": "Could not load main locale for: %s"}', $lang);
            }
            $locales['lang_module'] = $this->modules->getModuleLocales($lang);
        }

        // TODO: add ETAG support and caching.

        header('Content-Type: application/json;charset=utf-8');
        echo "{\n";
        foreach($locales as $key => $content){
            printf('"%s": %s%s', $key, $content, $key != 'lang_module' ? ",\n": '');
        }
        echo "}\n";
    }
}
