# Veterinary Clinic Appointment REST API

## Project Description

The Veterinary Clinic Appointment REST API is a web-based API developed using native PHP and MySQL. It allows users to retrieve, create, update, and delete veterinary clinic appointments. It also supports authentication, request validation, error handling, and document uploads.

## Technologies Used

- PHP 8.1+
- MySQL / MariaDB
- Apache
- XAMPP
- Postman

##
Press CTRL + SHIFT + V to view this markdown in pretty mode in VSCODE.

## Setup Instructions

### 1. Install and Start XAMPP

1. Install XAMPP on your computer.
2. Open the XAMPP Control Panel.
3. Start **Apache** and **MySQL**.

### 2. Place the Project Folder

1. Create the `vet-appointment-api` folder into your XAMPP `htdocs` directory.
2. Paste all the files located on this folder on to that folder.
3. The project should be accessible at:

   `http://localhost/vet-appointment-api`

### 3. Create the Database

Create the database and appointments table by pasting this SQL code to your SQL tab in your PhpMyAdmin:

```sql
CREATE DATABASE IF NOT EXISTS vet_clinic
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE vet_clinic;

CREATE TABLE appointments (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    owner_name VARCHAR(100) NOT NULL,
    pet_name VARCHAR(100) NOT NULL,
    service VARCHAR(100) NOT NULL,
    appointment_date DATETIME NOT NULL,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled')
        NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO appointments (
    owner_name,
    pet_name,
    service,
    appointment_date,
    status
) VALUES
    ('Juan Dela Cruz', 'Mimi', 'Check-up', '2026-11-02 08:00:00', 'confirmed'),
    ('Ana Reyes', 'Choco', 'Grooming', '2026-11-02 10:30:00', 'pending');
```

### 4. Configure the Database Connection

Open `config/database.php` and make sure the database connection settings match your local MySQL configuration.

The database name should be `vet_clinic`. Update the username and password if your local MySQL settings are different.

### 5. Test the API

1. Open Postman.
2. Use this API base URL: `http://localhost/vet-appointment-api`.
3. Send requests to the endpoints listed in this README.
4. Include the required authentication token for protected requests.

## Authentication

The API uses **Bearer Token Authentication** to restrict access to certain operations.

### User Roles and Permissions

| Role | Permissions |
|---|---|
| Staff | Create and update appointments |
| Admin | Delete appointments |

### Demo Tokens

| Variable | Value |
|---|---|
| `staffToken` | `staff-demo-token` |
| `adminToken` | `admin-demo-token` |

### How to Use Authentication in Postman

1. Open the Postman collection.
2. Go to the **Authorization** tab.
3. Select **Bearer Token**.
4. Enter `{{staffToken}}` for staff requests.
5. For DELETE requests, open the individual request's Authorization tab and use `{{adminToken}}`.

GET requests do not require authentication.

**Note:** These are demonstration tokens intended for local testing only. Do not use them on a live production server.

## API Endpoints

**Base URL:** `http://localhost/vet-appointment-api`

| Method | Endpoint | Description | Expected Status |
|---|---|---|---|
| GET | `/api/appointments` | Retrieve all appointments | 200 OK |
| GET | `/api/appointments/{id}` | Retrieve a specific appointment | 200 OK |
| POST | `/api/appointments` | Create a new appointment | 201 Created |
| PUT | `/api/appointments/{id}` | Update an existing appointment | 200 OK |
| DELETE | `/api/appointments/{id}` | Delete an appointment | 204 No Content |
| POST | `/api/appointments/{id}/documents` | Upload an appointment document | 201 Created |

### Common HTTP Status Codes

| Status Code | Meaning |
|---|---|
| 200 | Request successful |
| 201 | Resource successfully created |
| 204 | Resource successfully deleted; no response body |
| 400 | Bad request |
| 401 | Authentication required or invalid |
| 403 | Authenticated user does not have permission |
| 404 | Requested resource or route not found |
| 415 | Unsupported media type |
| 422 | Validation failed |
| 500 | Internal server error |

## Postman Test Cases

The API is tested using a Postman collection named **Vet-Appointment-API**.



### Postman Test Matrix

| # | Test Case | Method | Endpoint | Input | Expected Status | Expected Output |
|---|---|---|---|---|---|---|
| 1 | List all | GET | `/api/appointments` | None | 200 | `data` is an array |
| 2 | Get one | GET | `/api/appointments/1` | None | 200 | `data.id` is 1 |
| 3 | Get missing | GET | `/api/appointments/999` | None | 404 | Appointment not found. |
| 4 | Invalid ID | GET | `/api/appointments/abc` | None | 400 | ID must be a number. |
| 5 | Create valid | POST | `/api/appointments` | Valid JSON, staff token | 201 | New record with ID |
| 6 | Missing field | POST | `/api/appointments` | No `pet_name` | 422 | `errors.pet_name` |
| 7 | Bad status | POST | `/api/appointments` | Status `done` | 422 | `errors.status` |
| 8 | No token | POST | `/api/appointments` | Valid JSON, no authentication | 401 | Missing or malformed token. |
| 9 | Wrong type | POST | `/api/appointments` | Body type Text | 415 | Content-Type must be JSON. |
| 10 | Update | PUT | `/api/appointments/{id}` | Full JSON, staff token | 200 | Updated record |
| 11 | Delete as staff | DELETE | `/api/appointments/{id}` | Staff token | 403 | Admin role required. |
| 12 | Delete as admin | DELETE | `/api/appointments/{id}` | Admin token | 204 | Empty response body |
| 13 | Upload PNG | POST | `/api/appointments/{id}/documents` | Form-data field `document` with PNG file | 201 | `file_name`, `mime_type` |
| 14 | Upload PHP | POST | `/api/appointments/{id}/documents` | Form-data field `document` with `evil.php` | 415 | Only JPG, PNG, and PDF are allowed. |


## Project Structure

```text
vet-appointment-api/
├── config/
│   └── database.php
├── public/
│   ├── uploads/.htaccess
│   └── index.php
├── src/
│   ├── Response.php
│   ├── Request.php
│   ├── AppointmentRepository.php
│   ├── AppointmentValidator.php
│   ├── Auth.php
│   └── DocumentUploader.php
├── .htaccess
├── README.md
```

## Notes

- Apache and MySQL must be running before testing the API.
- Protected requests must include the appropriate Bearer Token.
- POST and PUT requests must send JSON data.
- Document uploads must use `multipart/form-data` with the field name `document`.
- Only JPG, PNG, and PDF files are supported for document uploads.
- The API is intended for local development and testing.