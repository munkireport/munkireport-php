<?php
namespace Mr\Warranty;

use Illuminate\Database\Eloquent\Model;

class Warranty extends Model
{
    protected $table = 'warranty';

    protected $fillable = [
        'purchase_date', 'end_date', 'status'
    ];
}