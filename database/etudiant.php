<?php 
require 'Session.php';
require 'user_management.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Étudiants</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="etudiant.css">
</head>
<body>

<header>
  <?php include 'admin_header.php'; ?>
</header>

<!-- Afficher le message de session -->
<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-info"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
<?php endif; ?>

<!-- Main Content -->
<div class="container mt-5">

  <!-- Tableaux pour les utilisateurs étudiants -->
  <h2>Liste des Étudiants</h2>

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

  <!-- Formulaire d'ajout d'utilisateur -->
  <h3>Ajouter un Étudiant</h3>
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
      <label for="etat" class="form-label" style="color: gray; opacity: 95%">État</label>
        <option value="student" style="color: gray; opacity: 95%">Étudiant</option>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Mot de Passe</label>
      <input type="password" class="form-control" name="password" required>
    </div>
    <div class="mb-3">
      <label for="classe_ID_classe" class="form-label">Classe</label>
      <select name="classe_ID_classe" class="form-select" required>
        <?php
        $classes_result->data_seek(0); // Revenir au début du résultat
        while ($classe = $classes_result->fetch_assoc()): ?>
            <option value="<?php echo $classe['ID_classe']; ?>"><?php echo $classe['Nom']; ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <button type="submit" name="action" value="add_user" class="btn btn-primary">Ajouter Étudiant</button>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
