<?php

namespace App\Services\Lead;

use App\Http\Controllers\LeadsController;
use App\Models\Lead;
use App\Services\generator\Generator;
use Ramsey\Uuid\Uuid;

class LeadService
{
public function findOrCreateLead(string $leadName,$client_id): Lead
{

$lead = Lead::where('title','like', '%'.$leadName.'%')->first();
if ($lead) {
    return $lead;
}


    $lead = Lead::create(
        [
            'title' => $leadName,
            'description' => $leadName,
            'user_assigned_id' => auth()->id(),
            'deadline' => Generator::generateRandomDate(),
            'status_id' => 7, //Open
            'user_created_id' => auth()->id(),
            'external_id' => Uuid::uuid4()->toString(),
            'client_id' => $client_id
        ]
    );
    event(new \App\Events\LeadAction($lead, LeadsController::CREATED));
    return $lead;
}

}