<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRegistration extends Model
{
    use HasFactory;

    protected $table = 'class_registrations';

    protected $fillable = ['student_id', 'course_class_id'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function courseClass()
    {
        return $this->belongsTo(CourseClass::class, 'course_class_id');
    }
}
