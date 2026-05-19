<?php

namespace Src\ClientManager\Infrastructure\Repository;

use Src\ClientManager\Domain\Model\Aggregates\Client;

class ClientRepository
{
    public function transformRandomClientsToClientModel(array $randomUsers): array
    {
        return array_map(fn (array $randomUser): Client => Client::fromRandomUser($randomUser),$randomUsers);
    }
}
