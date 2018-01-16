<?php
namespace MrModule\Security;

use Mr\Core\SerialNumberModel;

class Security extends SerialNumberModel
{
    protected $table = 'security';

    protected $fillable = [
        'serial_number',
        'gatekeeper',
        'sip'
    ];

    //// RELATIONSHIPS

}