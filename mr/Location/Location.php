<?php
namespace Mr\Location;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'location';

    protected $fillable = [
        'address',
        'altitude',
        'currentstatus',
        'ls_enabled',
        'lastlocationrun',
        'lastrun',
        'latitude',
        'latitudeaccuracy',
        'longitude',
        'longitudeaccuracy',
        'stalelocation'
    ];
}