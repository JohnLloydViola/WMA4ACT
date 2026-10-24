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
  public function create(array $data): int 
  { 
          $sql = 'INSERT INTO appointments (owner_name, pet_name, service, appointment_date, status) 
                  VALUES (:owner_name, :pet_name, :service, :appointment_date, :status)'; 
          
          $this->pdo->prepare($sql)->execute($data); 
          
          return (int) $this->pdo->lastInsertId(); 
  }

  public function update(int $id, array $data): void 
    { 
        $sql = 'UPDATE appointments 
                SET owner_name = :owner_name, 
                    pet_name = :pet_name, 
                    service = :service, 
                    appointment_date = :appointment_date, 
                    status = :status 
                WHERE id = :id'; 
                
        $this->pdo->prepare($sql)->execute($data + ['id' => $id]); 
    } 

    public function delete(int $id): bool 
    { 
        $sql = 'DELETE FROM appointments WHERE id = :id'; 
        $stmt = $this->pdo->prepare($sql); 
        $stmt->execute(['id' => $id]); 
        
        return $stmt->rowCount() > 0; 
    }

  // Always add new methods ABOVE the final closing brace. 
}