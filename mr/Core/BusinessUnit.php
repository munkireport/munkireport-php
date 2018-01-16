<?php

namespace Mr\Core;

use Illuminate\Database\Eloquent\Model;

class BusinessUnit extends Model
{
    protected $table = 'business_unit';

    protected $fillable = [
        'property',
        'value'
    ];

    //// RELATIONSHIPS

}
