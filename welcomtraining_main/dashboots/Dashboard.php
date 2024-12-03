<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
<header>
    <?php include 'navbar.php'; ?>
</header>
<?php
require 'db.php';

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['iduser'])) {
    die("Aucun utilisateur connecté.");
}

$user_id = $_SESSION['iduser'];

// Récupérer l'ID de la classe de l'utilisateur connecté
$sql_classe = "SELECT classe_ID_classe FROM user WHERE iduser = ?";
$stmt_classe = $conn->prepare($sql_classe);

if (!$stmt_classe) {
    die("Erreur dans la préparation de la requête : " . $conn->error);
}

$stmt_classe->bind_param("i", $user_id);
$stmt_classe->execute();
$result_classe = $stmt_classe->get_result();

if ($result_classe->num_rows === 0) {
    die("Impossible de trouver la classe de l'utilisateur.");
}

$user_data = $result_classe->fetch_assoc();
$id_classe = $user_data['classe_ID_classe'];

// Récupérer les cours liés à cette classe
$sql_cours = "
    SELECT c.ID_classe, c.Nom AS Nom_classe, co.name AS Nom_cours, co.horaire, co.date, u.Identifiant AS prof_nom
    FROM classe c
    JOIN cour co ON c.ID_classe = co.ID_classe
    JOIN user u ON co.prof = u.iduser
    WHERE c.ID_classe = ?
";

$stmt_cours = $conn->prepare($sql_cours);

if (!$stmt_cours) {
    die("Erreur dans la préparation de la requête : " . $conn->error);
}

$stmt_cours->bind_param("i", $id_classe);
$stmt_cours->execute();
$result_cours = $stmt_cours->get_result();

// Regrouper les cours par classe
$classes = [];
while ($row = $result_cours->fetch_assoc()) {
    $classes[$row['ID_classe']]['Nom'] = $row['Nom_classe'];
    $classes[$row['ID_classe']]['Cours'][] = [
        'Nom_cours' => $row['Nom_cours'],
        'date' => $row['date'],
        'horaire' => $row['horaire'],
        'prof' => $row['prof_nom'],
    ];
}
?>
<div class="container mt-5">
    <div class="header-info text-center">
        <h2 id="date"><?php echo date('d/m/Y'); ?></h2>
    </div>

    <h1 class="text-center my-4">Vos Cours par Classe</h1>

    <?php if (empty($classes)): ?>
        <p class="text-center">Aucun cours trouvé pour votre classe.</p>
    <?php else: ?>
        <?php foreach ($classes as $id_classe => $classe): ?>
            <h4><?php echo htmlspecialchars($classe['Nom']); ?></h4>
            <table class="table table-striped mb-4">
                <thead>
                <tr>
                    <th>Nom du cours</th>
                    <th>Date</th>
                    <th>Horaire</th>
                    <th>Professeur</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($classe['Cours'] as $cours): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cours['Nom_cours']); ?></td>
                        <td><?php echo htmlspecialchars($cours['date']); ?></td>
                        <td><?php echo htmlspecialchars($cours['horaire']); ?></td>
                        <td><?php echo htmlspecialchars($cours['prof']); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
