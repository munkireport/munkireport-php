<?php
namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\NotUpdatedForScope;
use App\Scopes\UpdatedBetweenScope;
use App\Scopes\UpdatedSinceScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use munkireport\models\MRModel;

class ReportData extends MRModel
{
    /**
     * @inheritDoc
     */
    protected $table = 'reportdata';

    /**
     * @inheritDoc
     */
    protected $fillable = [
      'serial_number',
      'console_user',
      'long_username',
      'uid',
      'remote_ip',
      'uptime',
      'reg_timestamp',
      'machine_group',
      'archive_status',
      'timestamp',
    ];

    public $timestamps = false;

    //// RELATIONSHIPS

    /**
     * Retrieve the machine model associated with this report data.
     */
    public function machine(): BelongsTo {
        return $this->belongsTo('App\Machine', 'serial_number', 'serial_number');
    }

    //// SCOPES

    // Cannot use these while timestamps are disabled.
    // use UpdatedSinceScope;
    // use NotUpdatedForScope;
    // use UpdatedBetweenScope;

    /**
     * Scope this query so that only non-archived reportdata is returned.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeNotArchived(Builder $query): Builder {
        return $query->where('archive_status', 0);
    }
}
