<?php

namespace App\Service\Provider;

use App\Entity\Task;

interface TaskProviderInterface
{
    public function fetchTasks(): array;
    public function convertToTasks(array $data): array;
}