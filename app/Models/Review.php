<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'bird_id',
        'user_id',
        'rating',
        'body',
        'approved',
    ];

    protected function casts(): array
    {
        return [
            'rating'   => 'integer',
            'approved' => 'boolean',
        ];
    }

    public function bird(): BelongsTo
    {
        return $this->belongsTo(Bird::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
