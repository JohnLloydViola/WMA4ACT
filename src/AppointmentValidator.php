<?php 
declare(strict_types=1); 

final class AppointmentValidator 
{ 
    public const STATUSES = [ 
        'pending', 
        'confirmed', 
        'completed', 
        'cancelled', 
    ]; 
    
    private const TEXT_FIELDS = ['owner_name', 'pet_name', 'service']; 
    private const MAX_LENGTH = 100; 
    private const DATE_FORMAT = 'Y-m-d H:i:s'; 
    
    public static function validate(array $input): array 
    { 
        $text = function (string $key) use ($input): string { 
            $value = $input[$key] ?? ''; 
            return is_string($value) ? trim($value) : ''; 
        }; 
        
        $errors = []; 
        $data = []; 
        
        foreach (self::TEXT_FIELDS as $field) { 
            $data[$field] = $text($field); 
            
            if ($data[$field] === '') { 
                $errors[$field][] = "The $field field is required."; 
            } elseif (mb_strlen($data[$field]) > self::MAX_LENGTH) { 
                $errors[$field][] = "The $field field is too long."; 
            } 
        } 
        
        $given = $text('appointment_date'); 
        $date = DateTime::createFromFormat(self::DATE_FORMAT, $given); 
        $data['appointment_date'] = $given; 
        
        if (!$date || $date->format(self::DATE_FORMAT) !== $given) { 
            $errors['appointment_date'][] = 'A valid appointment date is required.'; 
        } 
        
        $data['status'] = strtolower($text('status')) ?: 'pending'; 
        
        if (!in_array($data['status'], self::STATUSES, true)) { 
            $errors['status'][] = 'Status is not allowed.'; 
        } 
        
        return [$data, $errors]; 
    } 
}