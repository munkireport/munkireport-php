<?php
namespace Mr\Core\Scopes;

/**
 * This is a pseudo global scope to retrieve objects that have been updated since a number of days, hours etc.
 * You pass a DateInterval object and your models will be filtered relative to the current time.
 *
 * NOTE: This was not implemented as a global scope object, since they cannot take parameters.
 *
 * @package Mr\Scopes
 */
trait UpdatedSinceScope
{
    /**
     * Retrieve a list of models where the `updated_at` date is at least $interval duration old.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \DateInterval $interval The duration that the row has not been updated for
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpdatedSince($query, \DateInterval $interval) {
        $age = new \DateTime;
        $age->sub($interval);

        return $query->where('updated_at', '>', $age);
    }
}