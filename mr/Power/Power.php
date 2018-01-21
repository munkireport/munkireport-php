<?php
namespace Mr\Power;

use Mr\Core\SerialNumberModel;

class Power extends SerialNumberModel
{
    protected $table = 'power';

    protected $fillable = [
        'serial_number',
        'manufacture_date',
        'design_capacity',
        'max_capacity',
        'max_percent',
        'current_capacity',
        'current_percent',
        'cycle_count',
        'temperature',
        'condition'
    ];
}