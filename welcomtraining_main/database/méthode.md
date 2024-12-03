# Transformation de Code Procédural en Orienté Objet

## Étape 1 : Identifier les responsabilités

1. **Responsabilité principale** : Identifier la logique métier. Ici, il s'agit du traitement du formulaire d'ajout d'utilisateur.
2. **Regrouper les responsabilités** : Déterminer quelles parties du code appartiennent ensemble :
   - Gestion des utilisateurs.
   - Connexion à la base de données.
   - Gestion des erreurs et des messages.

---

## Étape 2 : Créer une classe dédiée

1. **Créez une classe** pour gérer les utilisateurs, par exemple `User_management`.
2. **Définissez un constructeur** pour injecter la connexion à la base de données via un objet `PDO`.

### Exemple de classe `User_management`

```php
class User_management {

    private $pdo;

    // Constructeur pour injecter la connexion à la base de données
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Méthode pour ajouter un utilisateur
    public function addUser($name, $email, $etat, $password, $classe_ID_classe) {
        try {
            // Hashage du mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Préparation de la requête
            $stmt = $this->pdo->prepare(
                "INSERT INTO user (identifiant, mail, etat, mdp, classe_ID_classe) VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->execute([$name, $email, $etat, $hashed_password, $classe_ID_classe]);

            return "Utilisateur ajouté avec succès.";
        } catch (PDOException $e) {
            return "Erreur lors de l'ajout de l'utilisateur : " . $e->getMessage();
        }
    }
}
```

---

## Étape 3 : Remplacer la logique procédurale

### Avant (Code procédural)

```php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'add_user') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $etat = $_POST['etat'];
    $password = $_POST['password'];
    $classe_ID_classe = $_POST['classe_ID_classe'];

    // Hashage du mot de passe
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Préparation de la requête d'insertion
    $stmt = $conn->prepare(
        "INSERT INTO user (identifiant, mail, etat, mdp, classe_ID_classe) VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("ssssi", $name, $email, $etat, $hashed_password, $classe_ID_classe);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Utilisateur ajouté avec succès.";
    } else {
        $_SESSION['message'] = "Erreur lors de l'ajout de l'utilisateur.";
    }

    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
```

### Après (Code orienté objet)

```php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'add_user') {
    // Instancier la classe User_management
    $UserManager = new User_management($pdo);

    // Appeler la méthode pour ajouter un utilisateur
    $message = $UserManager->addUser(
        $_POST['name'],
        $_POST['email'],
        $_POST['etat'],
        $_POST['password'],
        $_POST['classe_ID_classe']
    );

    // Stocker le message dans la session
    $_SESSION['message'] = $message;

    // Redirection
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
```

---

## Étape 4 : Avantages de cette méthode

### 1. **Réutilisabilité**
- La méthode `addUser` peut être réutilisée dans différents contextes sans dupliquer le code.

### 2. **Lisibilité**
- Le code devient plus propre et plus facile à comprendre.

### 3. **Maintenance**
- Toute modification future est limitée à une seule méthode de classe.

### 4. **Modularité**
- La logique de gestion des utilisateurs est encapsulée dans une classe dédiée.

---

## Étape 5 : Étendre cette méthode

Pour ajouter d'autres fonctionnalités (comme la suppression ou la mise à jour d'un utilisateur), procédez de manière similaire :

1. **Créez une méthode** dans la classe `User_management`, comme `deleteUser` ou `updateUser`.
2. **Passez les paramètres nécessaires** et exécutez les requêtes dans ces méthodes.

### Exemple de méthode pour supprimer un utilisateur

```php
public function deleteUser($userId) {
    try {
        $stmt = $this->pdo->prepare("DELETE FROM user WHERE id = ?");
        $stmt->execute([$userId]);

        return "Utilisateur supprimé avec succès.";
    } catch (PDOException $e) {
        return "Erreur lors de la suppression de l'utilisateur : " . $e->getMessage();
    }
}
```

---

## Résumé de la méthode

1. **Créer une classe dédiée** : Regroupez la logique par responsabilités.
2. **Encapsuler la logique dans des méthodes** : Une méthode pour chaque opération (ajout, suppression, mise à jour).
3. **Réutilisation** : Instanciez la classe dans votre code principal et appelez les méthodes selon le besoin.
4. **Centralisation** : Toute la logique métier est centralisée et maintenable.

---

Cette méthodologie peut être appliquée à tout type de projet nécessitant une gestion plus organisée et modulaire du code.