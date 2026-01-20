<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Routing\Router;
use App\Models\Database;

try {
    Database::begin();
    header('Content-Type: application/json');
    $router = new Router();
    require __DIR__ . '/../routes/api.php';
    $router->dispatch();
    Database::commit();
} catch (Throwable $e) {
    Database::rollback();
    http_response_code(500);
    echo json_encode(['success' => 0, 'message' => $e->getMessage()]);
    exit;
}

