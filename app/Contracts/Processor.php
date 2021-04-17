<?php


namespace App\Contracts;

/**
 * The Processor Interface describes what a report processor should look like.
 *
 * @package App\Contracts
 */
interface Processor
{
    /**
     * Processor constructor.
     * @param string $module The module name that the data is being reported for.
     * @param string $serial_number The serial number of the machine reporting the data.
     */
    function __construct(string $module, string $serial_number);

    /**
     * Process incoming data.
     *
     * @param string $data The raw string data that the client reported to us.
     * @return void
     */
    public function run(string $data): void;

    //// Helper Methods for raising Events

    /**
     * Store event
     *
     * Store event for this processor, assumes we have a serial_number
     *
     * @param string $type Use one of 'danger', 'warning', 'info' or 'success'
     * @param string $msg The message
     **/
    public function store_event(string $type, string $msg, string $data = '');

    /**
     * Delete event
     *
     * Delete event for this model, assumes we have a serial_number
     *
     **/
    public function delete_event();

}
