<?php

// Cette instruction se retrouve dans toutes les pages :
// Elle permet d'inclure les fichiers PHP nécessaires au fonctionnement du site, ainsi que
// les éléments en commun pour que le site fonctionne.
//
// Le fichier initCore.php est chargé : c'est comme si son code était écris ici.
require_once("../../initCore.php");

// Si aucune donnée soumise par un formulaire, ou utilisateur non connecté, ou session inexistante
//  on renvoie une erreur 404 et on arrête le chargement de la page
if (!isset($_GET) || !isset($_GET['id'])) {
    if (!isset($_SESSION['isConnected']) || $_SESSION['isConnected'] == false) {
        // Envoie une instruction HTTP 404
        header("HTTP/1.0 404 Not Found");
        echo "Page inexistante";
        exit();
    } else {
        redirectWithError("Accés non autorisé.");
    }
}



$idImage = VerifierUtils::verifierEstEntier($_GET['id']);
if ($idImage == -1) {
    redirectWithError("Id de l'image invalide");

}

$dbb = BddUtils::connectBDD();

$imageTablo = ImageServices::getImageWithId($dbb, $idImage);

var_dump($imageTablo);



if(file_exists(DIR_SRV.$imageTablo['path'])) {
    unlink(DIR_SRV.$imageTablo['path']);
}

ImageServices::deleteImageAvecId($dbb, $idImage);

header("Location: ".URL_SITE."/pages/listImages.php");