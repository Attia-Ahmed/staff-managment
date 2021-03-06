<?php

namespace App\Models\Builders;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class EmployerStatusBuilder extends Builder
{
    /**
     * @return EmployerStatusBuilder
     */
    public function whereOverlap(Carbon $start_date, Carbon $end_date)
    {


        $this->whereDate('offline_at', '<=', $start_date)
            ->orWhereNull("offline_at")
            ->whereDate('online_at', '<', $end_date);
        return $this;
    }
}
