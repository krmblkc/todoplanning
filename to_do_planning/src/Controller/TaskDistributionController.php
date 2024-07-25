<?php

namespace App\Controller;

use App\Service\TaskDistributor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskDistributionController extends AbstractController
{
    private $taskDistributor;

    public function __construct(TaskDistributor $taskDistributor)
    {
        $this->taskDistributor = $taskDistributor;
    }

    /**
     * @Route("/distribute-tasks", name="distribute_tasks")
     */
    public function distributeTasks(): Response
    {
        [$schedule, $weeks] = $this->taskDistributor->scheduleJobs();

        return $this->render('task_distribution/index.html.twig', [
            'schedule' => $schedule,
            'weeks' => $weeks
        ]);
    }
}
