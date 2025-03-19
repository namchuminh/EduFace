<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'student_code', 
        'name', 
        'email', 
        'phone', 
        'birth_date', 
        'gender', 
        'address', 
        'department', 
        'class'
    ];
}
