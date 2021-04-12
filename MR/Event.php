<?php
namespace MR;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'event';
    
    //// RELATIONSHIPS

    /**
     * Retrieve the machine instance associated with this event.
     */
    public function machine() {
        return $this->belongsTo('App\Machine', 'serial_number', 'serial_number');
    }
}
