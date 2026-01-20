  CREATE DATABASE IF NOT EXISTS event_booking;

  USE event_booking;

  CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255),
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  );

  CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(255),
    description TEXT ,
    event_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    capacity INT DEFAULT 0,
    booked_slots INT DEFAULT 0,
    country_code VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  );

  CREATE TABLE IF NOT EXISTS attendees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  );

  CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT,
    attendee_id INT,
    booked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  );

  -- ALTER TABLE events ADD CONSTRAINT user_events FOREIGN KEY (user_id) REFERENCES users (id);

  -- ALTER TABLE attendees ADD CONSTRAINT user_attendees FOREIGN KEY (user_id) REFERENCES users (id);

  -- ALTER TABLE bookings ADD FOREIGN KEY (event_id) REFERENCES events (id);

  -- ALTER TABLE bookings ADD FOREIGN KEY (attendee_id) REFERENCES attendees (id);

  -- ADDED THE DUMMY USER ENTRY FOR TESTING
  INSERT INTO users (name, email, password, created_at, updated_at) VALUES ('Super Admin', 'superadmin@test.com', '$2a$12$6OPJoAfX9J6hSzAkvg2/xehBCzU5Mw2IXVEdSfPqxxSc2bhNJKM0W', '2026-02-01 10:00:00', '2026-02-01 10:00:00');
  
  INSERT INTO events (user_id, name, description, event_date, capacity, booked_slots, country_code, created_at, updated_at) VALUES (1, 'Test Event1', 'This is test event 1', '2026-02-01 10:00:00', 20, 0, 'GB', '2026-02-01 10:00:00', '2026-02-01 10:00:00');
  INSERT INTO events (user_id, name, description, event_date, capacity, booked_slots, country_code, created_at, updated_at) VALUES (1, 'Test Event 2', 'This is test event 2', '2026-02-05 10:00:00', 20, 0, 'GB', '2026-02-01 10:00:00', '2026-02-01 10:00:00');
  
  INSERT INTO attendees (user_id, name, email, created_at, updated_at) VALUES (1, 'Test attendee1', 'testattende1@test.com', '2026-02-01 10:00:00', '2026-02-01 10:00:00');
  INSERT INTO attendees (user_id, name, email, created_at, updated_at) VALUES (1, 'Test attendee2', 'testattende2@test.com', '2026-02-01 10:00:00', '2026-02-01 10:00:00');

  INSERT INTO bookings (event_id, attendee_id, booked_at, created_at, updated_at) VALUES (1, 1, '2026-01-01 10:00:00', '2026-02-01 10:00:00', '2026-02-01 10:00:00');