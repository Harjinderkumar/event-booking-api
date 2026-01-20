<?php

namespace App\Routing;

class Router
{
  private array $routes = [];

  public function get(string $uri, array $action): void
  {
    $this->addRoute('GET', $uri, $action);
  }

  public function post(string $uri, array $action): void
  {
    $this->addRoute('POST', $uri, $action);
  }

  public function put(string $uri, array $action): void
  {
    $this->addRoute('PUT', $uri, $action);
  }

  public function delete(string $uri, array $action): void
  {
    $this->addRoute('DELETE', $uri, $action);
  }

  private function addRoute(string $method, string $uri, array $action): void
  {
    $this->routes[$method][$uri] = $action;
  }

  public function dispatch(): void
  {
    $method = $_SERVER['REQUEST_METHOD'];
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  
    // For Remove index.php/api prefix if needed
    // OR For Remove index.php/ prefix if needed
    $uri = str_replace('index.php/api/', '', $uri);
    $uri = str_replace('index.php/', '', $uri);

    if (!isset($this->routes[$method][$uri])) {
        http_response_code(404);
        echo json_encode(['success' => 0, 'message' => 'Route not found']);
        return;
    }

    [$controller, $methodName] = $this->routes[$method][$uri];

    $controllerInstance = new $controller();
    $controllerInstance->$methodName();
  }
}