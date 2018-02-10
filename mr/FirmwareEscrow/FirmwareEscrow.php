<?php
namespace Mr\FirmwareEscrow;

use Mr\Core\SerialNumberModel;

class FirmwareEscrow extends SerialNumberModel
{
    protected $table = 'firmware_escrow';
    public $timestamps = false;
}

