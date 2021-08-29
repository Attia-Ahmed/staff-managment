<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployerSchedule extends Model
{
    use HasFactory;

    protected $casts = [
        'day' => "date:Y-m-d",


    ];
    protected $guarded = ["id"];


}
