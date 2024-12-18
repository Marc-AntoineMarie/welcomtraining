<?php 
require 'Session.php';
require 'sign_request.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des Présences par Classe et Cours</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<header>
  <?php include 'admin_header.php'; ?>
</header>

<div class="container mt-5">
  <h2 class="text-center">Gestion des Présences par Classe et Cours</h2> 

  <!-- Dropdown pour selectionner la classe -->
  <div class="form-group mb-4">
    <label for="classSelect">Sélectionnez une classe :</label>
    <select id="classSelect" class="form-control">
      <option value="">Tous les cours</option>
      <?php foreach ($classes as $classe_id => $classe): ?>
        <option value="class-<?php echo $classe_id; ?>"><?php echo htmlspecialchars($classe['ClasseNom']); ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <!-- Display presence by class and course -->
  <?php foreach ($classes as $classe_id => $classe): ?>
    <div class="card mt-4 class-card" id="class-<?php echo $classe_id; ?>">
      <div class="card-header">
        <h4>Classe : <?php echo htmlspecialchars($classe['ClasseNom']); ?></h4>
      </div>
      <div class="card-body">
        <?php foreach ($classe['Cours'] as $cours_nom => $cours_data): ?>
          <h5><?php echo htmlspecialchars($cours_nom); ?> (Date : <?php echo htmlspecialchars($cours_data['Date']); ?>)</h5>
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Étudiant</th>
                <th>Présence</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($cours_data['Etudiants'] as $etudiant): ?>
                <tr>
                  <td><?php echo htmlspecialchars($etudiant['EtudiantNom']); ?></td>
                  <td>
                    <?php if ($etudiant['PresenceStatus'] == 'Présent'): ?>
                      <span class="badge bg-success">Présent</span>
                    <?php else: ?>
                      <span class="badge bg-danger">Absent</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endforeach; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
// JavaScript to filter classes based on dropdown selection
document.getElementById('classSelect').addEventListener('change', function() {
  let selectedClass = this.value;
  document.querySelectorAll('.class-card').forEach(function(card) {
    if (selectedClass === '' || card.id === selectedClass) {
      card.style.display = 'block';
    } else {
      card.style.display = 'none';
    }
  });
});
</script>

</body>
</html>
