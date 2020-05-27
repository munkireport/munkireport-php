<?php

namespace munkireport\models;

use munkireport\models\MRModel as Eloquent;

class Cache extends Eloquent
{
    protected $table = 'cache';

    protected $fillable = [
        'module',
        'property',
        'value',
        'timestamp',
    ];
}
