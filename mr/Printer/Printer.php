<?php
namespace Mr\Printer;

use Mr\Core\SerialNumberModel;

class Printer extends SerialNumberModel
{
    protected $table = 'printer';

    protected $fillable = [
        'name',
        'ppd',
        'driver_version',
        'url',
        'default_set',
        'printer_status',
        'printer_sharing'
    ];
}