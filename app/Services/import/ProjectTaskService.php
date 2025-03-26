<?php

namespace App\Services\import;

use App\DTOs\ProjectTaskDto;
use App\Http\Controllers\TasksController;
use App\Models\Project;
use App\Models\Task;
use App\Services\generator\Generator;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Exception;
use Ramsey\Uuid\Uuid;

class ProjectTaskService
{
    private  $errors = [];

    public function importProjectTask(string $filePath, string $fileName)
    {
        $data =array();
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
                $dto = new ProjectTaskDto($row[0] ?? '', $row[1] ?? '');
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
            'data' =>$data,
        ];
    }

    private function logErrors(string $fileName)
    {
        Log::error("Erreurs lors de l'importation du fichier : $fileName", $this->errors);
    }

    public function generateTaskProject($projectTaskDTOs)
    {
        foreach ($projectTaskDTOs as $projectTaskDTO) {
            $project = Project::where("title", $projectTaskDTO->getProjectTitle())->first();

            if (!$project) {
                throw new \RuntimeException("Le projet '{$projectTaskDTO->getProjectTitle()}' n'existe pas.");
            }

            $task = Task::create([
                'title' => $projectTaskDTO->getTaskTitle(),
                'description' => $projectTaskDTO->getTaskTitle(),
                'user_assigned_id' => auth()->id(),
                'deadline' => Generator::generateRandomDate(),
                'status_id' => 11, // Status Open
                'user_created_id' => auth()->id(),
                'external_id' => Uuid::uuid4()->toString(),
                'client_id' => $project->client_id,
                'project_id' => $project->id
            ]);

            event(new \App\Events\TaskAction($task, TasksController::CREATED));
        }
    }

}