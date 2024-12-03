<?php 
// Requêtes pour compter les utilisateurs par rôle
$prof_query = "SELECT COUNT(*) AS total FROM user WHERE etat = 'teacher'";
$student_query = "SELECT COUNT(*) AS total FROM user WHERE etat = 'student'";
$visitor_query = "SELECT COUNT(*) AS total FROM user WHERE etat = 'visitor'";

// Exécution des requêtes
$prof_result = $conn->query($prof_query);
$student_result = $conn->query($student_query);
$visitor_result = $conn->query($visitor_query);

// Récupération des résultats
$prof_count = $prof_result->fetch_assoc()['total'];
$student_count = $student_result->fetch_assoc()['total'];
$visitor_count = $visitor_result->fetch_assoc()['total'];

?>