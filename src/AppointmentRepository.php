<?php 
declare(strict_types=1); 

final class AppointmentRepository 
{ 
  public function __construct(private PDO $pdo) 
  { 
  } 

  public function all(): array 
  { 
    $stmt = $this->pdo->query( 
      'SELECT * FROM appointments ORDER BY appointment_date' 
    ); 
    return $stmt->fetchAll(); 
  } 

  public function find(int $id): ?array 
  { 
    $stmt = $this->pdo->prepare( 
        'SELECT * FROM appointments WHERE id = :id' 
    ); 
    $stmt->execute(['id' => $id]); 
    return $stmt->fetch() ?: null; 
  } 
  
  // create(), update() and delete() are added in Stages 7 and 8. 
  // Always add new methods ABOVE the final closing brace. 
}