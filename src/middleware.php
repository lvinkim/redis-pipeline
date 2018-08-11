<?php
// Application middleware

if (getenv('ENV') == 'prod') {
    $app->add(new \Tuupola\Middleware\HttpBasicAuthentication([
        "secure" => false,
        "users" => [
            'admin' => getenv('API_PASSWORD'),
        ],
        "error" => function (\Slim\Http\Response $response, $arguments) {
            return $response->withStatus(401)
                ->withHeader('Content-Type', 'application/json;charset=utf-8')
                ->write(json_encode([
                    'success' => false,
                    'message' => '401 Unauthorized',
                    'data' => []
                ]));
        }
    ]));
}