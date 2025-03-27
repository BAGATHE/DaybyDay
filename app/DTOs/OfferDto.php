<?php

namespace App\DTOs;

use App\Models\Offer;
use Ramsey\Uuid\Uuid;

class OfferDto
{
    public $sent_at;
    public $source_type;
    public $source_id;
    public $client_id;
    public $status;

    // Constructeur pour initialiser les variables
    public function __construct($sent_at, $source_type, $source_id, $client_id, $status)
    {
        $this->sent_at = $sent_at;
        $this->source_type = $source_type;
        $this->source_id = $source_id;
        $this->client_id = $client_id;
        $this->status = $status;
    }

    // Fonction pour insérer une offre dans la table "offers"
    public function generateOffer()
    {
        return Offer::create([
            'external_id' => Uuid::uuid4()->toString(),
            'sent_at' => $this->sent_at,
            'source_type' => $this->source_type,
            'source_id' => $this->source_id,
            'client_id' => $this->client_id,
            'status' => $this->status,
        ]);
    }
}
