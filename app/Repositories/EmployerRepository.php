<?php

namespace App\Repositories;

use App\Models\EmployerSchedule;
use App\Models\EmployerStatus;
use Carbon\Carbon;
use App\Models\Employer;


class EmployerRepository
{
    protected $employer;

    public function __construct(Employer $employer)
    {

        $this->employer = $employer;


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

        $old_status = $this->employer->status;
        if ($new_status == "online" && $old_status == "offline") {
            //this case is first time online;

            $last_status = EmployerStatus::create([
                "employer_id" => $this->employer->getKey(),
                "online_at" => $date
            ]);
            $this->employer->save();


        } elseif ($old_status == "online" && $new_status == "offline") {

            $last_status = $this->employer->last_status;
            $last_status->offline_at = $date;
            $last_status->save();


        }

        return $this->employer;
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

    public function getDailyOnlineSeconds(Carbon $start_date, Carbon $end_date)
    {

        $online_periods = $this->employer->statuses()->whereOverlap($start_date, $end_date)->get();

        // todo replace with relation --Done
        $shifts = $this->employer->schedules()->whereOverlap($start_date, $end_date)->get();;

        return $shifts->reduce(function ($total, $shift) use ($online_periods, $start_date, $end_date) {
            $shift = $shift->limitShiftToCustomPeriod($start_date, $end_date);
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

}
