<?php

namespace Compatibility;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Business Unit Model (from v5).
 *
 * Currently business units are implemented as a key - value table containing three things:
 * 
 * - A business unit identifier (unitid).
 * - A property name eg. (name, address and link).
 * - A property value (corresponding to the property name).
 *
 * Property names CAN be duplicate (i.e machine_group can have multiple values)
 *
 * @package App
 * @property int $id
 * @property int $unitid
 * @property string $property
 * @property string $value
 */
class BusinessUnit extends Model
{
    // Standard values for the `property` column.
    const PROP_NAME = 'name';
    const PROP_ADDRESS = 'address';
    const PROP_LINK = 'link';
    const PROP_USER = 'user';
    const PROP_MANAGER = 'manager';
    const PROP_ARCHIVER = 'archiver';
    const PROP_MACHINE_GROUP = 'machine_group';

    protected $table = 'business_unit';
    public $timestamps = false;

    protected $fillable = [
        'unitid',
        'property',
        'value'
    ];

    protected $casts = [
        'unitid' => 'integer'
    ];

    /**
     * Create a set of BusinessUnit rows with given parameters.
     *
     * @param integer $id The business unit Identifier
     * @param string $name Name of the business unit
     * @param null|string $address Address
     * @param null|string $link More information link.
     * @return array
     */
    public static function createWithParameters(int $id, string $name, ?string $address = null, ?string $link = null) {
        $nameRow = BusinessUnit::create(
            ['property' => BusinessUnit::PROP_NAME, 'value' => $name, 'unitid' => $id]);

        $result = [
            'name' => $nameRow
        ];

        if ($address) {
            $addressRow = BusinessUnit::create(
                ['property' => BusinessUnit::PROP_ADDRESS, 'value' => $address, 'unitid' => $id]);
            $result['address'] = $addressRow;
        }

        if ($link) {
            $linkRow = BusinessUnit::create(
                ['property' => BusinessUnit::PROP_LINK, 'value' => $link, 'unitid' => $id]
            );
            $result['link'] = $linkRow;
        }
        
        return $result;
    }

    /**
     * Determine whether the given User/Group(s) are a member of any Legacy Style Business Units.
     *
     * @param string $userPrincipal The user name/principal which has been added to business unit(s)
     * @param array $groupNames A list of group names which will have been added with the '@' prefix.
     * @return array An array of Business Unit ID's which the supplied user or groups are a member of
     */
    public static function memberships(string $userPrincipal, array $groupNames): array {
        $value = [];
        $userMemberships = self::members()
            ->where('value', $userPrincipal)
            ->get();

        foreach ($userMemberships as $membership) {
            $value += $membership->unitid;
        }

        $prefixGroup = function($name) { return "@".$name; };
        $groupsPrefixed = array_map($prefixGroup, $groupNames);
        $groupMemberships = self::members()
            ->whereIn('value', $groupsPrefixed)
            ->get();

        foreach ($groupMemberships as $membership) {
            $value += $membership->unitid;
        }

        return $value;
    }

    //// RELATIONSHIPS

    public function businessUnitable() {
        return $this->morphTo();
    }

    //// SCOPES

    /**
     * Return only BusinessUnit rows with details about Managers and Users.
     *
     * @param Builder $query
     * @return Builder The amended query
     */
    public function scopeMembers(Builder $query): Builder {
        return $query->whereIn('property', [
            BusinessUnit::PROP_MANAGER,
            BusinessUnit::PROP_USER,
            BusinessUnit::PROP_ARCHIVER,
        ]);
    }

    /**
     * Return only rows for the given Business Unit identifier.
     *
     * @param Builder $query
     * @param int $unitId Business Unit Identifier
     * @return Builder The amended query
     */
    public function scopeBusinessUnit(Builder $query, int $unitId): Builder {
        return $query->where('unitid', '=', $unitId);
    }

}
