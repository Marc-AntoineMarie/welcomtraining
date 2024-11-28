<?php
// Inclure votre fichier de connexion à la base de données
require 'db.php';

// log.php

// Fonction pour ajouter un log dans la base de données
function addLog($conn, $event_type, $description) {
    $stmt = $conn->prepare("INSERT INTO logs (event_type, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $event_type, $description);
    $stmt->execute();
    $stmt->close();
}
?>

