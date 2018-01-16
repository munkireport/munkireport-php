<?php
namespace Mr\MunkiReport;

use Mr\Core\SerialNumberModel;

class MunkiReport extends SerialNumberModel
{
    protected $table = 'munkireport';

    protected $fillable = [
        'runtype',
        'version',
        'errors',
        'warnings',
        'manifestname',
        'error_json',
        'warning_json',
        'starttime',
        'endtime'
    ];
}