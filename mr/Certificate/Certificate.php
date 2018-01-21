<?php
namespace Mr\Certificate;

use Mr\Core\SerialNumberModel;

class Certificate extends SerialNumberModel
{
    protected $table = 'certificate';
    public $timestamps = false;

    protected $casts = [
        'cert_exp_time' => 'int'
    ];

    protected $fillable = [
        'cert_exp_time',
        'cert_path',
        'cert_cn'
    ];
}