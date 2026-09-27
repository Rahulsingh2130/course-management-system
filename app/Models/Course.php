<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'price',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'price' => 'decimal:2',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function workshops()
    {
        return $this->hasMany(Workshop::class);
    }

    public function enrollments()
    {
        return $this->hasManyThrough(Enrollment::class, Workshop::class);
    }

    /**
     * Only courses that are active AND belong to an active category
     * are shown on the public catalog — mirrors the "dynamic course
     * and category filtering" requirement from the platform this is based on.
     */
    public function scopeVisibleToPublic($query)
    {
        return $query->where('is_active', true)
            ->whereHas('category', fn ($q) => $q->where('is_active', true));
    }
}
