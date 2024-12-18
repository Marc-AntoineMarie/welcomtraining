<?php
$host = 'localhost';
$db = 'wt';
$user = 'root'; 
$pass = ''; 

// connexion a la base de données avec PDO
try {
    
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // echo "Connexion réussie !"; 
} catch (PDOException $e) {
    echo "Échec de la connexion : " . $e->getMessage();
}

// Connexion à la base de données avec MySQLi
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
