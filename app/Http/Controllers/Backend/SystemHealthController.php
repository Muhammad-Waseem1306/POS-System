<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\HealthCheckService;
use Illuminate\Http\JsonResponse;

class SystemHealthController extends Controller
{
    public function __construct(private HealthCheckService $healthCheckService) {}

    public function index()
    {
        $checks        = $this->healthCheckService->runAll();
        $overallStatus = $this->healthCheckService->overallStatus($checks);
        return view('backend.system-health.index', compact('checks', 'overallStatus'));
    }

    public function api(): JsonResponse
    {
        $checks        = $this->healthCheckService->runAll();
        $overallStatus = $this->healthCheckService->overallStatus($checks);
        return response()->json(['status' => $overallStatus, 'checks' => $checks]);
    }
}
