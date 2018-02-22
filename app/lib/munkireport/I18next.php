<?php

namespace munkireport\localize;

/**
 * A php library to translate i18next localisation files
 * Author: Arjen van Bochoven
 *
 * @package Munkireport
 */

class I18next
{

    private $i18nArray;
    private $locale;

    public function __construct($locale = 'en')
    {
        // Default to en locale
        $this->locale = $locale ? $locale : 'en';

        // Load the localisation JSON
        if ($json = @file_get_contents(PUBLIC_ROOT . 'assets/locales/' . $this->locale . '.json')) {
            $this->i18nArray = json_decode($json, true);
        } else {
            $this->i18nArray = array();
        }
    }

    /**
     * Translate
     *     *
     * @param type var Description
     **/
    public function translate($text = '', $params = '')
    {
        $textArray = explode('.', $text);
        $search = $this->i18nArray;
        foreach ($textArray as $part) {
            if (! is_array($search) or ! isset($search[$part])) {
                return $text;
            }
            $parent = $search;
            $search = $parent[$part];
        }

        if (is_array($search)) {
            return 'Array found for ' . $text;
        }

        // Check if there are params
        if ($params) {
            $paramsArray = json_decode($params, true);

            // Check if there is a count param
            if (isset($paramsArray['count']) && ($paramsArray['count'] == 0 || $paramsArray['count'] > 1 )) {
                if (isset($parent[$part.'_plural'])) {
                    $search = $parent[$part.'_plural'];
                }
            }

            // Replace params
            foreach ($paramsArray as $find => $replace) {
                $search = str_replace('__'. $find . '__', $replace, $search);
            }
        }

        return $search;
    }
}
