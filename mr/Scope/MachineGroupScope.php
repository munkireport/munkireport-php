<?php

namespace Mr\Scope;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Class MachineGroupScope
 * @package Mr\Scope
 *
 * This global scope should apply the machine group filter, previously defined in site_helper.php:310
 *
 * @see site_helper.php:310 get_machine_group_filter()
 */
class MachineGroupScope implements Scope
{

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @todo Does not support the same amount of parameters as get_machine_group_filter() eg. you cannot specify
     *       a different table name for reportdata.
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if ($groups = get_filtered_groups()) {
            $builder->whereIn('reportdata.machine_group', $groups);
        }
    }
}
