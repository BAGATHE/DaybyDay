<?php

namespace App\Services\import;

use App\DTOs\ProjectClientDto;
use App\Http\Controllers\ProjectsController;
use App\Models\Project;
use App\Services\client\ClientService;
use App\Services\generator\Generator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;


class ProjectClientService
{
    protected $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    private $errors = [];

    public function importProjectClient(string $filePath, string $fileName)
    {
        $data = array();
        if (!file_exists($filePath)) {
            throw new \Exception("Le fichier '$filePath' est introuvable.");
        }

        $file = fopen($filePath, 'r');
        $lineNumber = 0;

        // Ignorer la première ligne (en-tête)
        fgetcsv($file);

        while (($row = fgetcsv($file, 1000, ',')) !== false) {
            $lineNumber++;

            try {
                // Création du DTO avec validation intégrée
                $dto = new ProjectClientDto($row[0] ?? '', $row[1] ?? '');
                if(Project::where('title',$row[0])->exists()){
                    $this->errors[] = "Fichier : $fileName | Ligne $lineNumber : le projet {$row[0]} existe déjà";
                }
                $data[] = $dto;


            } catch (\InvalidArgumentException $e) {
                // Stocker l'erreur avec le nom du fichier et la ligne concernée
                $this->errors[] = "Fichier : $fileName | Ligne $lineNumber : " . $e->getMessage();
            }
        }

        fclose($file);

        // Vérification finale des erreurs
        if (!empty($this->errors)) {
            $this->logErrors($fileName);
            return [
                'status' => 'error',
                'message' => 'Des erreurs ont été détectées.',
                'errors' => $this->errors
            ];
        }

        return [
            'status' => 'success',
            'message' => 'Importation réussie.',
            'data' => $data,
        ];
    }

    private function logErrors(string $fileName)
    {
        Log::error("Erreurs lors de l'importation du fichier : $fileName", $this->errors);
    }




    public function generateProjectAndClient($projectClientDTOs)
    {
            foreach ($projectClientDTOs as $projectClientDTO) {
                $client = $this->clientService->findOrCreateClient($projectClientDTO->getClientName());

                $project = Project::create([
                    'title' => $projectClientDTO->getProjectTitle(),
                    'description' => $projectClientDTO->getProjectTitle(),
                    'user_assigned_id' => auth()->id(),
                    'deadline' => Generator::generateRandomDate(),
                    'status_id' => 11, // Statut "Open"
                    'user_created_id' => auth()->id(),
                    'external_id' => Uuid::uuid4()->toString(),
                    'client_id' => $client ? $client->id : null,
                ]);

                event(new \App\Events\ProjectAction($project, ProjectsController::CREATED));
            }
    }

}