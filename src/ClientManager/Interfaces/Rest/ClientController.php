<?php

namespace Src\ClientManager\Interfaces\Rest;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Src\ClientManager\Application\Commandservice\ClientCommandServiceImpl;

class ClientController extends Controller
{
    public function __construct(private ClientCommandServiceImpl $commandService)
    {
    }

    #[Get('api/client/random_clients')]
    public function randomClients(Request $request): JsonResponse
    {
        $results = (int) $request->query('results', 10);

        $clients = $this->commandService->getRandomClients($results);

        return response()->json($clients, Response::HTTP_OK);
    }
}
