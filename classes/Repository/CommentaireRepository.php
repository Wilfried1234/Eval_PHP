<?php
require_once('AbstractRepository.php');

class CommentaireRepository  extends AbstractRepository{
    public function getCommentaire(): array
    {
        $sql = "SELECT * FROM commentaire";
        $statement = $this->db->prepare($sql);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_CLASS, Commentaire::class);
    }




        public function addCommentaire(Commentaire $commentaire) {
        $sql = "INSERT INTO Commentaire (commentaire, idUser) VALUES (:commentaire, :idUser)";
        $query = $this->db->prepare($sql);
        $query->execute([
            'commentaire' => $commentaire->getCommentaire(),
            'idUser' => $commentaire->getIdUser(),
        ]);

    }



}
?>