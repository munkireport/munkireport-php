<?php
namespace Mr\FanTemps;

use Mr\Core\SerialNumberModel;

class FanTemp extends SerialNumberModel
{
    protected $table = 'fan_temps';
    public $timestamps = false;
}
