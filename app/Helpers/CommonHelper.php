<?php

namespace App\Helpers;

class CommonHelper
{
  public static function getJsonInput()
  {
    if (isset($GLOBALS['__INPUT_STREAM__'])) {
      $raw = stream_get_contents($GLOBALS['__INPUT_STREAM__']);
    } else {
      $raw = file_get_contents('php://input');
    }

    return json_decode($raw, true) ?? [];
  }
}