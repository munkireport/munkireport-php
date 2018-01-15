<?php
namespace Mr\Core\Scopes;

/**
 * This is a pseudo global scope to retrieve objects that have not been updated for a certain duration of time.
 * You pass a DateInterval object and your models will be filtered relative to the current time.
 *
 * NOTE: This was not implemented as a global scope object, since they cannot take parameters.
 *
 * @package Mr\Scopes
 */
trait NotUpdatedForScope
{
    /**
     * Retrieve a list of models where the `updated_at` date is at least $interval duration old.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \DateInterval $interval The duration that the row has not been updated for
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotUpdatedFor($query, \DateInterval $interval) {
        $age = new \DateTime;
        $age->sub($interval);

        return $query->where('updated_at', '<', $age);
    }
}