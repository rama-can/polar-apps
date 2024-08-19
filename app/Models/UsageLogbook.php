<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UsageLogbook extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'date',
        'name',
        'status',
        'total_duration',
        'temperature',
        'rh',
        'note',
    ];

    protected $casts = [
        'date' => 'datetime:d-m-Y',
        'total_duration' => 'datetime:H:i',
        'temperature' => 'float',
        'rh' => 'float',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
