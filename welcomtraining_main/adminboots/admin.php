<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <header>
        <?php include 'navbar.php';
        echo (session_id());?>
    </header>

    <main class="container mt-5 pt-5">

        <?php
        require 'db.php';

                // Vérifier si la session est ouverte
                if (!isset($_SESSION['iduser'])) {
                    echo "<div class='alert alert-danger text-center'>Vous n'êtes pas connecté.</div>";
                    header("Location: ../login/login.php");
                    exit();
                } else {
                    echo "<div class='alert alert-success text-center'>Session ouverte. Utilisateur connecté avec l'ID : " . $_SESSION['iduser'] . ".</div>";
                }

        if ($conn->connect_error) {
            die("Erreur de connexion: " . $conn->connect_error);
        }

        // Configuration de la pagination
        $logs_per_page = 10;  
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
        $offset = ($page - 1) * $logs_per_page;  

        // Requêtes pour compter les utilisateurs par rôle
        $prof_query = "SELECT COUNT(*) AS total FROM user WHERE etat = 'teacher'";
        $student_query = "SELECT COUNT(*) AS total FROM user WHERE etat = 'student'";
        $visitor_query = "SELECT COUNT(*) AS total FROM user WHERE etat = 'visitor'";

        // Exécution des requêtes
        $prof_result = $conn->query($prof_query);
        $student_result = $conn->query($student_query);
        $visitor_result = $conn->query($visitor_query);

        // Récupération des résultats
        $prof_count = $prof_result->fetch_assoc()['total'];
        $student_count = $student_result->fetch_assoc()['total'];
        $visitor_count = $visitor_result->fetch_assoc()['total'];

        // Récupérer le nombre total d'événements pour la pagination
        $total_logs_query = "SELECT COUNT(*) AS total_logs FROM logs";
        $total_logs_result = $conn->query($total_logs_query);
        $total_logs = $total_logs_result->fetch_assoc()['total_logs'];

        // Calcul du nombre total de pages
        $total_pages = ceil($total_logs / $logs_per_page);

        // Récupérer les événements en fonction de la page actuelle
        $log_query = "SELECT * FROM logs ORDER BY created_at DESC LIMIT $logs_per_page OFFSET $offset";
        $log_result = $conn->query($log_query);

        // Fermeture de la connexion
        $conn->close();
        ?>

        <section class="statistics row mb-4">
            <div class="col-lg-4 col-md-6 mb-3">
                <a href="professeurs.php" class="blob-btn">
                    <div class="card text-center bg-purple text-white">
                        <div class="card-body">
                            <h2 class="card-title">Professeurs</h2>
                            <p class="card-text"><?php echo $prof_count; ?> inscrits</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <a href="etudiant.php" class="blob-btn">
                    <div class="card text-center bg-purple text-white">
                        <div class="card-body">
                            <h2 class="card-title">Étudiants</h2>
                            <p class="card-text"><?php echo $student_count; ?> inscrits</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <a href="visitor.php" class="blob-btn">
                    <div class="card text-center bg-purple text-white">
                        <div class="card-body">
                            <h2 class="card-title">Visiteurs</h2>
                            <p class="card-text"><?php echo $visitor_count; ?> inscrits</p>
                        </div>
                    </div>
                </a>
            </div>
        </section>

        <section class="actions text-center">
            <h2>Résumé des activités récentes</h2>
            <p>Activités récentes : Connexions d'utilisateurs, Ajout d'un étudiant, etc.</p>
        </section>

        <section class="logs">
            <h2>Derniers événements</h2>
            <ul class="list-group">
                <?php while ($log = $log_result->fetch_assoc()) : ?>
                    <li class="list-group-item">
                        <strong><?php echo $log['event_type']; ?>:</strong> 
                        <?php echo $log['description']; ?> 
                        <em>(<?php echo $log['created_at']; ?>)</em>
                    </li>
                <?php endwhile; ?>
            </ul>

            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center mt-4">
                    <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>">Précédent</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                        <li class="page-item <?php if ($i === $page) echo 'active'; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>">Suivant</a>
                    </li>
                </ul>
            </nav>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
