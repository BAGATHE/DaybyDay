<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Offer;
use App\Models\Payment;
use App\Utils\ResponseUtil;
use Illuminate\Http\Request;

class DashboardApiController extends Controller
{
    public function index(){
        try{
            $totalInvoiceAmount = InvoiceLine::selectRaw('SUM(price * quantity) as total')->value('total');
            $data =[
                'totalOffers' => Offer::count(),
                'totalInvoices'=> Invoice::count(),
                'totalInvoiceAmount' =>$totalInvoiceAmount,
                'totalPayement'=> Payment::sum('amount'),
            ];
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
