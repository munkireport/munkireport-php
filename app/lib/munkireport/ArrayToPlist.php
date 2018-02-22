<?php

// Utility class to render plist files

namespace munkireport\lib;

use CFPropertyList\CFPropertyList;
use CFPropertyList\CFDictionary;
use CFPropertyList\CFNumber;
use CFPropertyList\CFString;
use CFPropertyList\CFArray;

class ArrayToPlist
{

    private $parser;

    public function __construct()
    {
        $this->parser = new CFPropertyList();
    }

    public function parse($array)
    {
        $this->parser->add($dict = new \CFDictionary());
        $this->addItem($dict, $array);

        return $this->parser->toXML();
    }



    // Detect if this is an associative array
    public function has_string_keys(array $array)
    {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }


    public function typeValue($value)
    {
        if (is_numeric($value)) {
            return new \CFNumber($value);
        } else {
            return new \CFString($value);
        }
    }

    public function addItem(&$dict, $item)
    {
        $parent = get_class($dict);

        //echo "$parent\n";
        foreach ($item as $key => $value) {
            if (is_scalar($value)) {
                if ($parent == 'CFDictionary') {
                    $dict->add($key, $this->typeValue($value));
                } else {
                    $dict->add($this->typeValue($value));
                }
            } else {
                if ($this->has_string_keys($value)) {
                    if ($parent == 'CFArray') {
                        $dict->add($newdict = new \CFDictionary());
                    } else {
                        $dict->add($key, $newdict = new \CFDictionary());
                    }
                } else {
                    $dict->add($key, $newdict = new \CFArray());
                }

                $this->addItem($newdict, $value);
            }
        }
    }
}
