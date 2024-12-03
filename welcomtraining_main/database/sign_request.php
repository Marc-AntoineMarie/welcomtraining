<?php
// retrouver toutes les signatures étudiant
  $classes_result = $conn->query("
    SELECT 
      cl.ID_classe, 
      cl.Nom AS ClasseNom, 
      u.Identifiant AS EtudiantNom, 
      co.name AS CoursNom, 
      co.date AS DateCours,
      IF(s.sign_id IS NOT NULL, 'Présent', 'Absent') AS PresenceStatus
    FROM classe cl
    JOIN user u ON u.classe_ID_classe = cl.ID_classe
    JOIN cour co ON co.ID_classe = cl.ID_classe
    LEFT JOIN user_has_sign s ON s.user_id = u.iduser AND s.cours_id = co.ID_cour
  ");

  // Organisation data par classe
  $classes = [];
  while ($row = $classes_result->fetch_assoc()) {
    $classes[$row['ID_classe']]['ClasseNom'] = $row['ClasseNom'];
    $classes[$row['ID_classe']]['Cours'][$row['CoursNom']]['Date'] = $row['DateCours'];
    $classes[$row['ID_classe']]['Cours'][$row['CoursNom']]['Etudiants'][] = [
      'EtudiantNom' => $row['EtudiantNom'],
      'PresenceStatus' => $row['PresenceStatus']
    ];
  }
?>