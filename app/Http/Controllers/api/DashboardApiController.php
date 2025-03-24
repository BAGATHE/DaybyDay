<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Offer;
use App\Models\Payment;
use App\Services\charts\ChartService;
use App\Services\Dashboard\DashboardService;
use App\Utils\ResponseUtil;

class DashboardApiController extends Controller
{
    protected $dashboardService;
    protected  $chartService;

    public function __construct(DashboardService $dashboardService,ChartService $chartService)
    {
        $this->dashboardService = $dashboardService;
        $this->chartService = $chartService;
    }

    public function index(Request $request){
        try{
            $year = $request->only('year');

            $data = $this->dashboardService->getDashboardData();
            $paymentsource = $this->chartService->getPaymentBySource($year);
            $payementInvoicelines = $this->chartService->getPayementAndInvoiceLine($year);
            $clients = $this->chartService->getNumberClientinYear($year);
            return ResponseUtil::responseStandard('success', ["kpi"=>$data,"paymentsource"=>$paymentsource,'payementInvoicelinesAmount'=>$payementInvoicelines,'clients'=>$clients]);
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
