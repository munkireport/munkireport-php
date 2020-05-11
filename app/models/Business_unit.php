<?php

namespace munkireport\models;

use munkireport\models\MRModel as Eloquent;

class Business_unit extends Eloquent
{
    protected $table = 'business_unit';

    protected $fillable = [
        'unitid',
        'property',
        'value',
    ];
}
