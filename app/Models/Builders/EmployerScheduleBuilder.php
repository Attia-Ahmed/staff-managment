<?php

namespace App\Models\Builders;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class EmployerScheduleBuilder extends Builder
{
    public function whereOverlap(Carbon $start_date, Carbon $end_date)
    {
        $this->whereDate('shift_start', '<=', $end_date)
            ->whereDate('shift_start', '>=', $start_date);
        return $this;
    }
}
