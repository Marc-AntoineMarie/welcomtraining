<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.2.0/css/all.css'>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="./login.css">
  <title>Connexion</title> 
</head>
<body>

<?php
session_start(); // Démarrer la session au tout début

require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Préparation de la requête pour récupérer le mot de passe, l'état et l'ID de l'utilisateur
    $stmt = $conn->prepare("SELECT iduser, Mdp, etat FROM user WHERE Mail = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Vérifie si un utilisateur avec cet email existe
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password, $etat);
        $stmt->fetch();

        // Vérifie le mot de passe
        if (password_verify($password, $hashed_password)) {
            // Stocke l'ID, l'état et l'email de l'utilisateur dans la session
            $_SESSION['iduser'] = $user_id;
            $_SESSION['etat'] = $etat; 
            $_SESSION['email'] = $email; 

            // Redirection en fonction de l'état
            if ($etat === 'admin') {
                header("Location: ../adminboots/admin.php");
                exit();
            } else {
                header("Location: ../dashboots/dashboard.php");
                exit();
            }
        } else {
            echo "Mot de passe incorrect.";
        }
    } else {
        echo "Aucun compte trouvé avec cet email.";
    }

    $stmt->close();
}

$conn->close();
?> 

<div class="container">
    <div class="screen">
        <div class="screen__content">
            <form class="login" action="login.php" method="POST"> <!-- Assurez-vous que le fichier s'appelle login.php -->
                <div class="login__field mb-3">
                    <i class="login__icon fas fa-envelope"></i>
                    <input type="email" name="email" class="login__input form-control" placeholder="E-mail" required>
                </div>
                <div class="login__field mb-3">
                    <i class="login__icon fas fa-lock"></i>
                    <input type="password" name="password" class="login__input form-control" placeholder="Mot de passe" required>
                </div>
                <button type="submit" class="button login__submit btn btn-primary">
                    <span class="button__text">Connexion</span>
                    <i class="button__icon fas fa-chevron-right"></i>
                </button>                
            </form>
        </div>
        <div class="screen__background">
            <span class="screen__background__shape screen__background__shape4"></span>
            <span class="screen__background__shape screen__background__shape3"></span>        
            <span class="screen__background__shape screen__background__shape2"></span>
            <span class="screen__background__shape screen__background__shape1"></span>
        </div>        
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
