<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\v1\APICore;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Services\APIReportService;

/**
 * @resource Report
 */
class Report extends APICore
{
    protected $APIReportService;

    public function __construct(Request $request, APIReportService $APIReportService)
    {
        parent::__construct();

        $this->request = $request;
        $this->APIReportService = $APIReportService;
    }

    public function reportPost($true_id)
    {
        $response = $this->APIReportService->reportPost($true_id);

        return response()->json($response);
    }
}
