<?php

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Validators\AttendeeValidator;
use App\Models\Attendee;
use App\Models\Booking;
use App\Helpers\CommonHelper;

class AttendeeController
{
  public function store($auth_required = true)
  {
    if($auth_required) {
      $userId = AuthMiddleware::handle();
    } else {
      $userId = 1; // we update user id on event booking
    }

    $input = CommonHelper::getJsonInput();

    if (!$input) {
      http_response_code(400);
      echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
      return;
    }

    $input['user_id'] = $userId;
    $data = AttendeeValidator::validate($input);

    $attendeeId = Attendee::create($data);

    http_response_code(201);
    echo json_encode([
      'success' => true, 
      'message' => 'Attendee created successfully',
      'attendee_id' => $attendeeId
    ]);
  }

  public function index()
  {
    $userId = AuthMiddleware::handle();
    $_GET['user_id'] = $userId;
    $attendees = Attendee::list($_GET);

    echo json_encode(['success' => true, 'data' => $attendees]);
  }

  public function update()
  {
    $id = $_GET['id'] ?? null;
    if(!empty($id)) {
      $userId = AuthMiddleware::handle();

      $input = CommonHelper::getJsonInput();
      if (!$input) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
        return;
      }

      $attendee = Attendee::findAttendee($id, $userId);
      if (!$attendee) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Attendee not found']);
        exit;
      }
      $input['id'] = $id;
      $input['user_id'] = $userId;
      $data = AttendeeValidator::validate($input);

      $attendeeId = Attendee::update($id, $data);

      http_response_code(201);
      echo json_encode([
        'success' => true, 
        'message' => 'Attendee updated successfully',
      ]);
    } else {
      http_response_code(500);
      echo json_encode(['success' => false, 'message' => 'Must be pass Attendee id']);
      exit;
    }
  }

  public function delete() {
    $id = $_GET['id'] ?? null;
    if(!empty($id)) {
      $userId = AuthMiddleware::handle();
      $attendee = Attendee::findAttendee($id, $userId);
      if (!$attendee) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Attendee not found']);
        exit;
      }

      $booking = Booking::findBooking(['attendee_id' => $id]);
      if ($booking) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Delete not allowed because attendee have booking.']);
        exit;
      }

      Attendee::delete($id);

      echo json_encode(['success' => true, 'message' => 'Attendee deleted successfully']);
    } else {
      http_response_code(500);
      echo json_encode(['success' => false, 'message' => 'Must be pass Attendee id']);
      exit;
    }
  }

  public function attendeeRegsiter() 
  {
    $auth_required = false;
    self::store($auth_required);
  }
}