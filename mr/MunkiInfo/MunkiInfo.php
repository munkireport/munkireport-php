<?php
namespace Mr\MunkiInfo;

use Illuminate\Database\Eloquent\Model;

class MunkiInfo extends Model
{
    protected $table = 'munkiinfo';

    protected $fillable = [
        'munkiinfo_key',
        'munkiinfo_value'
    ];
}