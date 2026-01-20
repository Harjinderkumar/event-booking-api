<?php

require_once __DIR__ . '/TestCase.php';

class EventTest extends ApiTestCase
{
  private string $authToken = '1';

  /** @test */
  public function it_create_event()
  {
    $payload = [
      'name'         => 'PHP Conference',
      'description'  => 'Core PHP event',
      'country_code' => 'GB',
      'event_date'   => '2026-02-01 10:00:00',
      'capacity'     => '20',
    ];

    $response = $this->request(
      'POST',
      '/index.php/api/event',
      $payload,
      ['Authorization' => 'Bearer ' . $this->authToken]
    );

    $this->assertTrue($response['success']);
    $this->assertArrayHasKey('event_id', $response);
    $this->assertEquals('Event created successfully', $response['message']);
  }

  /** @test */
  public function it_updates_event()
  {
    // First create event
    $create = $this->request(
      'POST',
      '/index.php/api/event',
      [
        'name'       => 'Old Title',
        'description' => 'Old desc',
        'country_code'     => 'UK',
        'event_date'   => '2026-02-01 10:00:00',
        'capacity'     => '22',
      ],
      ['Authorization' => 'Bearer ' . $this->authToken]
    );

    $eventId = $create['event_id'];

    // Update event
    $update = $this->request(
      'PUT',
      "/index.php/api/event",
      [
        'name' => 'Updated Title',
        'description' => 'Old desc',
        'country_code'     => 'UK',
        'event_date'   => '2026-02-01 10:00:00',
        'capacity'     => '22',
      ],
      ['Authorization' => 'Bearer ' . $this->authToken],
      ['id' => $eventId]
    );

    $this->assertTrue($update['success']);
    $this->assertEquals('Event updated successfully', $update['message']);
  }

  /** @test */
  public function it_deletes_event()
  {
    // Create event
    $create = $this->request(
      'POST',
      '/index.php/api/event',
      [
        'name'       => 'To Be Deleted',
        'description' => 'Temporary',
        'country_code'     => 'UK',
        'event_date'   => '2026-02-01 10:00:00',
        'capacity'     => '20',
      ],
      ['Authorization' => 'Bearer ' . $this->authToken],
    );

    $eventId = $create['event_id'];

    // Delete
    $delete = $this->request(
      'DELETE',
      "/index.php/api/event",
      [],
      ['Authorization' => 'Bearer ' . $this->authToken],
      ['id' => $eventId]
    );

    $this->assertTrue($delete['success']);
    $this->assertEquals('Event deleted successfully', $delete['message']);
  }
}