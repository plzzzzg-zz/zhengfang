<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    //
    protected $fillable = [
        'student_id',
        'name',
        'place',
        'week_begin',
        'week_end',
        'class_begin',
        'class_end',
        'weekday',
        'teacher',
        'week_odd', //单双周：0,1,2
        ];
    protected $table ='courses';
}
