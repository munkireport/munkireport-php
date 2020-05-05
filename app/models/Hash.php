<?php

namespace munkireport\models;

use munkireport\models\MRModel as Eloquent;

class Hash extends Eloquent
{
    protected $table = 'hash';

    protected $fillable = [
        'serial_number',
        'name',
        'hash',
        'timestamp',
    ];
}
