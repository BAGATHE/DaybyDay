<?php

namespace App\Services\Invoice;

use App\Models\Invoice;
use App\Utils\ResponseUtil;
use Carbon\Carbon;

class InvoiceService
{
    public function getInvoices()
    {
        try {
            $invoices = Invoice::with('client', 'source', 'payments')->get()->map(function ($invoice) {
                $invoiceCalculator = new InvoiceCalculator($invoice);

                // Utiliser les méthodes de InvoiceCalculator pour calculer les montants
                $totalPaid = $invoice->payments->reduce(function ($carry, $payment) {
                    return $carry + $payment->amount;
                }, 0);

                // Récupérer le montant total, la TVA, et le montant dû via le calculateur
                $totalPrice = $invoiceCalculator->getTotalPrice()->getAmount();
                $vatTotal = $invoiceCalculator->getVatTotal()->getAmount();
                $subTotal = $invoiceCalculator->getSubTotal()->getAmount();
                $amountDue = $invoiceCalculator->getAmountDue()->getAmount();

                // Formater et retourner les données pour chaque facture
                return [
                    'id' => $invoice->id,
                    'status' => $invoice->status,
                    'sent_at' => Carbon::parse($invoice->sent_at)->format('d/m/Y H:i'),
                    'due_at' => Carbon::parse($invoice->due_at)->format('d/m/Y H:i'),
                    'client' => $invoice->client->company_name ?? 'N/A',
                    'source' => $invoice->source->title ?? 'N/A',
                    'total_paid' => strval($totalPaid),
                    'total_price' => strval($totalPrice),
                    'vat_total' => strval($vatTotal),
                    'sub_total' => strval($subTotal),
                    'amount_due' => strval($amountDue),
                ];
            });
            return $invoices;
        } catch (\Exception $e) {
            throw new \Exception('Erreur lors de la récupération des factures : ' . $e->getMessage());
        }
    }
}
