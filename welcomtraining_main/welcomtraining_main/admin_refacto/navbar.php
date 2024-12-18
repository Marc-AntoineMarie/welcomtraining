<?php 

include("../database/Session.php");

?>

<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="admin.php">Admin Dashboard</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="user_management.php">Gestion des Utilisateurs</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="classe.php">Classe</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="gestion_cour.php">Cours</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="gestion_sign.php">Signature</a>
        </li>
        <li class="nav-item">
          <form action="" method="POST" style="display: inline;">
            <button type="submit" name="Déconnexion" class="btn btn-link" style="background: none; border: none; color: white; text-decoration: none; cursor: pointer">Déconnexion</button>
          </form>
        </li>
      </ul>
    </div>
  </div>
</nav>

<style>  
.navbar {
    background-color: #6a4ec4 !important; 
}
  
.navbar-brand, .nav-link {
    color: white !important; 
}
.navbar-nav .nav-link:hover {
    color: #ddd !important; 
}
  
.navbar-toggler-icon {
    background-color: white; 
}
</style>
