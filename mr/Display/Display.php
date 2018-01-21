<?php
namespace Mr\Display;

use Mr\Core\SerialNumberModel;

class Display extends SerialNumberModel
{
    const TYPE_INTERNAL = 0;
    const TYPE_EXTERNAL = 1;

    protected $table = 'displays';
    public $timestamps = false;

    protected $fillable = [
        'type',
        'display_serial',
        'vendor',
        'model',
        'manufactured',
        'native'
    ];

    protected $casts = [
        'type' => 'integer',
        'manufactured' => 'integer'
    ];
}