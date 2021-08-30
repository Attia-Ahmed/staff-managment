<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Models\Builders\EmployerStatusBuilder;

class EmployerStatus extends Model
{
    use HasFactory;

    protected $fillable = ["employer_id", "online_at", "offline_at"];
    protected $hidden = ["created_at", "updated_at"];

    public function isOnline()
    {
        return !is_null($this->offline_at);
    }

    public function getPeriodStart()
    {
        return $this->online_at;
    }

    public function getPeriodEnd()
    {
        if ($this->isOnline()) {

            return $this->offline_at;
        }
        return Carbon::now();

    }


    public function newEloquentBuilder($query)
    {
        return new EmployerStatusBuilder($query);
    }


}
