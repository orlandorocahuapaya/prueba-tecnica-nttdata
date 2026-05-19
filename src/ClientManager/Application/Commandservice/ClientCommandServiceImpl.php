<?php

namespace Src\ClientManager\Application\Commandservice;

use Illuminate\Support\Facades\Http;
use Src\ClientManager\Infrastructure\Repository\ClientRepository;

class ClientCommandServiceImpl
{
    public function __construct(private ClientRepository $repository)
    {
    }

    public function getRandomClients(int $results = 10): array
    {
        $response = Http::timeout(10)
            ->get('https://randomuser.me/api/', ['results' => $results])
            ->throw()
            ->json();

        $randomUsers = $response['results'];

        return $this->repository->transformRandomClientsToClientModel($randomUsers);
    }
}
