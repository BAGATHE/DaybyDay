<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Services\charts\ChartService;
use App\Utils\ResponseUtil;
use Illuminate\Http\Request;

class ChartApiController extends Controller
{
    protected  $chartService;
    public function __construct(ChartService $chartService){
        $this->chartService = $chartService;
    }


    public function getpayementBysource(){
        try {
            $invoices = $this->chartService->getPaymentBySource();
            return ResponseUtil::responseStandard('success', ["payments" => $invoices]);
        } catch (\Exception $e) {
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
