<?php

use PHPUnit\Framework\TestCase;
use App\Models\Database;

abstract class ApiTestCase extends TestCase
{
  protected string $baseUrl = 'http://localhost:8080';

  protected function setUp(): void
  {
    parent::setUp();

    $pdo = Database::connect();
    $pdo->beginTransaction();
  }

  protected function request( string $method, string $uri, array $data = [], array $headers = [], array $query = [])
  {
    $_GET = $query;
    $_SERVER['REQUEST_METHOD'] = strtoupper($method);
    $_SERVER['REQUEST_URI']    = $uri;
    $_SERVER['CONTENT_TYPE']   = 'application/json';

    foreach ($headers as $key => $value) {
      $_SERVER['HTTP_' . strtoupper(str_replace('-', '_', $key))] = $value;
    }
    
    $input = json_encode($data);
    $this->setJsonInput($input);
    // file_put_contents('php://input', $input);

    ob_start();
    require __DIR__ . '/../../public/index.php';
    $response = ob_get_clean();

    return json_decode($response, true);
  }

  protected function setJsonInput(string $json): void
  {
      $stream = fopen('php://memory', 'r+');
      fwrite($stream, $json);
      rewind($stream);

      // Override php://input
      $GLOBALS['__INPUT_STREAM__'] = $stream;
  }

  protected function tearDown(): void
  {
    Database::connect()->rollBack();
    parent::tearDown();
  }
}
