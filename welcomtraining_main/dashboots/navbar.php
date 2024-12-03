<?php 
session_start();

if (!isset($_SESSION['iduser'])) {
    // L'utilisateur n'est pas connecté
    header("Location: ../login/login.php");
    exit();
}

if(isset($_POST['Déconnexion'])) {
    session_destroy();
    header("Location: ../login/login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.2.0/css/all.css'>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400,700&display=swap">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="./nav.css">
</head>
<body>
<header class="header_section">
  <div class="container-fluid">
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top custom_nav-container">
      <a class="navbar-brand" href="index.php">
        <span>Welcome Training</span>
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
          <!-- Menu de l'étudiant -->
          <li class="nav-item active">
            <a class="nav-link" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="veille.php">Profil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="schedule.php">Schedule</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="attendance.php">Attendance</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="newsletter.php">Newsletter</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="admin_dashboard.php">Administration</a>
          </li>
          <!-- Bouton de déconnexion -->
          <li class="nav-item">
            <form action="" method="POST" style="display: inline;">
              <button type="submit" name="Déconnexion" class="btn btn-link" style="background: none; border: none; color: black; text-decoration: none; cursor: pointer;">Déconnexion</button>
            </form>
          </li>
        </ul>
      </div>
    </nav>
  </div>
</header>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
