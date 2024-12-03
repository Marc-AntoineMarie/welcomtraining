<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signature en Ligne + Calendrier</title>
    <link rel="stylesheet" href="signature.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <!-- En-tête -->
        <div class="text-center mb-4">
            <h2 class="display-4">Signature en Ligne</h2>
            <p class="lead">Dessinez votre signature ci-dessous</p>
        </div>

        <!-- Zone de signature -->
        <div class="card shadow p-4">
            <div class="card-body d-flex justify-content-center">
                <canvas id="signatureCanvas" width="500" height="200" class="border rounded bg-light"></canvas>
                <img id="savedSignature" alt="Signature sauvegardée" class="mt-3 img-fluid d-none"/>
            </div>
        </div>

        <!-- Boutons de contrôle -->
        <div class="text-center mt-4">
            <button id="returnButton" class="btn btn-secondary mx-2">Annuler</button>
            <button id="clearButton" class="btn btn-warning mx-2">Effacer</button>
            <button id="saveButton" class="btn btn-primary mx-2">Envoyer</button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
    