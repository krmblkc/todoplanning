<?php

namespace App\Command;

use App\Service\Provider\TaskProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchTasksCommand extends Command
{
    protected static $defaultName = 'app:fetch-tasks';

    private $providers;
    private $entityManager;

    public function __construct(array $providers, EntityManagerInterface $entityManager)
    {
        $this->providers = $providers;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Fetches tasks from providers and saves them into the database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->providers as $provider) {
            $tasks = $this->fetchAndConvertTasks($provider);
            $this->saveTasks($tasks);
        }

        return 0;
    }

    private function fetchAndConvertTasks(TaskProviderInterface $provider): array
    {
        $data = $provider->fetchTasks();
        return $provider->convertToTasks($data);
    }

    private function saveTasks(array $tasks): void
    {
        foreach ($tasks as $task) {
            $this->entityManager->persist($task);
        }

        $this->entityManager->flush();
    }
}
