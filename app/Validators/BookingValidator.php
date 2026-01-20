<?php

namespace App\Validators;

use App\Models\Attendee;
use App\Models\Booking;
use App\Models\Event;

class BookingValidator {
  public static function validate($input) {
    $userId = $input['user_id'];
    // check event
    $event = Event::findEvent($input['event_id'], $userId);
    if (!$event) {
      http_response_code(404);
      echo json_encode(['success' => false, 'message' => 'Event not found']);
      return false;
    }

    // check Attendee
    $attendee = Attendee::findAttendee($input['attendee_id'], $userId);
    if (!$attendee) {
      http_response_code(404);
      echo json_encode(['success' => false, 'message' => 'Attendee not found']);
      return false;
    }

    // check duplicate booking
    $booking = Booking::findBooking($input);
    if ($booking) {
      http_response_code(404);
      echo json_encode(['success' => false, 'message' => 'Duplicate booking is not allowed']);
      return false;
    }

    // check overbooking
    if($event['booked_slots'] >= $event['capacity']) {
      http_response_code(404);
      echo json_encode(['success' => false, 'message' => 'This event is full. Overbooking is not allowed']);
      return false;
    }
    return true;
  }
}     