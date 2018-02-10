<?php
namespace Mr\Applications;

use Mr\Core\SerialNumberModel;

class Application extends SerialNumberModel
{
    protected $table = 'applications';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'path',
        'last_modified',
        'obtained_from',
        'runtime_environment',
        'version',
        'info',
        'signed_by',
        'has64bit',
    ];

    //// RELATIONSHIPS
}