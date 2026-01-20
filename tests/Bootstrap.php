<?php

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Ensure we are in testing mode
 */
define('APP_ENV', 'testing');

/**
 * Optional: Start output buffering
 */
ob_start();

if (!function_exists('getallheaders')) {
  function getallheaders() {
    $headers = [];

    foreach ($_SERVER as $name => $value) {
      if (str_starts_with($name, 'HTTP_')) {
        $key = str_replace(
          ' ',
          '-',
          ucwords(strtolower(str_replace('_', ' ', substr($name, 5))))
        );
        $headers[$key] = $value;
      }
    }

    return $headers;
  }
}