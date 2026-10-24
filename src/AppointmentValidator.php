<?php 
declare(strict_types=1); 

final class AppointmentValidator 
{ 
  private const REQUIRED = [ 
    'owner_name', 
    'pet_name', 
    'service', 
    'appointment_date', 
  ]; 

  public static function validate(array $input): array
  { 
    $errors = []; 
    $data = []; 
        
    foreach (self::REQUIRED as $field) { 
      $value = $input[$field] ?? ''; 
      $value = is_string($value) ? trim($value) : ''; 
            
      if ($value === '') { 
        $errors[$field][] = "The $field field is required."; 
      } 
            
      $data[$field] = $value; 
    } 
        
    $status = $input['status'] ?? 'pending'; 
    $data['status'] = is_string($status) ? trim($status) : 'pending'; 
        
    return [$data, $errors]; 
  } 
}