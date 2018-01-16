<?php

namespace Mr\Core;

use Illuminate\Database\Eloquent\Model;

class MachineGroup extends Model
{
    protected $table = 'machine_group';

    protected $fillable = [
        'property',
        'value'
    ];

    //// RELATIONSHIPS
}
