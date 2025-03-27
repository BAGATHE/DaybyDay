<?php

namespace App\DTOs;

use App\Models\Contact;
use App\Models\Client;
use App\Services\generator\Generator;
use Ramsey\Uuid\Uuid;


class ContactDto
{
    public $external_id;
    public $name;
    public $email;
    public $primary_number;
    public $secondary_number;
    public $is_primary;
    public $client_id;

    /**
     * Constructeur pour initialiser les données du contact.
     *
     * @param string $name
     * @param string $email
     * @param int $client_id
     */
    public function __construct($name, $email, $client_id) {
        $this->external_id = Uuid::uuid4()->toString();
        $this->name = $name;
        $this->email = 'copy'.$email;
        $this->primary_number = Generator::generateClientNumber();
        $this->secondary_number = Generator::generateClientNumber();
        $this->is_primary = 1;
        $this->client_id = $client_id;
    }

    /**
     * Fonction pour générer un contact lié à un client.
     *
     * @param id
     * @return Contact
     */
    public function generateContactClient($id)
    {
        return Contact::create([
            'external_id' => $this->external_id,
            'name' => $this->name,
            'email' => $this->email,
            'primary_number' => $this->primary_number,
            'secondary_number' => $this->secondary_number,
            'is_primary' => $this->is_primary,
            'client_id' => $id,
        ]);
    }
}
