<?php
namespace Mr\TimeMachine;

use Illuminate\Database\Eloquent\Model;

class TimeMachine extends Model
{
    protected $table = 'timemachine';

    protected $fillable = [
        'last_success',
        'last_failure',
        'last_failure_msg',
        'duration'
    ];

    protected $casts = [
        'last_success' => 'datetime',
        'last_failure' => 'datetime'
    ];
}