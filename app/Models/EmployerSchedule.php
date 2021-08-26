<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
class EmployerSchedule extends Model
{
    use HasFactory;

    protected $casts = [
        'day'=>"date:Y-m-d",
        "shift_start"=>"date:H:m",
        "shift_end"=>"date:H:m",

    ];
    protected $guarded=["id"];
    
 
}
