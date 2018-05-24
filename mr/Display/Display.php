<?php
namespace Mr\Display;

use Mr\Core\SerialNumberModel;

class Display extends SerialNumberModel
{
    const TYPE_INTERNAL = 0;
    const TYPE_EXTERNAL = 1;

    protected $table = 'displays';
    public $timestamps = false;

    protected $fillable = [
        'type',
        'display_serial',
        'vendor',
        'model',
        'manufactured',
        'native'
    ];

    protected $casts = [
        'type' => 'integer',
        'manufactured' => 'integer'
    ];

    protected static function boot() {
        parent::boot();
        static::addGlobalScope(new \Mr\Scope\MachineGroupScope);
    }

    //// SCOPES

    public function scopeExternal($query)
    {
        return $query->where('type', Display::TYPE_EXTERNAL);
    }

    public function scopeInternal($query)
    {
        return $query->where('type', Display::TYPE_INTERNAL);
    }
}