<?php 
declare(strict_types=1); 

$dsn = 'mysql:host=127.0.0.1;dbname=vet_clinic;charset=utf8mb4'; 

return new PDO($dsn, 'root', '', [ 
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
  PDO::ATTR_EMULATE_PREPARES => false, 
]);