<?php
namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;

    protected $table = 'event';

    protected $fillable = [
        'serial_number',
        'type',
        'module',
        'msg',
        'data',
        'timestamp',
    ];

    public $timestamps = false;

    //// RELATIONSHIPS

    /**
     * Get the machine that this event was recorded for.
     */
    public function machine(): BelongsTo
    {
        return $this->belongsTo('App\Machine', 'serial_number', 'serial_number');
    }
}
