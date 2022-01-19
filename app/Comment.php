<?php
namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comment';

    protected $fillable = [
        'serial_number',
        'section',
        'user',
        'text',
        'html',
        'timestamp',
    ];

    public $timestamps = false;
}
