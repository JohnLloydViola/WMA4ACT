<?php 

$pdo = require __DIR__ . '/../config/database.php'; 

echo 'Connected. Rows: ' . $pdo->query('SELECT COUNT(*) FROM appointments')->fetchColumn();