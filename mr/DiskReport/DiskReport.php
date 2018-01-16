<?php
namespace Mr\DiskReport;


use Mr\Core\SerialNumberModel;

class DiskReport extends SerialNumberModel
{
    /**
     * These consts identify the strings to match for SMART statuses.
     */
    const SMART_FAILING = 'Failing';
    const SMART_VERIFIED = 'Verified';
    const SMART_UNSUPPORTED = 'Not Supported';

    protected $table = 'diskreport';

    protected $fillable = [
        'TotalSize',
        'FreeSpace',
        'Percentage',
        'SMARTStatus',
        'VolumeType',
        'BusProtocol',
        'Internal',
        'MountPoint',
        'VolumeName',
        'CoreStorageEncrypted'
    ];

    protected $casts = [
        'TotalSize' => 'integer',
        'FreeSpace' => 'integer',
        'Percentage' => 'integer'
    ];

    //// RELATIONSHIPS

}