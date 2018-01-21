<?php
namespace Mr\FindMyMac;

use Mr\Core\SerialNumberModel;

class FindMyMac extends SerialNumberModel
{
    protected $table = 'findmymac';
    public $timestamps = false;

    protected $fillable = [
        'serial_number',
        'status',
        'ownerdisplayname',
        'email',
        'personid',
        'hostname'
    ];
}