<?php

namespace App\Services\Offer;

use App\Enums\OfferStatus;
use App\Models\Offer;
use Ramsey\Uuid\Uuid;

class OfferService
{
    public function generateOffre($id_lead,$id_client,$status){
        $offer = Offer::create([
            'status'=>$status,
            'client_id' =>$id_client,
            'source_id' => $id_lead,
            'source_type' => 'App\Models\Lead',
            'external_id' => Uuid::uuid4()->toString(),
        ]);
        return $offer;
    }

}