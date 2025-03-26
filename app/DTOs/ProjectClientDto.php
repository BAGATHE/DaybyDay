<?php

namespace App\DTOs;

class ProjectClientDto
{
    private  $project_title;
    private  $client_name;

    public function __construct(string $project_title, string $client_name)
    {
        $this->setProjectTitle($project_title);
        $this->setClientName($client_name);
    }

    public function setProjectTitle(string $project_title): void
    {
        if (empty($project_title)) {
            throw new \InvalidArgumentException("Le titre du projet ne peut pas être vide.");
        }
        $this->project_title = $project_title;
    }

    public function setClientName(string $client_name): void
    {
        if (empty($client_name)) {
            throw new \InvalidArgumentException("Le nom du client ne peut pas être vide.");
        }
        $this->client_name = $client_name;
    }

    public function getProjectTitle(): string
    {
        return $this->project_title;
    }

    public function getClientName(): string
    {
        return $this->client_name;
    }
}
