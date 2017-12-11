<?php

namespace munkireport\models;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Hash extends Eloquent
{
    protected $fillable = ['serial_number', 'name', 'hash'];
}
