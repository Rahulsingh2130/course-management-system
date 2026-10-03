<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $fillable = [
        'type', 'course_id', 'name', 'email', 'phone', 'company',
        'funding_source', 'team_size', 'message', 'status',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
