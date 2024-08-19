<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductCategory extends Model
{
    use HasFactory, \Cviebrock\EloquentSluggable\Sluggable;

    protected $fillable = [
        'name', 'slug', 'description', 'image', 'is_active'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'product_category_id');
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
