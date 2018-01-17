<?php
namespace Mr\ManagedInstalls;


use Illuminate\Database\Eloquent\Model;
use Mr\Core\Scopes\TimestampNewerThanScope;

class ManagedInstall extends Model
{
    protected $table = 'managedinstalls';

    /// SCOPES

    use TimestampNewerThanScope;

    public function scopePending($query) {
        return $query->where('status', '=', 'pending');
    }
}