<?php


namespace App;

/**
 * Processors
 *
 * Centrally registers the list of processors available and dispatches them when given a payload from check-in.
 *
 * @package App
 */
class Processors
{
    protected $processors = [];

    public function process($module, $usingClass) {
        $this->processors[$module] = $usingClass;
    }
}
