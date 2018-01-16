<?php
namespace Mr\FileVaultStatus;

use Mr\Core\SerialNumberModel;

class FileVaultStatus extends SerialNumberModel
{
    protected $table = 'filevault_status';

    protected $fillable = [
        'filevault_status',
        'filevault_users'
    ];
}