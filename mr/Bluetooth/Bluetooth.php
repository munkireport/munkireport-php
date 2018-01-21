<?php
namespace Mr\Bluetooth;

use Mr\Core\SerialNumberModel;

class Bluetooth extends SerialNumberModel
{
    protected $table = 'bluetooth';
    public $timestamps = false;

    protected $fillable = [
        'battery_percent',
        'device_type',
    ];

    protected $casts = [
        'battery_percent' => 'integer'
    ];

    //// RELATIONSHIPS
}