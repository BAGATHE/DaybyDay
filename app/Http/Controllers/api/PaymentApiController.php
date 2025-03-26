<?php

namespace App\Http\Controllers\api;

use App\Constante\Constante;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\Invoice\GenerateInvoiceStatus;
use App\Services\Invoice\InvoiceCalculator;
use App\Services\Payement\PaymentService;
use App\Utils\ResponseUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentApiController extends Controller
{
    protected $paymentService;
    public function __construct(paymentService $paymentService){
        $this->paymentService = $paymentService;
    }

    public function index(){
        try {
            $payements = $this->paymentService->getPayement();
            return ResponseUtil::responseStandard('success', ["payments" => $payements]);
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
    public function update(Request $request)
    {
        $payment = Payment::find($request->id);
        $invoice = $payment->invoice;


        $invoiceCalculator = new InvoiceCalculator($invoice);
        $montant_du = $invoiceCalculator->getAmountDue()->getAmount();
        $nouveau_montant = $request->amount * Constante::COEFFICIENT;

        $total_a_payé = $montant_du + $payment->amount;

        if ($total_a_payé < $nouveau_montant) {
            $montant_excedentaire = $nouveau_montant - $total_a_payé;
            return ResponseUtil::responseStandard('error', [
                    'message' => "Le montant inséré dépasse de $montant_excedentaire le montant total dû."]);
        }
        $payment->amount = $nouveau_montant;
        $payment->save();

        $invoice = $payment->invoice;
        $status = new GenerateInvoiceStatus($invoice);
        $status->createStatus();
        // Retourner une confirmation
        return ResponseUtil::responseStandard('success', ["message" => "modification reussi"]);
    }

    public function delete(Request $request){
        $result = DB::table('payments')->where('id','=',$request->id)->delete();
        return ResponseUtil::responseStandard('success', ["message" => "suppression reussie"]);
    }

}
