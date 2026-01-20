<?php

namespace App\Models;

use PDO;
use PDOException;

class Database
{
  private static ?PDO $connection = null;

  public static function connect()
  {
    if (self::$connection === null) {
      $config = require __DIR__ . '/../../config/database.php';;
      try {
        self::$connection = new PDO(
          "mysql:host={$config['Server']};dbname={$config['dbname']};port={$config['port']};",
          $config['user'],
          $config['pass'],
          [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
          ]
        );
      } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database connection failed']);
        exit;
      }
    }

    return self::$connection;
  }

  public static function begin()
  {
    if (defined('APP_ENV') && APP_ENV === 'testing') {
      return;
    }
    if (!self::connect()->inTransaction()) {
      self::connect()->beginTransaction();
    }
  }

  public static function commit()
  {
    if (defined('APP_ENV') && APP_ENV === 'testing') {
      return;
    }
    if (self::connect()->inTransaction()) {
      self::connect()->commit();
    }
  }

  public static function rollback()
  {
    if (defined('APP_ENV') && APP_ENV === 'testing') {
      return;
    }
    if (self::connect()->inTransaction()) {
      self::connect()->rollBack();
    }
  }
}
