<?php

namespace App\Services\client;

use App\Models\Client;
use App\Models\Contact;
use App\Models\Industry;
use App\Services\generator\Generator;
use Ramsey\Uuid\Uuid;

class ClientService
{
    public function findOrCreateClient(string $clientName): Client
    {
        // Vérifie si le client existe déjà (insensible à la casse)
        $client = Client::where('company_name', 'like', '%'.$clientName.'%')->first();
        if ($client) {
            return $client;
        }

        // Création d'un nouveau client avec valeurs par défaut
        $client = Client::create([
            'external_id' => Uuid::uuid4()->toString(),
            'adress' =>' no adress ',
            'zip_code' =>'101',
            'city' => 'tana',
            'company_name' => $clientName,
            'vat' => Generator::generateRandomVat(),
            'client_number' => Generator::generateClientNumber(),
            'user_id' => auth()->id() ?? 1,
            'company_type' =>'SARL',
            'industry_id' =>Industry::inRandomOrder()->first()->id,
        ]);

        $contact = Contact::create([
            'external_id' => Uuid::uuid4()->toString(),
            'name' => $client->company_name,
            'email' => Generator::generateClientEmail($client->company_name),
            'primary_number' => Generator::generateClientNumber(),
            'secondary_number' => Generator::generateClientNumber(),
            'is_primary'=>1,
            'client_id' => $client->id
        ]);
        return $client;
    }




}