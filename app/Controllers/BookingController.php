<?php

namespace App\Controllers;
use App\Middleware\AuthMiddleware;
use App\Validators\BookingValidator;
use App\Models\Booking;
use App\Models\Event;
use App\Helpers\CommonHelper;

class BookingController
{
  public function store()
  {
    $userId = AuthMiddleware::handle();

    $input = CommonHelper::getJsonInput();

    if (!$input) {
      http_response_code(400);
      echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
      return;
    }
    
    $input['user_id'] = $userId;
    $response = BookingValidator::validate($input);
    if(!$response) {
      return;
    }

    // create booking
    $bookingId = Booking::create($input);

    // update event booked_slots on new booking
    $input['action_type'] = 'create_booking';
    Event::updateBookedSlot($input);

    echo json_encode([
      'success' => true, 
      'message' => 'Booking created successfully',
      'booking_id' => $bookingId
    ]);
  }

  public function index()
  {
    $userId = AuthMiddleware::handle();
    $_GET['user_id'] = $userId;
    $attendees = Booking::list($_GET);

    echo json_encode(['success' => true, 'data' => $attendees]);
  }

  public function delete()
  {
    $id = $_GET['id'] ?? null;
    if(!empty($id)) {
      $userId = AuthMiddleware::handle();
      $booking = Booking::findBooking(['booking_id' => $id]);
      if (!$booking) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Booking not found']);
        exit;
      }

      $event = Event::findEvent($booking['event_id'], $userId);
      if (!$event) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Wrong booking found']);
        exit;
      }
      Booking::delete($id);

      // update event booked_slots on delete booking
      $data = [
        'action_type' =>'delete_booking',
        'event_id' => $booking['event_id'],
        'user_id' => $userId
      ];
      Event::updateBookedSlot($data);

      echo json_encode(['success' => true, 'message' => 'Booking deleted successfully']);
    } else {
      http_response_code(500);
      echo json_encode(['success' => false, 'message' => 'Must be pass Booking id']);
      exit;
    }
  }
}