<?php
namespace MR;

use Illuminate\Database\Eloquent\Model;

/**
 * The SerialNumberModel class is a base class for all models
 * with a `serial_number` column that can be directly related back to
 * a machine or reportdata entry.
 *
 * It exists to add scopes and relationships that are generic to most models.
 *
 * @package Mr
 */
class SerialNumberModel extends Model
{
    /**
     * Fetch the ReportData model associated with this model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reportData() {
        return $this->belongsTo('MR\ReportData', 'serial_number', 'serial_number');
    }

    /**
     * Fetch the Machine model associated with this model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function machine() {
        return $this->belongsTo('MR\Machine', 'serial_number', 'serial_number');
    }

    /**
     * Fetch events associated with this model.
     *
     * @return mixed
     */
    public function events() {
        return $this->hasMany('MR\Event', 'serial_number', 'serial_number');
    }
}
