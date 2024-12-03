<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.2.0/css/all.css'>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="./register.css">
  <title>Inscription</title>
</head>
<body> 

<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les valeurs du formulaire
    $email = $_POST['email'];
    $username = $_POST['username'];
    $pass = $_POST['password'];
    $password = password_hash($pass, PASSWORD_DEFAULT); // Hash du mot de passe
    $classe_ID_classe = $_POST['classe_ID_classe'];  // Récupérer l'ID de la classe

    // Vérifier si l'email existe déjà
    $stmt = $conn->prepare("SELECT * FROM user WHERE Mail = ? OR Identifiant = ?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<div class='alert alert-danger'>L'email ou l'identifiant existe déjà. Veuillez en choisir un autre.</div>";
    } else {
        // Préparer la requête pour insérer dans la table user avec la clé étrangère classe_ID_classe
        $stmt = $conn->prepare("INSERT INTO user (Mail, Identifiant, mdp, classe_ID_classe) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $email, $username, $password, $classe_ID_classe);

        // Exécuter la requête et gérer les erreurs
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Inscription réussie !</div>";
        } else {
            echo "<div class='alert alert-danger'>Erreur : " . $stmt->error . "</div>";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<div class="container">
    <div class="screen">
        <div class="screen__content">
            <form class="login" action="register.php" method="POST">
                <div class="login__field mb-3">
                    <i class="login__icon fas fa-envelope"></i>
                    <input type="email" name="email" class="login__input form-control" placeholder="Créer un e-mail" required>
                </div>
                <div class="login__field mb-3">
                    <i class="login__icon fas fa-user"></i>
                    <input type="text" name="username" class="login__input form-control" placeholder="Créer un identifiant" required>
                </div>
                <div class="login__field mb-3">
                    <i class="login__icon fas fa-lock"></i>
                    <input type="password" name="password" class="login__input form-control" placeholder="Créer un mot de passe" required>
                </div>
                <div class="login__field mb-3">
                    <i class="login__icon fas fa-graduation-cap"></i>
                    <select name="classe_ID_classe" class="login__input form-control" required>
                        <option value="" disabled selected>Choisir une classe</option>
                        <option value="1">Classe 1</option>
                        <option value="2">Classe 2</option>
                        <option value="3">Classe 3</option>
                    </select>
                </div>
                <button type="submit" class="button login__submit btn btn-primary">
                    <span class="button__text">S'inscrire</span>
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
