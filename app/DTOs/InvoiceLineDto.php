<?php

namespace App\DTOs;

use App\Models\InvoiceLine;
use Ramsey\Uuid\Uuid;

class InvoiceLineDto
{
    public $title;
    public $comment;
    public $price;
    public $invoice_id;
    public $offer_id;
    public $type;
    public $quantity;
    public $product_id;

    public function __construct($title, $comment, $price, $invoice_id, $offer_id, $type, $quantity, $product_id)
    {
        $this->title = $title;
        $this->comment = $comment;
        $this->price = $price;
        $this->invoice_id = $invoice_id;
        $this->offer_id = $offer_id;
        $this->type = $type;
        $this->quantity = $quantity;
        $this->product_id = $product_id;
    }

    public function generateInvoiceLine()
    {
        return InvoiceLine::create([
            'external_id' =>Uuid::uuid4()->toString(),
            'title' => $this->title,
            'comment' => $this->comment,
            'price' => $this->price,
            'invoice_id' => $this->invoice_id,
            'offer_id' => $this->offer_id,
            'type' => $this->type,
            'quantity' => $this->quantity,
            'product_id' => $this->product_id,
        ]);
    }
}
