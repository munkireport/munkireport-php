<?php
namespace Mr\InstallHistory;

use Mr\Core\SerialNumberModel;

class InstallHistory extends SerialNumberModel
{
    protected $table = 'installhistory';

    protected $fillable = [
        'serial_number',
        'date',
        'displayName',
        'displayVersion',
        'packageIdentifiers',
        'processName'
    ];
}