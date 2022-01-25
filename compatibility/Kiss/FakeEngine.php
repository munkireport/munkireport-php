<?php


namespace Compatibility\Kiss;


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
