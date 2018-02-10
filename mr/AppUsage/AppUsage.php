<?php
namespace Mr\AppUsage;

use Mr\Core\SerialNumberModel;

class AppUsage extends SerialNumberModel
{
    protected $table = 'appusage';
    public $timestamps = false;

    protected $fillable = [
        'event',
        'bundle_id',
        'app_version',
        'app_name',
        'app_path',
        'last_time_epoch',
        'last_time',
        'number_times',
    ];

    //// RELATIONSHIPS
}