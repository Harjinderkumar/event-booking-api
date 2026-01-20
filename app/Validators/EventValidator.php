<?php

namespace App\Validators;

class EventValidator {
  public static function validate($data) {
    $errors = [];

    if (empty($data['name'])) {
      $errors['name'] = 'Name is required';
    }

    if (empty($data['event_date']) || !strtotime($data['event_date'])) {
      $errors['event_date'] = 'Invalid Event time';
    }

    if ($data['capacity'] <= 0) {
      $errors['capacity'] = 'Invalid capacity';
    }

    if (empty($data['country_code'])) {
      $errors['country_code'] = 'Country code is required';
    }

    if (!empty($errors)) {
      http_response_code(422);
      echo json_encode(['success' => false, 'errors' => $errors]);
      exit;
    }

    return [
      'name'       => trim($data['name']),
      'description' => trim($data['description'] ?? ''),
      'event_date'  => $data['event_date'],
      'country_code'    => trim($data['country_code'] ?? ''),
      'capacity'    => isset($data['capacity']) ? (int)$data['capacity'] : null,
    ];
  }
}     