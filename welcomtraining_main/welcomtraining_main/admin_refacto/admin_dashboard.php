<?php
require_once 'navbar.php';
require_once '../database/people_counter.php';
require_once '../database/user_management.php';

// Récupérer les données pour les filtres
$etat_filter = $_GET['etat'] ?? '';
$classe_filter = $_GET['classe'] ?? '';
$search_filter = $_GET['search'] ?? '';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>
    <h1 class="text-center mb-4" id="dashboard-title">Gestion des Utilisateurs</h1>
    <div class="container mb-3">
        <form method="GET" class="row g-3 align-items-center" id="filter-form">
            <!-- Filtre par État -->
            <div class="col-md-3">
            <select name="etat" id="filter-etat" class="form-select" aria-labelledby="filter-etat-label">
                <option value="">Filtrer par Etat</option>
                <?php foreach ($UniqueEtats as $etat): ?>
                    <option value="<?= htmlspecialchars($etat['Etat']) ?>" <?= $etat['Etat'] == $etat_filter ? 'selected' : '' ?>>
                        <?= htmlspecialchars($etat['Etat']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            </div>
            <!-- Filtre par Classe -->
            <div class="col-md-3">
                <select name="classe" id="filter-classe" class="form-select" aria-labelledby="filter-classe-label">
                    <option value="">Filtrer par Classe</option>
                    <?php foreach ($Class_data as $class): ?>
                        <option value="<?= $class['ID_classe'] ?>" <?= $class['ID_classe'] == $classe_filter ? 'selected' : '' ?>><?= htmlspecialchars($class['Nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- Recherche par Nom -->
            <div class="col-md-4">
                <input type="text" name="search" id="search-name" class="form-control" placeholder="Rechercher par Nom..." value="<?= htmlspecialchars($search_filter) ?>">
            </div>
            <!-- Bouton de soumission -->
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100" id="submit-button">Filtrer</button>
            </div>
        </form>
    </div>
    <div class="container mt-4">
        <!-- Tableau des utilisateurs -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered" id="user-table">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Identifiant</th>
                        <th>Email</th>
                        <th>État</th>
                        <th>Classe</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <?php foreach ($User_data as $user): ?>
                        <tr id="user-row-<?= $user['iduser'] ?>">
                            <td><?= $user['iduser'] ?></td>
                            <td><?= htmlspecialchars($user['Identifiant']) ?></td>
                            <td><?= htmlspecialchars($user['Mail']) ?></td>
                            <td><?= htmlspecialchars($user['Etat']) ?></td>
                            <td>
                                <?php
                                    // Recherche du nom de la classe à partir de l'ID_classe de l'utilisateur
                                    $class_name = '';
                                    foreach ($Class_data as $class) {
                                        if ($class['ID_classe'] == $user['classe_ID_classe']) {
                                            $class_name = $class['Nom'];
                                            break;
                                        }
                                    }
                                    echo htmlspecialchars($class_name);
                                ?>
                            </td>
                            <td>
                                <!-- Bouton de suppression -->
                                <form method="POST" class="d-inline" id="delete-user-form-<?= $user['iduser'] ?>">
                                    <input type="hidden" name="action" value="delete_user">
                                    <input type="hidden" name="user_id" value="<?= $user['iduser'] ?>" id="delete-user-id-<?= $user['iduser'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" id="delete-btn-<?= $user['iduser'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                        Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
