<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hash extends Model
{
    protected $table = 'hash';

    protected $fillable = [
        'serial_number',
        'name',
        'hash',
        'timestamp',
    ];
}
