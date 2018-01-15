<?php
namespace Mr\Backup2Go;

use Mr\Core\SerialNumberModel;

class Backup2Go extends SerialNumberModel
{
    protected $table = 'backup2go';

    protected $fillable = [
        'backupdate'
    ];
}