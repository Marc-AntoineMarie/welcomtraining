<?php 
require 'Session.php';
include 'user_management.php';
?>
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
    <?php include 'admin_header.php'; ?> 
</header>                                   

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
                                $classes_result->data_seek(0);
                                while ($classe = $classes_result->fetch_assoc()): ?>
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
