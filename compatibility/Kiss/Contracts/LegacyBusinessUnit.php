<?php


namespace Compatibility\Kiss\Contracts;

/**
 * This interface declares all the properties of the munkireport\lib\BusinessUnit object so that
 * they can be added in a backwards compatible way to the new App\BusinessUnit object.
 *
 * @package Compatibility\Kiss\Contracts
 */
interface LegacyBusinessUnit
{
    /**
     * Create and save a business unit from the given array (from $_POST).
     *
     * @param $post_array
     * @return mixed
     */
    public function saveUnit($post_array);
}
