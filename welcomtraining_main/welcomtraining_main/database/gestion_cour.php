<?php 
require 'Session.php';
include 'cour_request.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="user_management.css">
</head>
<body>

<header>
  <?php include 'admin_header.php'; ?>
</header>

<!-- Main Content -->
<div class="container mt-5">
  <h2>Liste des Cours par Classe</h2>

  <!-- Bouton pour créer un cours -->
  <div class="mb-3">
    <a href="create_cour.php" class="btn btn-primary">Créer un cours</a>
  </div>

  <?php

  // Tableau pour regrouper les cours par classe
  $classes = [];
  
  $formManager = new ShowCourForm($pdo);
  $cours_data = $formManager->TeacherRecovery();

  foreach ($cours_data as $row) {
    $classes[$row['ID_classe']]['Nom'] = $row['Nom'];
    $classes[$row['ID_classe']]['cours'][] = [
      'Nom_cours' => $row['Nom_cours'],
      'date' => $row['date'],
      'horaire' => $row['horaire'],
      'prof' => $row['prof_nom'],

    ];
  }

  ?>

<?php foreach ($classes as $id_classe => $classe): ?>
  <h4><?php echo $classe['Nom']; ?></h4>
  <table class="table table-striped mb-4">
    <thead>
      <tr>
        <th>Nom du cours</th>
        <th>Date</th>
        <th>Horaire</th>
        <th>Professeur</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($classe['cours'] as $cours): ?>
        <tr>
          <td><?php echo $cours['Nom_cours']; ?></td>
          <td><?php echo $cours['date']; ?></td>
          <td><?php echo $cours['horaire']; ?></td>
          <td><?php echo $cours['prof']; ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endforeach; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
