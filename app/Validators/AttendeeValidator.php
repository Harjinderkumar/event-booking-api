<?php

namespace App\Validators;

use App\Models\Attendee;

class AttendeeValidator {
  public static function validate($data) {
    $errors = [];

    if (empty($data['name'])) {
      $errors['name'] = 'Name is required';
    }

    if (empty($data['email'])) {
      $errors['email'] = 'Email is required';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = "Invalid email format";
    } elseif (Attendee::checkDuplicateEmail($data['email'], $data['user_id'], $data['id'] ?? 0)) {
      $errors['email'] = "Email already exist for this user";
    }

    if (!empty($errors)) {
      http_response_code(422);
      echo json_encode(['success' => false, 'errors' => $errors]);
      exit;
    }

    return [
      'name'   => trim($data['name']),
      'email'  => $data['email'],
      'user_id'  => $data['user_id'],
    ];
  }
}     