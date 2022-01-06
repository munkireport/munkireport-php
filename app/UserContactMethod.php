<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserContactMethod extends Model
{
    use HasFactory;

    protected $table = 'users_contact_methods';

    protected $fillable = [
        'channel',
        'address'
    ];

    //// RELATIONSHIPS

    /**
     * Retrieve the user that this contact method is associated with.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo {
        return $this->belongsTo('App\User');
    }
}
