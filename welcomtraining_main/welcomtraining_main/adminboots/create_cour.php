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


  <!-- probleme de fou il m'affiche l'id au lieu du nom du prof ou de la matiere -->


<?php 
require 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'add_course') {
    $name = $_POST['name'];
    $horaire = $_POST['horaire'];
    $date = $_POST['date'];
    $classe_id = $_POST['classe_ID_classe'];
    $prof = $_POST['prof'];

    // Préparation de la requête d'insertion
    $stmt = $conn->prepare("INSERT INTO cour (name, horaire, date, ID_classe, prof) VALUES (?, ?, ?, ?, ?)");
    
    // Vérification si la préparation a réussi
    if ($stmt === false) {
        die("Erreur de préparation: " . $conn->error);
    }

    $stmt->bind_param("ssssi", $name, $horaire, $date, $classe_id, $prof);

    if ($stmt->execute()) {
        // Redirection vers gestion_cour.php après l'ajout réussi
        header("Location: gestion_cour.php?success=1");
        exit; // Arrête le script après redirection
    } else {
        echo "<div class='alert alert-danger'>Erreur lors de l'ajout du cours: " . $stmt->error . "</div>";
        exit; // Arrête le script si une erreur se produit
    }

    $stmt->close();
}

// Récupération des classes pour le menu déroulant
$classes_result = $conn->query("SELECT ID_classe, Nom FROM classe");

// Récupération des profs pour le menu déroulant
$prof_result = $conn->query("SELECT iduser, Identifiant FROM user WHERE Etat = 'teacher'");

// Récupération des matieres pour le menu déroulant
$cour_result = $conn->query("SELECT ID_matiere, nom_matiere FROM matiere");

?>

<div class="container mt-5">
  <h2>Ajouter un Nouveau Cours</h2>
  
  <form action="" method="POST" class="mt-4">
    <input type="hidden" name="action" value="add_course">
    <div class="mb-3">
      <label for="name" class="form-label">Nom du cours</label>
      <select class="form-control" id="name" name="name" required>
        <option value="">Sélectionner un cour</option>
        <?php
        if ($cour_result->num_rows > 0) {
            while($row = $cour_result->fetch_assoc()) {
                echo '<option value="' . htmlspecialchars($row['nom_matiere']) . '">' . htmlspecialchars($row['nom_matiere']) . '</option>';

            }
        } else {
            echo '<option value="">Aucun cour n est disponible</option>';
        }
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
