<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Offer;
use App\Models\Payment;
use App\Services\Dashboard\DashboardService;
use App\Utils\ResponseUtil;

class DashboardApiController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(){
        try{
            $data = $this->dashboardService->getDashboardData();
            return ResponseUtil::responseStandard('success', ["kpi" => $data]);
        }catch (\Exception $e){
            return ResponseUtil::responseStandard(
                'error',
                null,
                [
                    'code' => 500,
                    'message' => 'Erreur lors de la récupération des données : ' . $e->getMessage()
                ],
                500
            );
        }
    }
}
