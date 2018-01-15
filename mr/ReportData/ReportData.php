<?php
namespace Mr\ReportData;

use Illuminate\Database\Eloquent\Model;
use Mr\Core\Scopes\NotUpdatedForScope;
use Mr\Core\Scopes\UpdatedBetweenScope;
use Mr\Core\Scopes\UpdatedSinceScope;

class ReportData extends Model
{
    /**
     * @inheritDoc
     */
    protected $table = 'reportdata';

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'console_user',
        'long_username',
        'uptime',
        'machine_group',
        'remote_ip'
    ];

    protected $casts = [
        'uptime' => 'integer',
        'reg_timestamp' => 'integer',
        'timestamp' => 'integer',
    ];

    //// RELATIONSHIPS

    /**
     * Retrieve the machine model associated with this report data.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
//    public function machine() {
//        return $this->belongsTo('Mr\Machine', 'serial_number', 'serial_number');
//    }
//
//    // Leaky abstractions
//    public function munkireport() {
//        return $this->belongsTo('MrModule\MunkiReport\MunkiReport', 'serial_number', 'serial_number');
//    }
//
//    public function warranty() {
//        return $this->belongsTo('MrModule\Warranty\Warranty', 'serial_number', 'serial_number');
//    }

    //// SCOPES

    use UpdatedSinceScope;
    use NotUpdatedForScope;
    use UpdatedBetweenScope;
    
}