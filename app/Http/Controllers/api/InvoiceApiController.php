<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\Invoice\InvoiceService;
use App\Utils\ResponseUtil;
use Carbon\Carbon;

class InvoiceApiController extends Controller
{
    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function index()
    {
        try {
            $invoices = $this->invoiceService->getInvoices();  // Appel au service pour récupérer les factures
            return ResponseUtil::responseStandard('success', ["invoices" => $invoices]);
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
