<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stocks';
    protected $fillable = [
        'name',
        'group',
        'location',
        'balance'
    ];

    public function entries()
    {
        return $this->hasMany(Index::class);
    }
}
