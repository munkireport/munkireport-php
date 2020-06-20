<?php


namespace MR\Kiss;


class FakeEngine
{
    public function __construct()
    {
        $GLOBALS['engine'] = $this;
    }

    public function get_uri_string()
    {
        return "";
    }
}
