<?php 
require 'Session.php';
include 'cour_request.php';

$formManager = new ShowCourForm($pdo);
$cours_data = $formManager->SubjectRecovery();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ajouter un Cours</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="create_cour.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2>Ajouter un Nouveau Cours</h2>
  
  <form action="" method="POST" class="mt-4">
    <input type="hidden" name="action" value="add_course">
    <div class="mb-3">
      <label for="name" class="form-label">Nom du cours</label>
      <select class="form-control" id="name" name="name" required>
        <option value="">Sélectionner un cour</option>
        <?php
        if ($cours_data) {
            while($row = $cours_data->fetch_assoc()) {
                echo '<option value="' . htmlspecialchars($row['Nom_cours']) . '">' . htmlspecialchars($row['Nom_cours']) . '</option>';

            }
        } else {
            echo '<option value="">Aucun cour n est disponible</option>';
        }
      //   if (!empty($cours_data)) {
      //     foreach ($cours_data as $row) {
      //         echo '<option value="' . htmlspecialchars($row['Nom_cours']) . '">' . htmlspecialchars($row['Nom_cours']) . '</option>';
      //     }
      // } else {
      //     echo '<option value="">Aucun cours disponible</option>';
      // }
        ?>
      </select>
    </div>
  
    <div class="mb-3">
      <label for="horaire" class="form-label">Horaire</label>
      <input type="text" class="form-control" id="horaire" name="horaire" required>
    </div>

    <div class="mb-3">
      <label for="date" class="form-label">Date</label>
      <input type="date" class="form-control" id="date" name="date" required>
    </div>

    <div class="mb-3">
      <label for="classe_ID_classe" class="form-label">Classe</label>
      <select class="form-control" id="classe_ID_classe" name="classe_ID_classe" required>
        <option value="">Sélectionnez une classe</option>
        <?php
        if ($classes_result->num_rows > 0) {
            while($row = $classes_result->fetch_assoc()) {
                echo '<option value="' . $row['ID_classe'] . '">' . htmlspecialchars($row['Nom']) . '</option>';
            }
        } else {
            echo '<option value="">Aucune classe disponible</option>';
        }
        ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="prof" class="form-label">Prof</label>
      <select class="form-control" id="prof" name="prof" required>
        <option value="">Sélectionnez un professeur</option>
        <?php
        if ($prof_result->num_rows > 0) {
            while($row = $prof_result->fetch_assoc()) {
              // echo '<option value="' . $row['ID_user'] . '">' . htmlspecialchars($row['Identifiant']) . '</option>';
              echo '<option value="' . htmlspecialchars($row['iduser']) . '">' . htmlspecialchars($row['Identifiant']) . '</option>';
            }
        } else {
            echo '<option value="">Aucun prof n est disponible</option>';
        }
        ?>
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Ajouter le Cours</button>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
