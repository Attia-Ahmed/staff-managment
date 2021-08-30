<?php

namespace App\Models;

use App\Models\EmployerStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;


class Employer extends Model
{

    use HasFactory;

    protected $fillable = ["name", "last_status_id"];

    protected $hidden = ['last_status_id'];
    protected $guarded = [];


    public function getStatusAttribute()
    {


        $last_status = $this->getLastStatus();
        if ($last_status) {
            $offline_at = $last_status->offline_at;
            if ($offline_at == null) {
                return "online";
            }

        }
        return "offline";
    }


    public function getLastStatus()
    {
        if ($this->last_status_id) {
            return EmployerStatus::find($this->last_status_id);
        }
        return null;

    }

    public function updateStatus($new_status, $date)
    {
        /**
         * we will change in these situation
         * 1-employer is online and no record found (create first record)
         * 3-employer is online and current status is offline (create new record)
         * 2-employer is offline and current status is online (modifie offline at record)
         * otherwise just infrom customer his status
         */

        $old_status = $this->status;
        if ($new_status == "online" && $old_status == "offline") {
            //this case is first time online;

            $last_status = EmployerStatus::create([
                "employer_id" => $this->getKey(),
                "online_at" => $date
            ]);
            $this->last_status_id = $last_status->id;
            $this->save();


        } elseif ($old_status == "online" && $new_status == "offline") {

            $last_status = $this->getLastStatus();
            $last_status->offline_at = $date;
            $last_status->save();


        }

        return $this;
    }

    /**
     * @return Collection
     */
    private function getDailyOnlinePeriod($day)
    {
        $day = Carbon::create($day);

        // TODO how to move these where statments as a model method (overlapped).
        return EmployerStatus::where([
            "employer_id" => $this->id,
        ])->whereDate('offline_at', '<=', $day)
            ->orWhereNull("offline_at")
            ->whereDate('online_at', '>=', $day)->get();
    }

    public function isOnlineBeforeShiftEnd($period_start, $shift_end)
    {

        return $period_start <= $shift_end;
    }

    public function isOfflineAfterShiftStart($period_end, $shift_start)
    {

        return $period_end >= $shift_start;
    }

    public function calcPeriodShiftOverlap($period_start, $period_end, $shift_start, $shift_end)
    {

        /**
         * this case the user is  online before  the this shift starts
         * so we must calculate from shift start
         */

        if ($period_start <= $shift_start) {
            $period_start = $shift_start;

        }

        /**
         * this case the user is  offline after  the this shift end
         * so we must calculate unitl shift ends
         */

        if ($period_end >= $shift_end) {
            $period_end = $shift_end;
        }
        return Carbon::create($period_start)->diffInSeconds(Carbon::create($period_end));
    }

    public function getDailyOnlineSeconds($day)
    {
        $online_periods = $this->getDailyOnlinePeriod($day);

        // todo replace with relation
        $shifts = EmployerSchedule::where([
            "employer_id" => $this->id,
        ])->whereDate('day', '=', $day)->get();

        return $shifts->reduce(function ($total, $shift) use ($online_periods) {

            $total += $online_periods->filter(function ($period) use ($shift) {
                /**
                 * @var $period EmployerStatus
                 * @var $shift EmployerSchedule
                 */
                return (
                    ($this->isOnlineBeforeShiftEnd($period->getPeriodStart(), $shift->shift_end))
                    &&
                    ($this->isOfflineAfterShiftStart($period->getPeriodEnd(), $shift->shift_start))
                );

            })->reduce(function ($shift_online, $period) use ($shift) {
                /**
                 * @var $period EmployerStatus
                 * @var $shift EmployerSchedule
                 */
                $shift_online += $this->calcPeriodShiftOverlap($period->getPeriodStart(), $period->getPeriodEnd(), $shift->shift_start, $shift->shift_end);

                return $shift_online;
            });

            return $total;
        });


    }

    public function getLastSeenAttribute()
    {
        $last_status = $this->getLastStatus();
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

    public function last_status()
    {

        return $this->hasOne(EmployerStatus::class, "employer_id", "last_status_id")->latestOfMany();

    }
}
