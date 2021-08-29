<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Carbon;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public static function addHour($day, $hour)
    {
        return Carbon::create($day)->addHours($hour)->toISOString();

    }

}
