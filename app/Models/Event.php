<?php

namespace App\Models;

use PDO;

class Event
{
  public static function create(array $data)
  {
    $db = Database::connect();

    $statement = $db->prepare("
      INSERT INTO events 
      (name, description, event_date, country_code, capacity, user_id, created_at, updated_at)
      VALUES (:name, :description, :event_date, :country_code, :capacity, :user_id, :created_at, :updated_at)
    ");

    $statement->execute([
      ':name'       => $data['name'],
      ':description' => $data['description'],
      ':event_date'  => $data['event_date'],
      ':country_code'    => $data['country_code'],
      ':capacity'    => $data['capacity'],
      ':user_id'  => $data['user_id'],
      ':created_at'  => date('Y-m-d H:i:s'),
      ':updated_at'  => date('Y-m-d H:i:s'),
    ]);

    return (int) $db->lastInsertId();
  }

  public static function list(array $data_arr)
  {
    $page = (int)($data_arr['page'] ?? 1);
    $limit = (int)($data_arr['limit'] ?? 10);
    $offset = ($page - 1) * $limit; // calculate offset
    $country_code = isset($data_arr['country_code']) ? trim($data_arr['country_code']) : "";

    $db = Database::connect();

    $sql = "SELECT * FROM events WHERE user_id = :user_id";

    $filter_arr = [];
    if ($country_code) {
      $sql .= " AND country_code = :country_code";
      $filter_arr[':country_code'] = $country_code;
    }
    $filter_arr[':user_id'] = $data_arr['user_id'];

    $sql .= " ORDER BY event_date DESC LIMIT :limit OFFSET :offset";

    $statement = $db->prepare($sql);

    foreach ($filter_arr as $key => $value) {
      $statement->bindValue($key, $value);
    }

    $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
    $statement->bindValue(':offset', $offset, PDO::PARAM_INT);

    $statement->execute();
    $events = $statement->fetchAll();
    
    return [
      'success' => 1, 
      'data' => $events,
      'meta' => [
        'page' => $page,
        'limit' => $limit,
        'total' => self::count($country_code)
      ]
    ];
  }

  public static function count(string $country_code)
  {
    $db = Database::connect();

    $sql = "SELECT COUNT(*) FROM events";
    $filter_arr = [];

    if ($country_code) {
      $sql .= " WHERE country_code = :country_code";
      $filter_arr[':country_code'] = $country_code;
    }

    $statement = $db->prepare($sql);
    $statement->execute($filter_arr);

    return (int) $statement->fetchColumn();
  }

  // find event only for authorized user
  public static function findEvent(int $id, int $user_id)
  {
    $db = Database::connect();
    $statement = $db->prepare("SELECT * FROM events WHERE id = :id AND user_id = :user_id");
    $statement->execute([':id' => $id, ':user_id' => $user_id]);
    return $statement->fetch() ?: null;
  }

  public static function update(int $id, array $data)
  {
    $db = Database::connect();

    $statement = $db->prepare("
        UPDATE events SET
            name = :name,
            description = :description,
            event_date = :event_date,
            country_code = :country_code,
            capacity = :capacity,
            updated_at = :updated_at
        WHERE id = :id
    ");

    return $statement->execute([
        ':id'           => $id,
        ':name'         => $data['name'],
        ':description'  => $data['description'],
        ':event_date'   => $data['event_date'],
        ':country_code' => $data['country_code'],
        ':capacity'     => $data['capacity'],
        ':updated_at'   => date('Y-m-d H:i:s'),
    ]);
  }

  public static function delete(int $id)
  {
    $db = Database::connect();
    $statement = $db->prepare("DELETE FROM events WHERE id = :id");
    return $statement->execute([':id' => $id]);
  }

  public static function updateBookedSlot(array $data)
  {
    $db = Database::connect();
    $event = Event::findEvent($data['event_id'], $data['user_id']);
    $booked_slots = !empty($event['booked_slots']) ? $event['booked_slots'] : 0;
    if($data['action_type'] == 'create_booking') {
      ++$booked_slots;
    } else {
      --$booked_slots;
    }
    $statement = $db->prepare("UPDATE events SET booked_slots = :booked_slots, updated_at = :updated_at WHERE id = :id");
    $statement->execute([
      ':id'           => $data['event_id'],
      ':booked_slots' => $booked_slots,
      ':updated_at'   => date('Y-m-d H:i:s'),
    ]);
  }

}
