<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    use HasFactory;

    public const MODES = [
        'classroom' => 'Classroom',
        'online_instructor' => 'Online Instructor-led',
        'online_self_paced' => 'Online Self-paced',
        'onsite' => 'Onsite',
    ];

    protected $fillable = [
        'course_id',
        'instructor_id',
        'batch_name',
        'mode',
        'location',
        'starts_at',
        'ends_at',
        'seat_limit',
        'price',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_active' => 'boolean',
            'price' => 'decimal:2',
        ];
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function modeLabel(): string
    {
        return self::MODES[$this->mode] ?? ucfirst($this->mode);
    }

    public function effectivePrice()
    {
        return $this->price ?? $this->course->price;
    }

    public function seatsRemaining(): int
    {
        return max(0, $this->seat_limit - $this->enrollments()->where('status', '!=', 'cancelled')->count());
    }

    public function isFull(): bool
    {
        return $this->seatsRemaining() <= 0;
    }

    public function scopeUpcoming($query)
    {
        return $query->where('starts_at', '>=', now())->orderBy('starts_at');
    }
}
