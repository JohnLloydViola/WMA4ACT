<?php 
declare(strict_types=1); 

require __DIR__ . '/../src/Response.php'; 
require __DIR__ . '/../src/Request.php'; 
require __DIR__ . '/../src/AppointmentRepository.php'; 
require __DIR__ . '/../src/AppointmentValidator.php'; 

$pdo = require __DIR__ . '/../config/database.php'; 
$repo = new AppointmentRepository($pdo); 

// ---- Read the method and URL ---- 
$method = $_SERVER['REQUEST_METHOD']; 
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); 
$base = rtrim(dirname($_SERVER['SCRIPT_NAME'], 2), '/'); 
$path = trim(substr($uri, strlen($base)), '/'); 
$parts = explode('/', $path); 

if (($parts[0] ?? '') !== 'api' || ($parts[1] ?? '') !== 'appointments') { 
    Response::error('Route not found.', 404);
} 

if (isset($parts[2]) && !ctype_digit($parts[2])) { 
    Response::error('Appointment ID must be a number.', 400); 
} 

$id = isset($parts[2]) ? (int) $parts[2] : null; 

// ---- GET routes ---- 
if ($method === 'GET' && $id === null) { 
    Response::success('Appointments retrieved.', $repo->all()); 
} 

if ($method === 'GET') { 
    $appointment = $repo->find($id); 
    if ($appointment === null) { 
        Response::error('Appointment not found.', 404); 
    } 
    Response::success('Appointment retrieved.', $appointment); 
} 

// ---- Anything else ---- 
// Keep this as the LAST line. New routes go ABOVE it. 
Response::error('Method not allowed.', 405);