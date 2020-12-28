<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkingDayRequest;
use App\Services\WorkingDayService;
use Illuminate\Http\Response;

class WorkingDayController extends Controller
{
    protected $workingDay;
    public function __construct(WorkingDayService $workingDay)
    {
        $this->workingDay = $workingDay;
    }
    public function index()
    {
        $workingDays = $this->workingDay->getWorkingDays();
        return response()->json($workingDays, Response::HTTP_OK);
    }
    public function store(WorkingDayRequest $request)
    {
        $this->workingDay->storeDay($request);
        return response()->json(null, Response::HTTP_CREATED);
    }
}
