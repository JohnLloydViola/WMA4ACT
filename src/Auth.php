<?php 
declare(strict_types=1); 

final class Auth 
{ 
    public static function authorize(string $requiredRole): void 
    { 
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? ''; 
        
        if (!preg_match('/^Bearer\s+(\S+)$/', $header, $match)) { 
            Response::error('Missing or malformed token.', 401); 
        } 
        
        $tokens = [
            (getenv('API_TOKEN_ADMIN') ?: 'admin-demo-token') => 'admin', 
            (getenv('API_TOKEN_STAFF') ?: 'staff-demo-token') => 'staff', 
        ]; 
        
        $role = null; 
        
        foreach ($tokens as $token => $tokenRole) { 
            if (hash_equals((string) $token, $match[1])) { 
                $role = $tokenRole; 
            } 
        } 
        
        if ($role === null) { 
            Response::error('Invalid token.', 401); 
        } 
        
        if ($requiredRole === 'admin' && $role !== 'admin') { 
            Response::error('Admin role required.', 403); 
        } 
    } 
}