<?php 
declare(strict_types=1); 

final class Request 
{ 
  public static function json(): array 
  { 
    $type = $_SERVER['CONTENT_TYPE'] ?? '';

    if (stripos($type, 'application/json') !== 0) { 
      Response::error('Content-Type must be JSON.', 415); 
    } 
        
    $raw = (string) file_get_contents('php://input'); 
    $input = json_decode($raw, true); 
        
    if (!is_array($input)) { 
        Response::error('Request body must be valid JSON.', 400); 
    } 
        
    return $input; 
  } 
}