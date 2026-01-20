<?php 

use App\Controllers\EventController;
use App\Controllers\AttendeeController;
use App\Controllers\BookingController;
use App\Controllers\AuthController;

$router->post('/login', [AuthController::class, 'login']);


$router->post('/event', [EventController::class, 'store']);

$router->get('/event', [EventController::class, 'index']);

$router->put('/event', [EventController::class, 'update']);

$router->delete('/event', [EventController::class, 'delete']);

$router->post('/attendee', [AttendeeController::class, 'store']);

$router->get('/attendee', [AttendeeController::class, 'index']);

$router->put('/attendee', [AttendeeController::class, 'update']);

$router->delete('/attendee', [AttendeeController::class, 'delete']);

$router->post('/booking', [BookingController::class, 'store']);

$router->get('/booking', [BookingController::class, 'index']);

$router->delete('/booking', [BookingController::class, 'delete']);

$router->post('/attendee/regsiter', [AttendeeController::class, 'attendeeRegsiter']);