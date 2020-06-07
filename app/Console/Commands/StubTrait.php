<?php

namespace App\Console\Commands;

trait StubTrait {

    protected function getStub($name)
    {
        return APP_ROOT . "build/templates/module/$name.stub";
    }

    protected function populateStub($stub, $search = [])
    {
        return str_replace(array_keys($search), array_values($search), file_get_contents($stub));
    }

    public function saveStub($path, $data)
    {
        file_put_contents($path, $data);
    }
}