<?php

namespace App\DTOs;

use App\Models\Project;
use Ramsey\Uuid\Uuid;

class ProjectDto
{
    public $title;
    public $description;
    public $status_id;
    public $user_assigned_id;
    public $user_created_id;
    public $client_id;
    public $invoice_id;
    public $deadline;

    // Constructeur pour initialiser les variables
    public function __construct($title, $description, $status_id, $user_assigned_id, $user_created_id, $client_id, $invoice_id, $deadline)
    {
        $this->title = 'COPY '.$title;
        $this->description = 'COPY '.$description;
        $this->status_id = $status_id;
        $this->user_assigned_id = $user_assigned_id;
        $this->user_created_id = $user_created_id;
        $this->client_id = $client_id;
        $this->invoice_id = $invoice_id;
        $this->deadline = $deadline;
    }

    // Fonction pour insérer un projet dans la table "projects"
    public function generateProject()
    {
        return Project::create([
            'external_id' => Uuid::uuid4()->toString(),  // Génération d'un UUID unique
            'title' => $this->title,
            'description' => $this->description,
            'status_id' => $this->status_id,
            'user_assigned_id' => $this->user_assigned_id,
            'user_created_id' => $this->user_created_id,
            'client_id' => $this->client_id,
            'invoice_id' => $this->invoice_id,
            'deadline' => $this->deadline,
        ]);
    }
}
