<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Accessory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'type', 'description',
        'price', 'original_price', 'image',
        'stock', 'is_active', 'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'price'          => 'decimal:2',
            'original_price' => 'decimal:2',
            'is_active'      => 'boolean',
            'is_featured'    => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Accessory $a) {
            if (empty($a->slug)) {
                $a->slug = Str::slug($a->name);
            }
        });
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('uploads/images/accessories/' . $this->image);
        }

        return asset('assets/images/default.png');
    }

    public static function types(): array
    {
        return [
            'cage'       => 'Cage & Habitat',
            'food'       => 'Food & Nutrition',
            'toy'        => 'Toys & Enrichment',
            'supplement' => 'Supplements',
            'perch'      => 'Perches & Stands',
            'other'      => 'Other',
        ];
    }

    public function getTypeLabelAttribute(): string
    {
        return self::types()[$this->type] ?? ucfirst($this->type);
    }

    public function getStockStatusAttribute(): array
    {
        if ($this->stock === 0)  return ['label' => 'Out of Stock', 'level' => 'out'];
        if ($this->stock <= 3)   return ['label' => 'Low Stock (' . $this->stock . ')', 'level' => 'low'];
        return ['label' => 'In Stock (' . $this->stock . ')', 'level' => 'in'];
    }
}
