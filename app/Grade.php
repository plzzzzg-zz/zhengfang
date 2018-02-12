<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    //
    protected $fillable = [
        'student_id',
        'name',

    ];
    protected $table ='grades';
}
