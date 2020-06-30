<?php

class FakerDataStore
{
    private static $list = [];
    private static $caller = [];

    public static function add($property, $value)
    {
        self::$list[$property][] = $value;
    }

    public static function get($who, $property)
    {
        $count = self::$caller[$who] ?? 0;
        self::$caller[$who] = $count + 1;
        return self::$list[$property][$count];
    }
}
