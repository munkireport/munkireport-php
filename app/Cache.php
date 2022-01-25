<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Cache extends Model
{
    protected $table = 'cache';
    public $timestamps = false;

    protected $fillable = [
        'module',
        'property',
        'value',
        'timestamp',
    ];
}
