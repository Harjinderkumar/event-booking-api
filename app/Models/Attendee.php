<?php

namespace App\Models;

class Attendee
{
  public static function create(array $data)
  {
    $db = Database::connect();
    $statement = $db->prepare("
      INSERT INTO attendees 
      (name, email, user_id, created_at, updated_at)
      VALUES (:name, :email, :user_id, :created_at, :updated_at)
    ");

    $statement->execute([
      ':name'       => $data['name'],
      ':email' => $data['email'],
      ':user_id'  => $data['user_id'],
      ':created_at'  => date('Y-m-d H:i:s'),
      ':updated_at'  => date('Y-m-d H:i:s'),
    ]);

    return (int) $db->lastInsertId();
  }

  public static function list(array $data_arr)
  {
    $db = Database::connect();
    $statement = $db->prepare("SELECT * FROM attendees WHERE user_id = :user_id");
    $statement->execute([':user_id' => $data_arr['user_id']]);
    return $statement->fetchAll() ?: [];
  }

  public static function checkDuplicateEmail(string $email, int $user_id, int $id ) 
  {
    $db = Database::connect();
    if($id) {
      $statement = $db->prepare("SELECT * FROM attendees WHERE NOT id = :id AND email = :email AND user_id = :user_id");
      $statement->execute([':id' => $id, ':email' => $email, ':user_id' => $user_id]);
    } else {
      $statement = $db->prepare("SELECT * FROM attendees WHERE email = :email AND user_id = :user_id");
      $statement->execute([':email' => $email, ':user_id' => $user_id]);
    }
    return $statement->fetch() ?: null;
  }

  // find attendee only for authorized user
  public static function findAttendee(int $id, int $user_id)
  {
    $db = Database::connect();
    $statement = $db->prepare("SELECT * FROM attendees WHERE id = :id AND user_id = :user_id");
    $statement->execute([':id' => $id, ':user_id' => $user_id]);
    return $statement->fetch() ?: null;
  }

  public static function update(int $id, array $data) {
    $db = Database::connect();

    $statement = $db->prepare("
        UPDATE attendees SET
          name = :name,
          email = :email,
          updated_at = :updated_at
        WHERE id = :id
    ");

    return $statement->execute([
        ':id'           => $id,
        ':name'         => $data['name'],
        ':email'        => $data['email'],
        ':updated_at'   => date('Y-m-d H:i:s'),
    ]);
  }

  public static function delete(int $id)
  {
    $db = Database::connect();
    $statement = $db->prepare("DELETE FROM attendees WHERE id = :id");
    return $statement->execute([':id' => $id]);
  }

}