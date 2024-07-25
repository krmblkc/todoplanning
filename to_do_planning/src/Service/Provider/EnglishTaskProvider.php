<?php

namespace App\Service\Provider;

use GuzzleHttp\Client;
use App\Entity\Task;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class EnglishTaskProvider implements TaskProviderInterface
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function fetchTasks(): array
    {
        $apiUrl = "https://raw.githubusercontent.com/WEG-Technology/mock/main/mock-one";

        $response = $this->client->request('GET',$apiUrl);

        //dump($response->getContent());die();

        $content = $response->getContent();
        $data = json_decode($content, true);

        return $data;
    }

    public function convertToTasks(array $data): array
    {
        $tasks = [];
        foreach ($data as $taskData) {
            $task = new Task();
            $task->setDuration($taskData['estimated_duration']);
            $task->setDifficulty($taskData['value']);
            $tasks[] = $task;
        }

        return $tasks;
    }
}
