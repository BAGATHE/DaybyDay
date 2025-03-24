<?php

namespace App\Services\charts;

use App\Models\InvoiceLine;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class ChartService
{
    public function getPaymentBySource()
    {
        $payments = Payment::select("payment_source")
            ->whereIn("payment_source", ["bank", "cash", "expenses"])
            ->groupBy("payment_source")
            ->selectRaw("payment_source, SUM(amount) as total_amount")
            ->get()
            ->keyBy("payment_source");

        return [$payments->get('bank', collect(['total_amount' => 0])),
                $payments->get('cash', collect(['total_amount' => 0])),
                $payments->get('expenses', collect(['total_amount' => 0])),
        ];
    }

    public function getPayementAndInvoiceLine($year)
    {
        // Requête pour les paiements
        $payments = Payment::select(
            DB::raw('SUM(amount) as payment_amount'),
            DB::raw('MONTH(payment_date) as mois'),
            DB::raw('YEAR(payment_date) as annee')
        )
            ->whereYear('payment_date', $year)  // Ajouter la condition WHERE pour l'année
            ->groupBy(DB::raw('YEAR(payment_date)'), DB::raw('MONTH(payment_date)'))
            ->orderBy(DB::raw('YEAR(payment_date)'))
            ->orderBy(DB::raw('MONTH(payment_date)'))
            ->get();

        // Requête pour les lignes de facture
        $invoiceLines = InvoiceLine::select(
            DB::raw('SUM(price * quantity) as invoice_amount'),
            DB::raw('MONTH(created_at) as mois'),
            DB::raw('YEAR(created_at) as annee')
        )
            ->whereNotNull('invoice_id')
            ->whereNull('deleted_at')
            ->whereYear('created_at', $year)  // Ajouter la condition WHERE pour l'année
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('YEAR(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();

        // Fusionner les paiements et les lignes de factures par mois et année
        $mergedData = [];

        // Fusionner les données des paiements dans le tableau fusionné
        foreach ($payments as $payment) {
            $month = $payment->mois;
            $year = $payment->annee;

            // Ajouter ou mettre à jour les données pour ce mois/année
            $mergedData["$year-$month"] = [
                'payment_amount' => (float)$payment->payment_amount,  // Assurez-vous que c'est un nombre
                'invoice_amount' => 0.0,  // Valeur par défaut pour les factures
                'mois' => (int)$month,  // Mois en tant qu'entier
                'annee' => (int)$year   // Année en tant qu'entier
            ];
        }

        // Ajouter les lignes de facture au tableau fusionné
        foreach ($invoiceLines as $invoiceLine) {
            $month = $invoiceLine->mois;
            $year = $invoiceLine->annee;

            // Si des paiements existent déjà pour ce mois/année, les conserver
            if (isset($mergedData["$year-$month"])) {
                $mergedData["$year-$month"]['invoice_amount'] = (float)$invoiceLine->invoice_amount;  // Assurez-vous que c'est un nombre
            } else {
                // Sinon, ajouter une nouvelle entrée avec la valeur d'invoice_amount
                $mergedData["$year-$month"] = [
                    'payment_amount' => 0.0,  // Valeur par défaut pour les paiements
                    'invoice_amount' => (float)$invoiceLine->invoice_amount,  // Assurez-vous que c'est un nombre
                    'mois' => (int)$month,  // Mois en tant qu'entier
                    'annee' => (int)$year   // Année en tant qu'entier
                ];
            }
        }

        // Reconvertir le tableau fusionné en un tableau de résultats
        $finalData = array_values($mergedData);

        return $finalData;

    }






}