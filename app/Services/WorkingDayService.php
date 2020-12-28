<?php

namespace App\Services;

use App\Repositories\WorkingDayRepository;

class WorkingDayService
{
    protected $workingDay;
    public function __construct(WorkingDayRepository $workingDay)
    {
        $this->workingDay = $workingDay;
    }
    public function getWorkingDays()
    {
        return $this->workingDay->index();
    }
    public function storeDay($data)
    {
        $this->workingDay->store($data->day);
    }
}