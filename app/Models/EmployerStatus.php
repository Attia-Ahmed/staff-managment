<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Models\Employer;
use Illuminate\Notifications\Notifiable;


class EmployerStatus extends Model
{
    use HasFactory;
    protected $fillable=["employer_id","online_at","offline_at"];
    protected $hidden=["created_at","updated_at"];

    public function employer()
{
    return $this->belongsTo(Employer::class,'employer_id',"last_status_id");
}
    
}
