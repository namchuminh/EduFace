<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'schedule_id',
        'checked_at',
        'status',
    ];

    public function student(){
        return $this->belongsTo(Student::class, 'student_id');
    }
}




