<?php
namespace Mr\Bluetooth;

use Mr\Core\SerialNumberModel;

class BluetoothInfo extends SerialNumberModel
{
    protected $table = 'bluetooth';

    protected $fillable = [
        'battery_percent',
        'device_type',
    ];

    protected $casts = [
        'battery_percent' => 'integer'
    ];

    //// RELATIONSHIPS
}