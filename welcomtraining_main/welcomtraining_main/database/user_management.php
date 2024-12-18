<?php

class UserManagement {

    private $pdo;
    private $UserRecoverySQLRequest = "SELECT * FROM user";

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Récupération des utilisateurs
    public function UserRecovery() {
        $stmt = $this->pdo->prepare($this->UserRecoverySQLRequest);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUsers($etat = '', $classe = '', $search = '') {
        $sql = "SELECT * FROM user WHERE 1";

        $params = [];
        if ($etat) {
            $sql .= " AND Etat = ?";
            $params[] = $etat;
        }

        if ($classe) {
            $sql .= " AND classe_ID_classe = ?";
            $params[] = $classe;
        }

        if ($search) {
            $sql .= " AND Identifiant LIKE ?";
            $params[] = "%" . $search . "%";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUniqueEtats() {
        $sql = "SELECT DISTINCT Etat FROM user";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClasses() {
        $sql = "SELECT * FROM classe";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // CRUD : Ajouter un utilisateur
    public function addUser($name, $email, $etat, $password, $classe_id_classe) {
        $sql = "INSERT INTO user (name, email, Etat, password, classe_ID_classe) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        if ($stmt->execute([$name, $email, $etat, password_hash($password, PASSWORD_BCRYPT), $classe_id_classe])) {
            return "Utilisateur ajouté avec succès.";
        }
        return "Erreur lors de l'ajout de l'utilisateur.";
    }

    // CRUD : Mettre à jour un utilisateur
    public function updateUser($user_id, $name, $email, $etat, $classe_id_classe) {
        $sql = "UPDATE user SET name = ?, email = ?, Etat = ?, classe_ID_classe = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        if ($stmt->execute([$name, $email, $etat, $classe_id_classe, $user_id])) {
            return "Utilisateur mis à jour avec succès.";
        }
        return "Erreur lors de la mise à jour de l'utilisateur.";
    }

    // CRUD : Supprimer un utilisateur
    public function deleteUser($user_id) {
        $sql = "DELETE FROM user WHERE iduser = ?";
        $stmt = $this->pdo->prepare($sql);
        if ($stmt->execute([$user_id])) {
            return "Utilisateur supprimé avec succès.";
        }
        return "Erreur lors de la suppression de l'utilisateur.";
    }

    public function handleFormSubmission() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $message = '';

            switch ($action) {
                case 'add_user':
                    $message = $this->addUser(
                        $_POST['name'],
                        $_POST['email'],
                        $_POST['etat'],
                        $_POST['password'],
                        $_POST['classe_id_classe']
                    );
                    break;

                case 'update_user':
                    $message = $this->updateUser(
                        intval($_POST['user_id']),
                        $_POST['name'],
                        $_POST['email'],
                        $_POST['etat'],
                        $_POST['classe_id_classe']
                    );
                    break;

                case 'delete_user':
                    $message = $this->deleteUser(intval($_POST['user_id']));
                    break;

                default:
                    $message = "Action inconnue.";
            }

            // Enregistrer le message dans la session et rediriger
            $_SESSION['message'] = $message;
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
}

// Initialisation de la classe
$UserManager = new UserManagement($pdo);
$UserManager->handleFormSubmission();

// Récupération des données pour affichage
$etat_filter = $_GET['etat'] ?? '';
$classe_filter = $_GET['classe'] ?? '';
$search_filter = $_GET['search'] ?? '';

$User_data = $UserManager->getUsers($etat_filter, $classe_filter, $search_filter);
$Class_data = $UserManager->getClasses();
$UniqueEtats = $UserManager->getUniqueEtats();

// Affichage d'un message si existant
if (isset($_SESSION['message'])) {
    echo "<div class='alert'>" . $_SESSION['message'] . "</div>";
    unset($_SESSION['message']);
}
?>
