<?php

namespace App\DTOs;

use App\Models\Client;
use Ramsey\Uuid\Uuid;

class ClientDto
{
    public $address;
    public $zipcode;
    public $city;
    public $company_name;
    public $vat;
    public $company_type;
    public $client_number;
    public $user_id;
    public $industry_id;

    /**
     * Constructeur pour initialiser les données avec des variables individuelles.
     *
     * @param string|null $address
     * @param string|null $zipcode
     * @param string $city
     * @param string $company_name
     * @param string $vat
     * @param string $company_type
     * @param int $client_number
     * @param int $user_id
     * @param int $industry_id
     */
    public function __construct(
        $address = null,
        $zipcode = null,
        $city,
        $company_name,
        $vat,
        $company_type,
        $client_number,
        $user_id,
        $industry_id
    ) {
        $this->address = $address;
        $this->zipcode = $zipcode;
        $this->city = $city;
        $this->setCompanyName($company_name);
        $this->vat = 'COPY'.$vat;
        $this->company_type = $company_type;
        $this->client_number = $client_number;
        $this->user_id = $user_id;
        $this->industry_id = $industry_id;
    }

    // Setter pour company_name (ajout de "COPY ")
    public function setCompanyName($companyName)
    {
        $this->company_name = "COPY " . $companyName;
    }

    public function generateClient()
    {
        return Client::create([
            'external_id' => Uuid::uuid4()->toString(),
            'address' => $this->address,
            'zipcode' => $this->zipcode,
            'city' => $this->city,
            'company_name' => $this->company_name,
            'vat' => $this->vat,
            'company_type' => $this->company_type,
            'client_number' => $this->client_number,
            'user_id' => $this->user_id,
            'industry_id' => $this->industry_id,
        ]);
    }
}
