<?php

namespace App\Services\Invoice;

use App\Enums\InvoiceStatus;
use App\Enums\OfferStatus;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Utils\ResponseUtil;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

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


    public function generateInvoiceLinesForOffers($id_offre,$id_product,$id_lead,$price,$quantity){
        $invoice_line  = InvoiceLine::create([
           'external_id' => Uuid::uuid4()->toString(),
            'type'=>'days',
            'quantity'=>$quantity,
            'title'=>'offre du lead'.$id_lead.' pas encore valider',
            'comment'=>'',
            'price'=> $price,
            'invoice_id'=>null,
            'product_id'=>$id_product,
            'offer_id'=>$id_offre,
        ]);
        return $invoice_line;
    }

    public function generateInvoiceLinesForInvoices($id_invoice,$id_product,$id_lead,$price,$quantity){
        $invoice_line  = InvoiceLine::create([
            'external_id' => Uuid::uuid4()->toString(),
            'type'=>'days',
            'quantity'=>$quantity,
            'title'=>'offre du lead'.$id_lead.' valider',
            'comment'=>'',
            'price'=> $price,
            'invoice_id'=>$id_invoice,
            'product_id'=>$id_product,
            'offer_id'=>null,
        ]);
        return $invoice_line;
    }

    public function generateInvoice($id_client,$id_lead,$id_offers){
        $sentAt = date('Y-m-d', time());
        $invoice = Invoice::create([
            'status' => InvoiceStatus::unpaid(),
            'client_id'=>$id_client,
            'sent_at'=>$sentAt, 
            'due_at'=>'', //date tokony nanefako azy
            'source_id' =>$id_lead,
            'external_id' => Uuid::uuid4()->toString(),
            'offer_id' => $id_offers,
            'remise'=>0,
            'source_type'=>'App\Models\Lead',
        ]);
        return $invoice;
    }
}
