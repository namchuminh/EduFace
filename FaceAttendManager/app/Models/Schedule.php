<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_class_id',
        'date',
        'start_time',
        'end_time',
        'room'
    ];

    public function courseClass(){
        return $this->belongsTo(CourseClass::class, 'course_class_id');
    }
}
