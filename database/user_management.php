<?php

// Récupérer la liste des utilisateurs
$usersResult = $conn->query("SELECT user.iduser, user.identifiant, user.mail, user.classe_ID_classe, user.etat, classe.Nom AS class_name 
FROM user 
LEFT JOIN classe ON user.classe_ID_classe = classe.ID_classe
ORDER BY user.classe_ID_classe ASC");

// Récupérer les utilisateurs par rôle
$teachers_result = $conn->query("SELECT iduser, identifiant, mail, classe_ID_classe, etat FROM user WHERE etat = 'teacher'");
$students_result = $conn->query("SELECT iduser, identifiant, mail, classe_ID_classe, etat FROM user WHERE etat = 'student'");
$visitors_result = $conn->query("SELECT iduser, identifiant, mail, classe_ID_classe, etat FROM user WHERE etat = 'visitor'");
$admins_result = $conn->query("SELECT iduser, identifiant, mail, classe_ID_classe, etat FROM user WHERE etat = 'admin'");

// Récupérer les classes pour le champ de sélection
$classes_result = $conn->query("SELECT ID_classe, Nom FROM classe");

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

// Fonction pour mettre à jour la classe d'un utilisateur
function updateUserClass($conn, $userId, $newClassId) {
    $stmt = $conn->prepare("UPDATE user SET classe_ID_classe = ? WHERE iduser = ?");
    $stmt->bind_param("ii", $newClassId, $userId);
    
    return $stmt->execute() ? true : false;
}

// Fonction pour ajouter une nouvelle classe
function addClass($conn, $className) {
    $stmt = $conn->prepare("INSERT INTO classe (Nom) VALUES (?)");
    $stmt->bind_param("s", $className);
    
    return $stmt->execute() ? true : false;
}

// Traitement des requêtes POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Mise à jour de la classe
        if ($action === 'update_class') {
            $userId = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
            $newClassId = isset($_POST['new_class_id']) ? intval($_POST['new_class_id']) : 0;

            if ($userId > 0 && $newClassId > 0) {
                if (updateUserClass($conn, $userId, $newClassId)) {
                    $_SESSION['message'] = "Classe de l'utilisateur mise à jour avec succès.";
                } else {
                    $_SESSION['message'] = "Erreur lors de la mise à jour de la classe.";
                }
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
        }

        // Ajout de classe
        elseif ($action === 'add_class') {
            $className = $_POST['class_name'] ?? '';
            if (!empty($className) && addClass($conn, $className)) {
                $_SESSION['message'] = "Classe ajoutée avec succès.";
            } else {
                $_SESSION['message'] = "Erreur lors de l'ajout de la classe.";
            }
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
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
?>