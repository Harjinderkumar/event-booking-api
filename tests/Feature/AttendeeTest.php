<?php

require_once __DIR__ . '/TestCase.php';

class AttendeeTest extends ApiTestCase
{
  private string $authToken = '1';

  /** @test */
  public function it_create_attendee()
  {
    $payload = [
      'name'   => 'James Test',
      'email'  => 'Jamestest'.rand(10,100).'@gmail.com',
    ];

    $response = $this->request(
      'POST',
      '/index.php/api/attendee',
      $payload,
      ['Authorization' => 'Bearer ' . $this->authToken]
    );

    $this->assertTrue($response['success']);
    $this->assertArrayHasKey('attendee_id', $response);
    $this->assertEquals('Attendee created successfully', $response['message']);
  }

  /** @test */
  public function it_update_attendee() {
    $payload = [
      'name'   => 'James Test11',
      'email'  => 'Jamestest'.rand(10,100).'@gmail.com',
    ];

    // First create event
    $response = $this->request(
      'POST',
      '/index.php/api/attendee',
      $payload,
      ['Authorization' => 'Bearer ' . $this->authToken]
    );
    $attendee_id = $response['attendee_id'];

    // Update event
    $update = $this->request(
      'PUT',
      "/index.php/api/attendee",
      [
        'name'   => 'James Test1122',
        'email'  => 'Jamestest'.rand(10,100).'@gmail.com',
      ],
      ['Authorization' => 'Bearer ' . $this->authToken],
      ['id' => $attendee_id]
    );

    $this->assertTrue($update['success']);
    $this->assertEquals('Attendee updated successfully', $update['message']);
  }

  /** @test */
  public function it_delete_attendee() {
    $payload = [
      'name'   => 'James Test333',
      'email'  => 'Jamestest333'.rand(10,100).'1@gmail.com',
    ];

    // First create attendee
    $response = $this->request(
      'POST',
      '/index.php/api/attendee',
      $payload,
      ['Authorization' => 'Bearer ' . $this->authToken]
    );
    $attendee_id = $response['attendee_id'];

    // Delete attendee
    $update = $this->request(
      'DELETE',
      "/index.php/api/attendee",
      [],
      ['Authorization' => 'Bearer ' . $this->authToken],
      ['id' => $attendee_id]
    );

    $this->assertTrue($update['success']);
    $this->assertEquals('Attendee deleted successfully', $update['message']);
  }
}