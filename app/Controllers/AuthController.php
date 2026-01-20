<?php

namespace App\Controllers;
use App\Models\Database;
use App\Helpers\JWT;

class AuthController
{
  public function login()
  {
    $input = json_decode(file_get_contents('php://input'), true);

    if (empty($input['email']) || empty($input['password'])) {
      return Response::json(['message' => 'Invalid credentials'], 422);
    }

    $db = Database::connect();

    $stmt = $db->prepare("SELECT id, password FROM users WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $input['email']]);

    $user = $stmt->fetch();

    if (!$user || !password_verify($input['password'], $user['password'])) {
      http_response_code(401);
      echo json_encode(['success' => false, 'message' => 'Unauthorized']);
      return;
    }

    $token = JWT::encode([
      'user_id' => $user['id']
    ]);

    http_response_code(201);
    echo json_encode([
      'success' => true, 
      'token' => $token,
      'token_type' => 'Bearer',
      'expires_in' => 3600
    ]);
  }
}