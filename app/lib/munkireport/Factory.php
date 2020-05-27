<?php

namespace munkireport\lib;

use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Symfony\Component\Finder\Finder;


class Factory extends EloquentFactory
{

    /**
     * Load factories from path. Overloaded to account for direct path loading
     *
     * @param  string  $path
     * @return $this
     */
    public function load($path)
    {
        $factory = $this;

        if (is_dir($path)) {
            foreach (Finder::create()->files()->name('*.php')->in($path) as $file) {
                require $file->getRealPath();
            }
        } elseif (is_file($path)) {
            require $path;
        }

        return $factory;
    }
}