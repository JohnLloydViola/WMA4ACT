<?php 
declare(strict_types=1); 

final class DocumentUploader 
{ 
    private const MAX_BYTES = 2 * 1024 * 1024; // 2 MB 
    
    private const ALLOWED = [ 
        'image/jpeg' => 'jpg', 
        'image/png' => 'png', 
        'application/pdf' => 'pdf', 
    ]; 
    
    public function __construct(private string $directory) 
    { 
    } 
    
    public function store(array $file, int $appointmentId): array 
    { 
        $error = $file['error']; 
        
        if ($error === UPLOAD_ERR_INI_SIZE || $error === UPLOAD_ERR_FORM_SIZE) { 
            Response::error('File is too large.', 413); 
        } 
        
        if ($error !== UPLOAD_ERR_OK) { 
            Response::error('Upload failed (code ' . $error . ').', 400); 
        } 
        
        if ($file['size'] > self::MAX_BYTES) { 
            Response::error('File exceeds the 2 MB limit.', 413); 
        } 
        
        $finfo = new finfo(FILEINFO_MIME_TYPE); 
        $mime = $finfo->file($file['tmp_name']); 
        
        if (!isset(self::ALLOWED[$mime])) { 
            Response::error('Only JPG, PNG, and PDF are allowed.', 415); 
        } 
        
        $name = sprintf( 
            'appointment-%d-%s.%s', 
            $appointmentId, 
            bin2hex(random_bytes(8)), 
            self::ALLOWED[$mime] 
        ); 
        
        $target = $this->directory . '/' . $name; 
        
        if (!move_uploaded_file($file['tmp_name'], $target)) { 
            Response::error('Could not save the file.', 500); 
        } 
        
        return [ 
            'file_name' => $name, 
            'mime_type' => $mime, 
            'size_bytes' => (int) $file['size'], 
        ]; 
    } 
}