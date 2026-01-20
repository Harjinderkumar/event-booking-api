<?php

namespace App\Helpers;

class JWT
{
    private static string $secret = 'EVENT_BOOKING_API_SECRET_KEY';
    private static string $algo = 'HS256';

    public static function encode(array $payload, int $expirySeconds = 3600)
    {
      $header = ['typ' => 'JWT', 'alg' => self::$algo];

      $payload['iat'] = time();
      $payload['exp'] = time() + $expirySeconds;

      $base64Header = self::base64UrlEncode(json_encode($header));
      $base64Payload = self::base64UrlEncode(json_encode($payload));

      $signature = hash_hmac(
        'sha256',
        "$base64Header.$base64Payload",
        self::$secret,
        true
      );

      return "$base64Header.$base64Payload." . self::base64UrlEncode($signature);
    }

    public static function decode(string $token)
    {
      [$header, $payload, $signature] = explode('.', $token);

      $expected = self::base64UrlEncode(
        hash_hmac('sha256', "$header.$payload", self::$secret, true)
      );

      if (!hash_equals($expected, $signature)) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Invalid token']);
        exit;
      }

      $data = json_decode(self::base64UrlDecode($payload), true);

      if ($data['exp'] < time()) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Token expired']);
        exit;
      }

      return $data;
    }

    private static function base64UrlEncode(string $data)
    {
      return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64UrlDecode(string $data): string
    {
      return base64_decode(strtr($data, '-_', '+/'));
    }
}