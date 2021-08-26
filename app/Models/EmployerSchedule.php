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
        "shift_start"=>"date:h:m",
        "shift_end"=>"date:h:m",

    ];
    protected $guarded=["id"];
    
 
}
