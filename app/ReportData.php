<?php
namespace App;

use Compatibility\Scopes\FilterScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\NotUpdatedForScope;
use App\Scopes\UpdatedBetweenScope;
use App\Scopes\UpdatedSinceScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use munkireport\models\MRModel;
use Laravel\Scout\Searchable;

/**
 * @property int $id
 * @property string $serial_number
 * @property string $console_user
 * @property string $long_username
 * @property string $remote_ip
 * @property int $uptime
 * @property int $machine_group
 * @property int $reg_timestamp
 * @property int $timestamp
 * @property int $uid
 * @property int $archive_status
 */
class ReportData extends MRModel
{
    use HasFactory;
    use Searchable;
    use FilterScope;

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
    use ProvidesHistogram;

    /**
     * Scope this query so that only non-archived reportdata is returned.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeNotArchived(Builder $query): Builder {
        return $query->where('archive_status', 0);
    }

    /**
     * Scope this query so that only reportdata records associated with the given array of machine group ID's are
     * returned.
     *
     * @param Builder $query
     * @param array $machineGroupIds
     * @return Builder
     */
    public function scopeMachineGroupIds(Builder $query, array $machineGroupIds): Builder {
        return $query->whereIn('machine_group', $machineGroupIds);
    }

    //// SCOUT

    /**
     * Implements SCOUT interface for searchable models.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => (int) $this->id,
            'serial_number' => $this->serial_number,
            'console_user' => $this->console_user,
            'remote_ip' => $this->remote_ip,
        ];
    }
}
