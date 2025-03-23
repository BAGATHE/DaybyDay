<?php

namespace App\Services\Payement;

use App\Models\Payment;

class PaymentService
{
    public function getPayement()
    {
        try {
            $payments = Payment::with('invoice')->get();
            $filteredPayments = $payments->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'amount' => strval($payment->amount),
                    'description' => $payment->description,
                    'payment_source' => $payment->payment_source,
                    'payment_date' => $payment->payment_date,
                    'invoice_id' => $payment->invoice->id,
                ];
            });

            return $filteredPayments;
        } catch (\Exception $e) {
            throw new \Exception('Erreur lors de la récupération des paiements : ' . $e->getMessage());
        }
    }
}