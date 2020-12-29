<?php

namespace App\Repositories;

use App\Models\WorkingDay;

class WorkingDayRepository
{
    public function index()
    {
        $workingDays = WorkingDay::all('name');
        return $workingDays;
    }
    public function store($day)
    {
        WorkingDay::create([
            'name' =>  $day,
        ]);
    }
}
