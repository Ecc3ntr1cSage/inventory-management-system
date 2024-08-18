<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Index extends Model
{
    use HasFactory;

    protected $table = 'indexes';
    protected $fillable = [
        'stock_id',
        'date',
        'reference_no',
        'in_quantity',
        'out_quantity',
        'balance',
        'name',
    ];

    public function inventory()
    {
        return $this->belongsTo(Stock::class);
    }
}
