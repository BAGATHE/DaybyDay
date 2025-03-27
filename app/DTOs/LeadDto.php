<?php

namespace App\DTOs;

use App\Models\Lead;
use Ramsey\Uuid\Uuid;

class LeadDto
{
    public $title;
    public $description;
    public $status_id;
    public $user_assigned_id;
    public $client_id;
    public $user_created_id;
    public $qualified;
    public $result;
    public $deadline;

    // Constructeur pour initialiser les variables
    public function __construct($title, $description, $status_id, $user_assigned_id, $client_id, $user_created_id, $qualified, $result, $deadline)
    {
        $this->title = 'COPY '.$title;
        $this->description = 'COPY '.$description;
        $this->status_id = $status_id;
        $this->user_assigned_id = $user_assigned_id;
        $this->client_id = $client_id;
        $this->user_created_id = $user_created_id;
        $this->qualified = $qualified;
        $this->result = $result;
        $this->deadline = $deadline;
    }

    public function generateLead()
    {
        // Crée un lead avec les données de l'objet
        return Lead::create([
            'external_id' => Uuid::uuid4()->toString(),
            'title' => $this->title,
            'description' => $this->description,
            'status_id' => $this->status_id,
            'user_assigned_id' => $this->user_assigned_id,
            'client_id' => $this->client_id,
            'user_created_id' => $this->user_created_id,
            'qualified' => $this->qualified,
            'result' => $this->result,
            'deadline' => $this->deadline,
        ]);
    }
}
