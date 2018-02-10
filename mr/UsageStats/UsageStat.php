<?php
namespace Mr\UsageStats;

use Mr\Core\SerialNumberModel;

class UsageStat extends SerialNumberModel
{
    protected $table = 'usage_stats';
    public $timestamps = false;
}
