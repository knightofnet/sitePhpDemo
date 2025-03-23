<?php

require_once("initCore.php");

try {

    $dbb = BddUtils::connectBDD();

    // On récupère toutes les images à partir de la BDD.
    $images = ImageServices::getAllImages($dbb);
    foreach ($images as $image) {

        // Pour chaque image, on va supprimer le fichier avec la fonction PHP unlink().
        if ($image['path']) {
            unlink(DIR_SRV . $image['path']);
        }

        // Après avoir effacé le fichier de l'image, on va l'effacer de la BDD.
        ImageServices::deleteImageAvecId($dbb, $image['idimage']);
    }

    // On regarde si la remise à zéro de la base de données a aussi été demandée.

    // Si oui, on efface la BDD.
    AutoCreateNewBdd::razSite($dbb);

    // On détruit aussi la session.
    $_SESSION = [];
    session_destroy();


    echo "Site remis à zéro avec succès.";
} catch (Exception $e) {
    echo "Erreur lors de la remise à zéro du site : " . $e->getMessage();
}
