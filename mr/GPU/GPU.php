<?php
namespace Mr\GPU;

use Mr\Core\SerialNumberModel;

class GPU extends SerialNumberModel
{
    protected $table = 'gpu';
    public $timestamps = false;

    protected $fillable = [
        'model',
        'vendor',
        'vram',
        'pcie_width',
        'slot_name',
        'device_id',
        'gmux_version',
        'efi_version',
        'revision_id',
        'rom_revision'
    ];


    protected static function boot() {
        parent::boot();
        static::addGlobalScope(new \Mr\Scope\MachineGroupScope);
    }
}