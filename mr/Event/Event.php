<?php
namespace Mr\Event;

use Illuminate\Database\Eloquent\Model;
use Mr\Core\Scopes\TimestampNewerThanScope;

class Event extends Model
{
    protected $table = 'event';
    
    //// RELATIONSHIPS

    /**
     * Retrieve the machine instance associated with this event.
     */
    public function machine() {
        return $this->belongsTo('Mr\Machine\Machine', 'serial_number', 'serial_number');
    }

    //// SCOPES

    use TimestampNewerThanScope;
}