<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends SerialNumberModel
{
    protected $table = 'tag';

    protected $fillable = [
        'serial_number',
        'tag',
        'user',
        'timestamp',
    ];

    public $timestamps = false;
}
