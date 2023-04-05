<?php
session_start();
require_once('classes/Article.php');
require_once('classes/Repository/ArticleRepository.php');
require_once('classes/User.php');
require_once('classes/Repository/UserRepository.php');
require_once('classes/Commentaire.php');
require_once('classes/Repository/CommentaireRepository.php');



$user = User::isLogged();

$articleRepository = new ArticleRepository();
$userRepository = new UserRepository();



//Si le paramètre id n'existe pas
if (!isset($_GET['id'])) {
    header('Location: index.php');
}

//On récupère l'id de l'article qu'on a dans l'url
$articleId = $_GET['id'];

//On va chercher dans la liste des articles, l'article qui correspond à l'id qu'on a dans l'url
$articleToShow = $articleRepository->findArticle($articleId);

//Si aucun article ne correspond dans la liste
if ($articleToShow === false) {
    header('Location: index.php');
}

$auteur = $userRepository->getById($articleToShow->getUserId());

$commentRepository = new CommentaireRepository();
$comments = $commentRepository->getCommentsByArticleId($articleId);


// Afficher le formulaire si on et connecté

if ($user) {

// Ajout d'un nouveau commentaire
if (isset($_POST['commentaire']) && isset($_POST['articleId'])) {
    $commentContent = ($_POST['commentaire']);
    $articleId = $_POST['articleId'];

    // Vérifie si le commentaire n'est pas vide
    if (!empty($commentContent)) {
        $commentaireRepository = new CommentaireRepository();

        // Crée un nouvel objet Commentaire
        $commentaire = new Commentaire();
        $commentaire->setCommentaire($commentContent);
        $commentaire->setIdUser($user->getId());
        $commentaire->setIdArticle($articleId);

        // Enregistre le commentaire en base de données
        $commentaireRepository->addCommentaire($commentaire);
    }
}

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="/public/css/show-article.css">
    <title>Article</title>
</head>

<body>
<div class="container">
    <?php require_once 'includes/header.php' ?>
    <div class="content">
        <div class="article-container">
            <a class="article-back" href="/">Retour à la liste des articles</a>
            <div class="article-cover-img" style="background-image:url(<?= $articleToShow->getImageFullPath() ?>)"></div>
            <h1 class="article-title"><?= $articleToShow->getTitle() ?></h1>
            <span>Rédigé par : <?= $auteur->getNom() . ' ' . $auteur->getPrenom() ?></span>
            <div class="separator"></div>
            <p class="article-content"><?= $articleToShow->getContent() ?></p>
            <?php if($user !== false && ($auteur->getId() === $user->getId())): ?>
            <div class="action">
                <a class="btn btn-secondary" href="/delete-article.php?id=<?= $articleId ?>">Supprimer</a>
                <a class="btn btn-primary" href="/form-article.php?id=<?= $articleId ?>">Editer l'article</a>
            </div>
            <?php endif; ?>

            <div class="comments">
                <div class="saisie-comments">
                <h2>Commentaire</h2>
                <form action="show-article.php?id=<?= $articleId ?>" method="post">
                    <textarea name="commentaire" placeholder="Saisissez votre commentaire"></textarea>
                    <input type="hidden" name="articleId" value="<?= $articleId ?>">
                    <input type="submit" value="Envoyer">
                </form>
                </div>
                <?php foreach ($comments as $comment): ?>
                    <?php $author = $userRepository->getById($comment->getIdUser()); ?>
                    <?php if ($author !== null): ?>
                        <div class="comment">
                            <span class="comment-author"><?= $author->getNom() . ' ' . $author->getPrenom() ?></span>
                            <p class="comment-content"><?= $comment->getCommentaire() ?></p>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php require_once 'includes/footer.php' ?>
</div>
</body>
</html>