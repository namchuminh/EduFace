<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_code',
        'subject_id',
        'lecturer_id',
        'semester',
        'academic_year',
        'student_count'
    ];

    public function lecturer(){
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    public function subject(){
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
