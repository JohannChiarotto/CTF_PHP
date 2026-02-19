<?php
// core/Router.php

class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, callable $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $method, string $uriPath): void
    {
        $method = strtoupper($method);
        $path = parse_url($uriPath, PHP_URL_PATH);

        $route = $path === '' ? '/' : $path;

        if (isset($this->routes[$method][$route])) {
            call_user_func($this->routes[$method][$route]);
            return;
        }

        http_response_code(404);
        echo '404 Not Found';
    }
}

