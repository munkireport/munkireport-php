<?php
namespace Mr\Profile;


use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profile';

    protected $fillable = [
        'profile_uuid',
        'profile_name',
        'profile_removal_allowed',
        'payload_name',
        'payload_display',
        'payload_data'
    ];
}