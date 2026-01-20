<?php

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Validators\EventValidator;
use App\Models\Event;
use App\Helpers\CommonHelper;

class EventController
{
    // create new event
    public function store()
    {
      $userId = AuthMiddleware::handle();
      $input = CommonHelper::getJsonInput();

      if (!$input) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
        return;
      }

      $data = EventValidator::validate($input);
      $data['user_id'] = $userId;

      $eventId = Event::create($data);

      http_response_code(201);
      echo json_encode([
        'success' => true, 
        'message' => 'Event created successfully',
        'event_id' => $eventId
      ]);
    }

    // list all event with pagination and country filter
    public function index()
    {
      $userId = AuthMiddleware::handle();
      $_GET['user_id'] = $userId;
      $events = Event::list($_GET);

      echo json_encode(['data' => $events]);
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

        $event = Event::findEvent($id, $userId);
        if (!$event) {
          http_response_code(404);
          echo json_encode(['success' => false, 'message' => 'Event not found']);
          exit;
        }

        $data = EventValidator::validate($input);
        Event::update($id, $data);
        echo json_encode(['success' => true, 'message' => 'Event updated successfully']);
      } else {
          http_response_code(500);
          echo json_encode(['success' => false, 'message' => 'Must be pass Event id']);
          exit;
      }
    }

    public function delete()
    {
      $id = $_GET['id'] ?? null;
      if(!empty($id)) {
        $userId = AuthMiddleware::handle();
        $event = Event::findEvent($id, $userId);
        if (!$event) {
          http_response_code(404);
          echo json_encode(['success' => false, 'message' => 'Event not found']);
          exit;
        }
        Event::delete($id);

        echo json_encode(['success' => true, 'message' => 'Event deleted successfully']);
      } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Must be pass Event id']);
        exit;
      }
    }

}
