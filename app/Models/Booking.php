<?php

namespace App\Models;

class Booking
{
  // find Booking
  public static function findBooking(array $data)
  {
    $db = Database::connect();
    $sql = "SELECT * FROM bookings WHERE created_at IS NOT NULL";
    if(!empty($data['event_id'])) {
      $sql .= " AND event_id = :event_id";
      $filter_arr[':event_id'] = $data['event_id'];
    }
    if(!empty($data['attendee_id'])) {
       $sql .= " AND attendee_id = :attendee_id";
      $filter_arr[':attendee_id'] = $data['attendee_id'];
    }
    if(!empty($data['booking_id'])) {
       $sql .= " AND id = :id";
      $filter_arr[':id'] = $data['booking_id'];
    }

    $statement = $db->prepare($sql);

    foreach ($filter_arr as $key => $value) {
      $statement->bindValue($key, $value);
    }
    $statement->execute();

    return $statement->fetch() ?: null;
  }

  public static function create(array $data) {
    $db = Database::connect();
    $statement = $db->prepare("
      INSERT INTO bookings 
      (event_id, attendee_id, booked_at, created_at, updated_at)
      VALUES (:event_id, :attendee_id, :booked_at, :created_at, :updated_at)
    ");

    $statement->execute([
      ':event_id'       => $data['event_id'],
      ':attendee_id' => $data['attendee_id'],
      ':booked_at'  => date('Y-m-d H:i:s'),
      ':created_at'  => date('Y-m-d H:i:s'),
      ':updated_at'  => date('Y-m-d H:i:s'),
    ]);

    return (int) $db->lastInsertId();
  }

  public static function list(array $data_arr)
  {
    $db = Database::connect();
    $statement = $db->prepare("SELECT b.id, b.event_id, e.name, b.attendee_id, a.email, b.booked_at  FROM bookings b INNER JOIN events e ON e.id = b.event_id INNER JOIN attendees a ON a.id = b.attendee_id  WHERE e.user_id = :user_id");
    $statement->execute([':user_id' => $data_arr['user_id']]);
    $result_arr = $statement->fetchAll() ?: [];
    $response = [];
    foreach ($result_arr as $key => $value) {
      $response[] = [
        'id' => $value['id'],
        'event' =>  ['id' => $value['event_id'], 'name' => $value['name']],
        'attendee' => ['id' => $value['attendee_id'], 'email' => $value['email']],
        'booked_at' => $value['booked_at'],
      ];
    }
    return $response;
  }

  public static function delete(int $id) {
    $db = Database::connect();
    $statement = $db->prepare("DELETE FROM bookings WHERE id = :id");
    $response = $statement->execute([':id' => $id]);

    return $response;
  }
}