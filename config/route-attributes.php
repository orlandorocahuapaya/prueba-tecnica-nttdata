<?php

return [
    'enabled' => true,

    'directories' => [
        base_path('src/ClientManager/Interfaces/Rest') => [
            'namespace' => 'Src',
            'base_path' => base_path('src'),
            'middleware' => ['api'],
            'patterns' => ['*Controller.php'],
            'not_patterns' => [],
        ],
    ],

    'middleware' => [
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],

    'scope-bindings' => null,
];
