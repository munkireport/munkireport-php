<?php

namespace munkireport\models;

use munkireport\models\MRModel as Eloquent;

class Module_marketplace_model extends Eloquent
{
    protected $table = 'modules';

    protected $fillable = [
        'module',
        'version',
        'url',
        'maintainer',
        'date_updated',
        'core',
        'packagist',
    ];
}
