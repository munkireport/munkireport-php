<?php

namespace munkireport\models;

use munkireport\models\MRModel as Eloquent;

/**
 * Class Business_unit
 *
 * To be replaced by Compatibility\BusinessUnit
 *
 * @package munkireport\models
 */
class Business_unit extends Eloquent
{
    protected $table = 'business_unit';

    protected $fillable = [
        'unitid',
        'property',
        'value',
    ];
}
