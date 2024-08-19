<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalibrationLogbook extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'date',
        'technician',
        'document',
        'institution',
    ];

    protected $cats = [
        'date' => 'datetime:d-m-Y',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
