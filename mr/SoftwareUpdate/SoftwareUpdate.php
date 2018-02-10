<?php
namespace Mr\SoftwareUpdate;

use Mr\Core\SerialNumberModel;

class SoftwareUpdate extends SerialNumberModel
{
    protected $table = 'softwareupdate';
    public $timestamps = false;
}
