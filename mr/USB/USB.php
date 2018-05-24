<?php
namespace Mr\USB;

use Mr\Core\SerialNumberModel;

class USB extends SerialNumberModel
{
    protected $table = 'usb';
    public $timestamps = false;

    protected $fillable = [
        'serial_number',
        'name',
        'type',
        'manufacturer',
        'vendor_id',
        'device_speed',
        'internal',
        'media',
        'bus_power',
        'bus_power_used',
        'extra_current_used',
        'usb_serial_number',
        'printer_id'
    ];

    protected $casts = [
        'internal' => 'boolean',
        'media' => 'boolean'
    ];


    protected static function boot() {
        parent::boot();
        static::addGlobalScope(new \Mr\Scope\MachineGroupScope);
    }
}