<?php 

// Gestion des Cours
class ShowCourForm {

    private $pdo;
    private $SubjectRecoverySqlRequest = "SELECT * FROM matiere";
    private $TeacherRecoverySqlRequest = "SELECT c.ID_classe, c.Nom, co.name AS Nom_cours, co.horaire, co.date, u.Identifiant AS prof_nom 
    From classe c
    JOIN cour co ON c.ID_classe = co.ID_classe 
    JOIN user u ON co.prof = u.iduser";

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function SubjectRecovery() 
    {
        $stmt = $this->pdo->prepare($this->SubjectRecoverySqlRequest);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function TeacherRecovery()
    {
        $stmt= $this->pdo->prepare($this->TeacherRecoverySqlRequest);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function FormGestionnary()
    {
        $form = new FormCour();

        // preparation de la requete SQL
        $sql_cour = "INSERT INTO cour (name, horaire, date, ID_classe, prof) 
                    VALUES (:name, :horaire, :date, :ID_classe, :prof)";

        // Execution des requetes préparer
        $stmt_cour = $this->pdo->prepare($sql_cour);
        $stmt_cour->bindParam(':name', $form->name, PDO::PARAM_STR);
        $stmt_cour->bindParam(':horaire', $form->horaire, PDO::PARAM_INT);
        $stmt_cour->bindParam(':date', $form->date, PDO::PARAM_INT);
        $stmt_cour->bindParam(':ID_classe', $form->ID_classe, PDO::PARAM_INT);
        $stmt_cour->bindParam(':prof', $form->form, PDO::PARAM_INT);

    }
};

?>