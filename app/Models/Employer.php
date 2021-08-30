<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Employer extends Model
{

    use HasFactory;

    protected $fillable = ["name"];

    protected $guarded = [];


    public function getStatusAttribute()
    {


        $last_status = $this->last_status;
        if ($last_status) {
            $offline_at = $last_status->offline_at;
            if ($offline_at == null) {
                return "online";
            }

        }
        return "offline";
    }


    public function getLastSeenAttribute()
    {
        $last_status = $this->last_status;
        if ($last_status) {

            $offline_at = $last_status->offline_at;

            if ($offline_at) {
                return $offline_at;
            } else {
                return Carbon::now();
            }
        }
        return $this->updated_at;
    }

    public function statuses()
    {

        return $this->hasMany(EmployerStatus::class);

    }

    public function getLastStatusAttribute()
    {

        return $this->statuses()->get()->last();

    }

    public function schedules()
    {

        return $this->hasMany(EmployerSchedule::class, "employer_id", "id");

    }
}
