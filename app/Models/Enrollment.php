<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    public const SEMESTERS = ['1st', '2nd', 'Summer'];
    public const STATUSES = ['enrolled', 'dropped', 'completed'];

    protected $fillable = ['student_id', 'subject_id', 'school_year', 'semester', 'status', 'grade'];

    protected function casts(): array
    {
        return ['grade' => 'decimal:2'];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
