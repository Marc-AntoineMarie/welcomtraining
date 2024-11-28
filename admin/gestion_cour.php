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
  <?php include 'navbar.php'; ?>
</header>

<?php
require 'db.php';

// Récupérer les cours pour l'affichage

    $cours_result = $conn->query("
    SELECT c.ID_classe, c.Nom, co.name AS Nom_cours, co.horaire, co.date, u.Identifiant AS prof_nom
    FROM classe c
    JOIN cour co ON c.ID_classe = co.ID_classe
    JOIN user u ON co.prof = u.iduser
");

// Vérification pour s'assurer que la requête est correcte
if ($cours_result === false) {
    die("Erreur dans la requête : " . $conn->error);
}
?>

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
  
  while ($row = $cours_result->fetch_assoc()) {
      $classes[$row['ID_classe']]['Nom'] = $row['Nom'];
      $classes[$row['ID_classe']]['Cours'][] = [
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
      <?php foreach ($classe['Cours'] as $cours): ?>
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
