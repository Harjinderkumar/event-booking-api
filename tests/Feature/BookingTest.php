<?php

require_once __DIR__ . '/TestCase.php';

class BookingTest extends ApiTestCase
{
  private string $authToken = '1';

  /** @test */
  public function it_test_overbooking()
  {
    $payload = self::insert_mock_booking_data();

    // create first booking
    $response_one = $this->request(
      'POST',
      '/index.php/api/booking',
      $payload,
      ['Authorization' => 'Bearer ' . $this->authToken]
    );

    $payload_two = self::insert_mock_booking_data();
    $payload['attendee_id'] = $payload_two['attendee_id'];

    // create second booking with another attendee for check overbooking
    $response_two = $this->request(
      'POST',
      '/index.php/api/booking',
      $payload,
      ['Authorization' => 'Bearer ' . $this->authToken]
    );

    $this->assertTrue($response_one['success']);
    $this->assertEquals('Booking created successfully', $response_one['message']);

    $this->assertFalse($response_two['success']);
    $this->assertEquals('This event is full. Overbooking is not allowed', $response_two['message']);
  }

  /** @test */
  public function it_test_duplicate_booking() {
    $payload = self::insert_mock_booking_data();

    // create first booking
    $response_one = $this->request(
      'POST',
      '/index.php/api/booking',
      $payload,
      ['Authorization' => 'Bearer ' . $this->authToken]
    );

    // create second booking with same payload for check duplicate
    $response_two = $this->request(
      'POST',
      '/index.php/api/booking',
      $payload,
      ['Authorization' => 'Bearer ' . $this->authToken]
    );

    $this->assertTrue($response_one['success']);
    $this->assertEquals('Booking created successfully', $response_one['message']);

    $this->assertFalse($response_two['success']);
    $this->assertEquals('Duplicate booking is not allowed', $response_two['message']);
  }

  /** @test */
  public function it_delete_booking() {
    $payload = self::insert_mock_booking_data();

    // create first booking
    $response_one = $this->request(
      'POST',
      '/index.php/api/booking',
      $payload,
      ['Authorization' => 'Bearer ' . $this->authToken]
    );

    // create second booking with same payload for check duplicate
    $response_two = $this->request(
      'POST',
      '/index.php/api/booking',
      $payload,
      ['Authorization' => 'Bearer ' . $this->authToken],
      ['id' => $response_one['booking_id']]
    );

    $this->assertTrue($response_one['success']);
    $this->assertEquals('Booking created successfully', $response_one['message']);

    $this->assertFalse($response_two['success']);
    $this->assertEquals('Duplicate booking is not allowed', $response_two['message']);
  }

  public function insert_mock_booking_data() {
    // create attendee
    $response = $this->request(
      'POST',
      '/index.php/api/attendee',
      [
        'name'   => 'James Test',
        'email'  => 'Jamestes'.rand(10,100).'@gmail.com',
      ],
      ['Authorization' => 'Bearer ' . $this->authToken]
    );
    $attendee_id = $response['attendee_id'];

    // create booking
    $create = $this->request(
      'POST',
      '/index.php/api/event',
      [
        'name'       => 'Old Title',
        'description' => 'Old desc',
        'country_code'     => 'UK',
        'event_date'   => '2026-02-01 10:00:00',
        'capacity'     => '1',
      ],
      ['Authorization' => 'Bearer ' . $this->authToken]
    );

    $eventId = $create['event_id'];
    return ['attendee_id' => $attendee_id, 'event_id' => $eventId];
  }
}