# Event Management API

Admin User can create, update, delete and list events with pagination and country filter. He can manage attendees(create, edit, update and delete).
  Also, he can create, edit and delete event booking for attendees.

## Installation

Use the git for clone repository

```bash
git clone https://github.com/Harjinderkumar/event-booking-api.git
```
Run the docker command on project root

```bash
docker-compose up --build
```
Use below command for run unit tests on project root

```bash
docker exec -it event_api_app ./tools/phpunit
```

## API Documentation
Please click below link for access documentation:
https://documenter.getpostman.com/view/51607221/2sBXVihAYW


You can import Postman JSON collection in your postman app. Please download file from below link:
https://github.com/Harjinderkumar/event-booking-api/blob/main/assets/Event_Booking_API.postman_collection.json


## Database Structure

![Screenshot of Database Structure.](/assets/db-schema.png)
