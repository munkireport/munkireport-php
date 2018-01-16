<?php
namespace Mr\LocalAdmin;


use Illuminate\Database\Eloquent\Model;

class LocalAdmin extends Model
{
    protected $table = 'localadmin';

    protected $fillable = [
        'serial_number',
        'users'
    ];
}