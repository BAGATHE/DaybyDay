<?php

namespace App\Services\Dashboard;

use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Offer;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getDashboardData()
    {
        try {
            $totalInvoiceAmount = InvoiceLine::whereNotNull('invoice_id')->sum(DB::raw("price * quantity"));
            return [
                'totalOffers' => Offer::count(),
                'totalInvoices' => Invoice::count(),
                'totalInvoiceAmount' => $totalInvoiceAmount,
                'totalPayment' => Payment::sum('amount'),
            ];
        } catch (\Exception $e) {
            throw new \Exception('Erreur lors de la récupération des données : ' . $e->getMessage());
        }
    }

}