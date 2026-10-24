<?php 
declare(strict_types=1); 

final class Response 
{ 
  public static function json(int $status, array $body): never
  { 
    http_response_code($status); 
    header('Content-Type: application/json; charset=utf-8'); 
    echo json_encode($body, JSON_UNESCAPED_UNICODE); 
    exit; 
  } 

  public static function success( string $message, mixed $data = null, int $status = 200 ): never 
  { 
    self::json($status, [ 
      'success' => true, 
      'message' => $message, 
      'data' => $data, 
    ]); 
  } 

  public static function error( string $message, int $status, array $errors = [] ): never 
  { 
    $body = ['success' => false, 'message' => $message]; 

    if ($errors) { 
      $body['errors'] = $errors; 
    } 
    
    self::json($status, $body); 
  } 

  public static function noContent(): never 
  { 
    http_response_code(204); 
    exit; 
  } 
}