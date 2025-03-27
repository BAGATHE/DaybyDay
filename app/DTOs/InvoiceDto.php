<?php

namespace App\DTOs;

use App\Models\Invoice;
use Ramsey\Uuid\Uuid;

class InvoiceDto
{
    public $status;
    public $remise;
    public $invoice_number;
    public $sent_at;
    public $due_at;
    public $integration_invoice_id;
    public $integration_type;
    public $source_type;
    public $source_id;
    public $client_id;
    public $offer_id;

    // Constructeur pour initialiser les variables
    public function __construct(
        $status,
        $remise,
        $invoice_number,
        $sent_at,
        $due_at,
        $integration_invoice_id,
        $integration_type,
        $source_type,
        $source_id,
        $client_id,
        $offer_id
    ) {
        $this->status = $status;
        $this->remise = $remise;
        $this->invoice_number = $invoice_number;
        $this->sent_at = $sent_at;
        $this->due_at = $due_at;
        $this->integration_invoice_id = $integration_invoice_id;
        $this->integration_type = $integration_type;
        $this->source_type = $source_type;
        $this->source_id = $source_id;
        $this->client_id = $client_id;
        $this->offer_id = $offer_id;
    }

    public function generateInvoice()
    {
        return Invoice::create([
            'external_id' => Uuid::uuid4()->toString(),  // Génération d'un UUID unique
            'status' => $this->status,
            'remise' => $this->remise,
            'invoice_number' => $this->invoice_number,
            'sent_at' => $this->sent_at,
            'due_at' => $this->due_at,
            'integration_invoice_id' => $this->integration_invoice_id,
            'integration_type' => $this->integration_type,
            'source_type' => $this->source_type,
            'source_id' => $this->source_id,
            'client_id' => $this->client_id,
            'offer_id' => $this->offer_id,
        ]);
    }
}
