<?php

namespace App\DTOs;

class ProjectTaskDto
{
    private  $project_title;
    private  $task_title;

    public function __construct($project_title, $task_title){
        $this->setProjectTitle($project_title);
        $this->setTaskTitle($task_title);
    }

    public function setProjectTitle(string $project_title): void
    {
        if (empty($project_title)) {
            throw new \InvalidArgumentException("Le titre du projet ne peut pas être vide.");
        }
        $this->project_title = $project_title;
    }

    public function getProjectTitle(): string
    {
        return $this->project_title;
    }

    public function setTaskTitle(string $task_title): void{
        if (empty($task_title)) {
            throw new \InvalidArgumentException("Le task  ne peut pas être vide.");
        }
        $this->task_title = $task_title;
    }
    public function getTaskTitle(): string{
        return $this->task_title;
    }

}