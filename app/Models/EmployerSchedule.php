<?php

namespace App\Models;

use App\Models\Builders\EmployerScheduleBuilder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployerSchedule extends Model
{
    use HasFactory;

    protected $casts = [
        'day' => "date:Y-m-d",


    ];
    protected $guarded = ["id"];

    public function limitShiftToCustomPeriod(Carbon $start_date, Carbon $end_date)
    {
        if ($this->shift_start <= $start_date) {
            $this->shift_start = $start_date;

        }

        if ($this->shift_end >= $end_date) {
            $this->shift_end = $end_date;
        }
        return $this;
    }

    public function employer()
    {
        return $this->belongsTo(Employer::class, 'employer_id', "id");
    }

    public function newEloquentBuilder($query)
    {
        return new EmployerScheduleBuilder($query);
    }

}
