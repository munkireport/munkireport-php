<?php
namespace Mr\ARD;

use Mr\Core\Scopes\VueTableScope;
use Mr\Core\SerialNumberModel;

class ARD extends SerialNumberModel
{
    protected $table = 'ard';

    protected $fillable = [
        'serial_number',
        'Text1',
        'Text2',
        'Text3',
        'Text4'
    ];

    //// RELATIONSHIPS
}