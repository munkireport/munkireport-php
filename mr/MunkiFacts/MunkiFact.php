<?php
namespace Mr\MunkiFacts;

use Mr\Core\SerialNumberModel;

class MunkiFact extends SerialNumberModel
{
    protected $table = 'munki_facts';
    public $timestamps = false;
}
