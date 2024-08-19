<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    use \Cviebrock\EloquentSluggable\Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'content',
        'available_stock',
        'stock',
        'status',
        'product_category_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function WorkInstruction(): HasMany
    {
        return $this->hasMany(WorkInstruction::class, 'product_id');
    }

    public function usageLogbooks(): HasMany
    {
        return $this->hasMany(UsageLogbook::class, 'product_id');
    }

    public function calibrationLogbooks(): HasMany
    {
        return $this->hasMany(CalibrationLogbook::class, 'product_id');
    }

    public function getThumbnailAttribute()
    {
        return asset('storage/' . $this->image);
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
