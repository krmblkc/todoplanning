<?php

// src/Service/TaskDistributor.php
namespace App\Service;

use App\Entity\Task;
use App\Repository\DeveloperRepository;
use App\Repository\TaskRepository;

class TaskDistributor
{
    private $developerRepository;
    private $taskRepository;

    public function __construct(DeveloperRepository $developerRepository, TaskRepository $taskRepository)
    {
        $this->developerRepository = $developerRepository;
        $this->taskRepository = $taskRepository;
    }

    public function scheduleJobs(): array
    {
        $developers = $this->developerRepository->findAll();
        $tasks = $this->taskRepository->findBy([], ['difficulty' => 'DESC']);

        $schedule = [];
        $weeks = 0;
        $totalHours = array_reduce($tasks, fn($carry, $task) => $carry + ($task->getDuration() * $task->getDifficulty()), 0);

        while ($totalHours > 0) {
            foreach ($developers as $developer) {
                $hoursLeft = 45;
                foreach ($tasks as $task) {
                    $taskHours = $task->getDuration() * $task->getDifficulty();
                    if ($hoursLeft >= $taskHours) {
                        $hoursLeft -= $taskHours;
                        $totalHours -= $taskHours;
                        $schedule[$weeks][$developer->getName()][] = $task;
                    }
                }
            }
            $weeks++;
        }

        return [$schedule, $weeks];
}

}
