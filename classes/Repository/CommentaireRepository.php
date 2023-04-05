<?php

require_once('AbstractRepository.php');

class CommentaireRepository extends AbstractRepository
{


    public function addCommentaire(Commentaire $commentaire)
    {
        $sql = "INSERT INTO Commentaire (commentaire, idUser,idArticle) VALUES (:commentaire, :idUser,:idArticle)";
        $query = $this->db->prepare($sql);
        $query->execute([
            'commentaire' => $commentaire->getCommentaire(),
            'idUser' => $commentaire->getIdUser(),
            'idArticle' => $commentaire->getIdArticle(),
        ]);
    }

    public function getCommentsByArticleId($articleId)
    {
        $sql = "SELECT * FROM commentaire WHERE idArticle = :articleId";
        $statement = $this->db->prepare($sql);
        $statement->execute(['articleId' => $articleId]);

        return $statement->fetchAll(PDO::FETCH_CLASS, Commentaire::class);
    }
}

