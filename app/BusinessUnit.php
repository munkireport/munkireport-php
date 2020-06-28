<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use MR\Kiss\Contracts\LegacyBusinessUnit;

/**
 * MunkiReport Business Unit
 *
 * Represents an organisational team/group that has access to a collection of machines.
 *
 * @package MR
 */
class BusinessUnit extends Model implements LegacyBusinessUnit
{
    protected $fillable = [
        'unitid',
        'property',
        'value',
    ];

    //// RELATIONSHIPS

    /**
     * Retrieve a list of members of this business unit (managers and users).
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function members() {
        return $this->belongsToMany('App\User');
    }

    /**
     * Retrieve users who are managers of this business unit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function managers() {
        return $this->members()->wherePivot('role', 'manager');
    }

    /**
     * Retrieve users who are basic users in this business unit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users() {
        return $this->members()->wherePivot('role', 'user');
    }

    /**
     * Retrieve the list of modules assigned to this business unit.
     * If there are none, we should treat this as all modules being enabled.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
//    public function modules() {
//        return $this->belongstoMany('App\Module');
//    }

    //// LegacyBusinessUnit

    /**
     * saveUnit is implemented for backwards compatibility with munkireport\lib\BusinessUnit.
     *
     * - Based on whether `unitid` === `new`, it will create a new business unit with max(unitid) + 1
     * - If the post_array contains `iteminfo`, each item in the iteminfo array will be considered
     * - items can contain new machine groups or new properties for the current business unit.
     *
     * @param $post_array
     * @return mixed|void
     */
    public function saveUnit($post_array)
    {
        // TODO: Implement saveUnit() method.
    }
}
