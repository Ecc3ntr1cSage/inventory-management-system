<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $table = 'applications';
    protected $fillable = [
        'user_id',
        'asset_id',
        'description',
        'reason',
        'position',
        'department',
        'location',
        'application_date',
        'date_issued',
        'date_returned',
        'handler',
        'receiver',
        'status'
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
