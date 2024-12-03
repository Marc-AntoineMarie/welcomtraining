<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="classe.css">
</head>
<body>

<header>
    <?php include 'navbar.php'; ?> 
</header> 

<?php
require 'db.php';

// Vérification de connexion utilisateur
if (!isset($_SESSION['iduser'])) {
    echo "<div class='alert alert-danger text-center'>Vous n'êtes pas connecté.</div>";
    header("Location: ../login/login.php");
    exit();
}

// Vérifier la connexion à la base de données
if ($conn->connect_error) {
    die("Erreur de connexion: " . $conn->connect_error);
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

// Récupérer la liste des classes
$classesResult = $conn->query("SELECT ID_classe, Nom FROM classe");

// Récupérer la liste des utilisateurs
$usersResult = $conn->query("SELECT user.iduser, user.identifiant, user.mail, user.classe_ID_classe, user.etat, classe.Nom AS class_name 
FROM user 
LEFT JOIN classe ON user.classe_ID_classe = classe.ID_classe
ORDER BY user.classe_ID_classe ASC");
?>

<!-- Afficher les messages de session -->
<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-info"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
<?php endif; ?>

<!-- Affichage du tableau d'utilisateurs avec modification de classe -->
<div class="container mt-5">
    <h2>Liste des Utilisateurs</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Classe</th>
                <th>État</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = $usersResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['iduser']); ?></td>
                    <td><?php echo htmlspecialchars($user['identifiant']); ?></td>
                    <td><?php echo htmlspecialchars($user['mail']); ?></td>
                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="user_id" value="<?php echo $user['iduser']; ?>">
                            <input type="hidden" name="action" value="update_class">
                            <select name="new_class_id" onchange="this.form.submit()" class="form-select form-select-sm">
                                <?php
                                // Reset le curseur des résultats des classes
                                $classesResult->data_seek(0);
                                while ($classe = $classesResult->fetch_assoc()): ?>
                                    <option value="<?php echo htmlspecialchars($classe['ID_classe']); ?>" <?php if($classe['ID_classe'] == $user['classe_ID_classe']) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($classe['Nom']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </form>
                    </td>
                    <td><?php echo ucfirst(htmlspecialchars($user['etat'])); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Formulaire d'ajout de classe -->
    <h3>Ajouter une Classe</h3>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="class_name" class="form-label">Nom de la Classe</label>
            <input type="text" class="form-control" id="class_name" name="class_name" required>
        </div>
        <input type="hidden" name="action" value="add_class">
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>

</body>
</html>
