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

// Traitement du formulaire d'ajout d'utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'add_user') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $etat = $_POST['etat'];
    $password = $_POST['password'];
    $classe_ID_classe = $_POST['classe_ID_classe'];

    // Hashage du mot de passe
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Préparation de la requête d'insertion
    $stmt = $conn->prepare("INSERT INTO user (identifiant, mail, etat, mdp, classe_ID_classe) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $name, $email, $etat, $hashed_password, $classe_ID_classe);

    if ($stmt->execute()) {
        // Enregistrer le message de succès dans la session
        $_SESSION['message'] = "Utilisateur ajouté avec succès.";
    } else {
        $_SESSION['message'] = "Erreur lors de l'ajout de l'utilisateur.";
    }

    $stmt->close();

    // Rediriger vers la même page pour éviter le double envoi
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Traitement de la requête de suppression d'utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'delete_user') {
    $user_id = $_POST['user_id'];

    // Préparation de la requête de suppression
    $stmt = $conn->prepare("DELETE FROM user WHERE iduser = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Utilisateur supprimé avec succès.";
    } else {
        $_SESSION['message'] = "Erreur lors de la suppression de l'utilisateur.";
    }

    $stmt->close();

    // Rediriger vers la même page pour éviter le double envoi
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Traitement de la mise à jour de la classe d'un utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'update_class') {
    $user_id = $_POST['user_id'];
    $new_class_id = $_POST['new_class_id'];

    // Préparation de la requête de mise à jour
    $stmt = $conn->prepare("UPDATE user SET classe_ID_classe = ? WHERE iduser = ? AND etat != 'admin'");
    $stmt->bind_param("ii", $new_class_id, $user_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Classe mise à jour avec succès.";
    } else {
        $_SESSION['message'] = "Erreur lors de la mise à jour de la classe.";
    }

    $stmt->close();

    // Rediriger vers la même page pour éviter le double envoi
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Traitement de la mise à jour de l'état d'un utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'update_state') {
    $user_id = $_POST['user_id'];
    $new_state = $_POST['new_state'];

    // Préparation de la requête de mise à jour
    $stmt = $conn->prepare("UPDATE user SET etat = ? WHERE iduser = ? AND etat != 'admin'");
    $stmt->bind_param("si", $new_state, $user_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "État mis à jour avec succès.";
    } else {
        $_SESSION['message'] = "Erreur lors de la mise à jour de l'état.";
    }

    $stmt->close();

    // Rediriger vers la même page pour éviter le double envoi
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Récupérer les classes pour le champ de sélection
$classes_result = $conn->query("SELECT ID_classe, Nom FROM classe");

// Récupérer les utilisateurs par rôle
$teachers_result = $conn->query("SELECT iduser, identifiant, mail, classe_ID_classe, etat FROM user WHERE etat = 'teacher'");
$students_result = $conn->query("SELECT iduser, identifiant, mail, classe_ID_classe, etat FROM user WHERE etat = 'student'");
$visitors_result = $conn->query("SELECT iduser, identifiant, mail, classe_ID_classe, etat FROM user WHERE etat = 'visitor'");
$admins_result = $conn->query("SELECT iduser, identifiant, mail, classe_ID_classe, etat FROM user WHERE etat = 'admin'");
?>

<!-- Afficher le message de session -->
<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-info"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
<?php endif; ?>

<!-- Main Content -->
<div class="container mt-5">

  <!-- Tableaux pour chaque type d'utilisateur -->
  <h2>Liste des Utilisateurs</h2>

  <h3>Professeurs</h3>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Email</th>
        <th>Classe</th>
        <th>État</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($user = $teachers_result->fetch_assoc()): ?>
          <tr>
              <td><?php echo $user['iduser']; ?></td>
              <td><?php echo $user['identifiant']; ?></td>
              <td><?php echo $user['mail']; ?></td>
              <td>
                  <form method="POST" action="" style="display:inline;">
                      <input type="hidden" name="user_id" value="<?php echo $user['iduser']; ?>">
                      <input type="hidden" name="action" value="update_class">
                      <select name="new_class_id" onchange="this.form.submit()" class="form-select form-select-sm">
                          <?php
                          // Réinitialiser le pointeur du résultat des classes
                          $classes_result->data_seek(0); // Revenir au début du résultat
                          while ($classe = $classes_result->fetch_assoc()): ?>
                              <option value="<?php echo $classe['ID_classe']; ?>" <?php if($classe['ID_classe'] == $user['classe_ID_classe']) echo 'selected'; ?>><?php echo $classe['Nom']; ?></option>
                          <?php endwhile; ?>
                      </select>
                  </form>
              </td>
              <td>
                  <form method="POST" action="" style="display:inline;">
                      <input type="hidden" name="user_id" value="<?php echo $user['iduser']; ?>">
                      <input type="hidden" name="action" value="update_state">
                      <select name="new_state" onchange="this.form.submit()" class="form-select form-select-sm">
                          <option value="teacher" <?php if ($user['etat'] == 'teacher') echo 'selected'; ?>>Professeur</option>
                          <option value="student" <?php if ($user['etat'] == 'student') echo 'selected'; ?>>Étudiant</option>
                          <option value="visitor" <?php if ($user['etat'] == 'visitor') echo 'selected'; ?>>Visiteur</option>
                      </select>
                  </form>
              </td>
              <td>
                  <form method="POST" action="" style="display:inline;">
                      <input type="hidden" name="user_id" value="<?php echo $user['iduser']; ?>">
                      <input type="hidden" name="action" value="delete_user">
                      <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                  </form>
              </td>
          </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <h3>Étudiants</h3>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Email</th>
        <th>Classe</th>
        <th>État</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($user = $students_result->fetch_assoc()): ?>
          <tr>
              <td><?php echo $user['iduser']; ?></td>
              <td><?php echo $user['identifiant']; ?></td>
              <td><?php echo $user['mail']; ?></td>
              <td>
                  <form method="POST" action="" style="display:inline;">
                      <input type="hidden" name="user_id" value="<?php echo $user['iduser']; ?>">
                      <input type="hidden" name="action" value="update_class">
                      <select name="new_class_id" onchange="this.form.submit()" class="form-select form-select-sm">
                          <?php
                          // Réinitialiser le pointeur du résultat des classes
                          $classes_result->data_seek(0); // Revenir au début du résultat
                          while ($classe = $classes_result->fetch_assoc()): ?>
                              <option value="<?php echo $classe['ID_classe']; ?>" <?php if($classe['ID_classe'] == $user['classe_ID_classe']) echo 'selected'; ?>><?php echo $classe['Nom']; ?></option>
                          <?php endwhile; ?>
                      </select>
                  </form>
              </td>
              <td>
                  <form method="POST" action="" style="display:inline;">
                      <input type="hidden" name="user_id" value="<?php echo $user['iduser']; ?>">
                      <input type="hidden" name="action" value="update_state">
                      <select name="new_state" onchange="this.form.submit()" class="form-select form-select-sm">
                          <option value="teacher" <?php if ($user['etat'] == 'teacher') echo 'selected'; ?>>Professeur</option>
                          <option value="student" <?php if ($user['etat'] == 'student') echo 'selected'; ?>>Étudiant</option>
                          <option value="visitor" <?php if ($user['etat'] == 'visitor') echo 'selected'; ?>>Visiteur</option>
                      </select>
                  </form>
              </td>
              <td>
                  <form method="POST" action="" style="display:inline;">
                      <input type="hidden" name="user_id" value="<?php echo $user['iduser']; ?>">
                      <input type="hidden" name="action" value="delete_user">
                      <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                  </form>
              </td>
          </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <h3>Visiteurs</h3>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Email</th>
        <th>Classe</th>
        <th>État</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($user = $visitors_result->fetch_assoc()): ?>
          <tr>
              <td><?php echo $user['iduser']; ?></td>
              <td><?php echo $user['identifiant']; ?></td>
              <td><?php echo $user['mail']; ?></td>
              <td>
                  <form method="POST" action="" style="display:inline;">
                      <input type="hidden" name="user_id" value="<?php echo $user['iduser']; ?>">
                      <input type="hidden" name="action" value="update_class">
                      <select name="new_class_id" onchange="this.form.submit()" class="form-select form-select-sm">
                          <?php
                          // Réinitialiser le pointeur du résultat des classes
                          $classes_result->data_seek(0); // Revenir au début du résultat
                          while ($classe = $classes_result->fetch_assoc()): ?>
                              <option value="<?php echo $classe['ID_classe']; ?>" <?php if($classe['ID_classe'] == $user['classe_ID_classe']) echo 'selected'; ?>><?php echo $classe['Nom']; ?></option>
                          <?php endwhile; ?>
                      </select>
                  </form>
              </td>
              <td>
                  <form method="POST" action="" style="display:inline;">
                      <input type="hidden" name="user_id" value="<?php echo $user['iduser']; ?>">
                      <input type="hidden" name="action" value="update_state">
                      <select name="new_state" onchange="this.form.submit()" class="form-select form-select-sm">
                          <option value="teacher" <?php if ($user['etat'] == 'teacher') echo 'selected'; ?>>Professeur</option>
                          <option value="student" <?php if ($user['etat'] == 'student') echo 'selected'; ?>>Étudiant</option>
                          <option value="visitor" <?php if ($user['etat'] == 'visitor') echo 'selected'; ?>>Visiteur</option>
                      </select>
                  </form>
              </td>
              <td>
                  <form method="POST" action="" style="display:inline;">
                      <input type="hidden" name="user_id" value="<?php echo $user['iduser']; ?>">
                      <input type="hidden" name="action" value="delete_user">
                      <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                  </form>
              </td>
          </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <h3>Administrateurs</h3>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Email</th>
        <th>Classe</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($user = $admins_result->fetch_assoc()): ?>
          <tr>
              <td><?php echo $user['iduser']; ?></td>
              <td><?php echo $user['identifiant']; ?></td>
              <td><?php echo $user['mail']; ?></td>
              <td>
                  <?php echo $user['classe_ID_classe']; ?>
              </td>
              <td>
                  <form method="POST" action="" style="display:inline;">
                      <input type="hidden" name="user_id" value="<?php echo $user['iduser']; ?>">
                      <input type="hidden" name="action" value="delete_user">
                      <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                  </form>
                  <span class="text-muted">Non modifiable</span>
              </td>
          </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <!-- Formulaire d'ajout d'utilisateur -->
  <h3>Ajouter un Utilisateur</h3>
  <form method="POST" action="">
    <div class="mb-3">
      <label for="name" class="form-label">Nom</label>
      <input type="text" class="form-control" name="name" required>
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" name="email" required>
    </div>
    <div class="mb-3">
      <label for="etat" class="form-label">État</label>
      <select name="etat" class="form-select" required>
        <option value="teacher">Professeur</option>
        <option value="student">Étudiant</option>
        <option value="visitor">Visiteur</option>
        <option value="admin">Administrateur</option>
      </select>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Mot de passe</label>
      <input type="password" class="form-control" name="password" required>
    </div>
    <div class="mb-3">
      <label for="classe_ID_classe" class="form-label">Classe</label>
      <select name="classe_ID_classe" class="form-select" required>
          <?php
          $classes_result->data_seek(0); 
          while ($classe = $classes_result->fetch_assoc()): ?>
              <option value="<?php echo $classe['ID_classe']; ?>"><?php echo $classe['Nom']; ?></option>
          <?php endwhile; ?>
      </select>
    </div>
    <button type="submit" name="action" value="add_user" class="btn btn-primary">Ajouter l'utilisateur</button>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
