<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Bird extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'subtitle',
        'description',
        'price',
        'original_price',
        'image',
        'gallery',
        'badge',
        'species',
        'age',
        'temperament',
        'habitat',
        'habitat_guide',
        'nutrition',
        'nutrition_guide',
        'social',
        'social_guide',
        'wingspan_cm',
        'color',
        'is_active',
        'featured',
        'stock',
    ];

    protected function casts(): array
    {
        return [
            'gallery'       => 'array',
            'price'         => 'decimal:2',
            'original_price'=> 'decimal:2',
            'is_active'     => 'boolean',
            'featured'      => 'boolean',
        ];
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('uploads/images/birds/' . $this->image);
        }

        return asset('assets/images/default.png');
    }

    protected static function booted(): void
    {
        static::creating(function (Bird $bird) {
            if (empty($bird->slug)) {
                $bird->slug = Str::slug($bird->name);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function wishlistItems(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
