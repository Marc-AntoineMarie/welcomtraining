<?php 
// Requêtes pour compter les utilisateurs par rôle
$prof_query = "SELECT COUNT(*) AS total FROM user WHERE etat = 'teacher'";
$student_query = "SELECT COUNT(*) AS total FROM user WHERE etat = 'student'";
$visitor_query = "SELECT COUNT(*) AS total FROM user WHERE etat = 'visitor'";

// Exécution des requêtes
$profs_result = $conn->query($prof_query);
$students_result = $conn->query($student_query);
$visitors_result = $conn->query($visitor_query);

// Récupération des résultats
$prof_count = $profs_result->fetch_assoc()['total'];
$student_count = $students_result->fetch_assoc()['total'];
$visitor_count = $visitors_result->fetch_assoc()['total'];

?>