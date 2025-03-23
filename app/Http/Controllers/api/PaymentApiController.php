<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Services\Payement\PaymentService;
use App\Utils\ResponseUtil;

class PaymentApiController extends Controller
{
    protected $paymentService;
    public function __construct(paymentService $paymentService){
        $this->paymentService = $paymentService;
    }

    public function index(){
        try {
            $invoices = $this->paymentService->getPayement();
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
