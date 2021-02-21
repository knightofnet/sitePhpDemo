<?php 

// Cette instruction se retrouve dans toutes les pages :
// Elle permets d'inclure les fichiers PHP nécessaires au fonctionnement du site, ainsi que
// les éléments en commun pour que le site fonctionne.
//
// Le fichier initCore.php est chargé : c'est comme si son code était écris ici.
require_once("../../initCore.php");

// Si aucune donnée soumise par un formulaire, ou utilisateur non connecté, ou session inexistante
//  on renvoie une erreur 404 et on arrête le chargement de la page
if (!isset($_POST) || !isset($_SESSION['isConnected']) || $_SESSION['isConnected'] == false) {
    header("HTTP/1.0 404 Not Found");
    echo "Page inexistante";
    die();
}

/*
 * L'utilisateur est connecté et un formulaire a été posté.
 * Il va falloir traiter l'ajout de l'image :
 * 
 */

$nomImage = $_POST['nomImage'];


// Les informations du fichier sont disponibles dans la variable global $_FILES.
if (!isset($_FILES['fichier'])) {
    redirectWithError("Aucun fichier n'a été soumis avec le formulaire");
}

if ($_FILES['fichier']['error'] > 0) {
    $erreur = "Erreur lors du transfert";
    redirectWithError($erreur);
}

$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
//1. strrchr renvoie l'extension avec le point (« . »).
//2. substr(chaine,1) ignore le premier caractère de chaine.
//3. strtolower met l'extension en minuscules.
$extension_upload = strtolower(  substr(  strrchr($_FILES['fichier']['name'], '.')  ,1)  );
if ( !in_array($extension_upload,$extensions_valides) ) {
    $erreur = "Extension du fichier incorrecte";
    
    redirectWithError($erreur);
}

$cheminImageSurServeur = DIR_SRV."images/".$_FILES['fichier']['name'];
$resultat = move_uploaded_file($_FILES['fichier']['tmp_name'],$cheminImageSurServeur);

$urlServeur = "/images/".$_FILES['fichier']['name'];

if ($resultat) {
    
    // le fichier est correctement uploadé sur le serveur.
    // Il ne reste plus qu'à l'insérer en BDD
    $dbb = BddUtils::connectBDD();
    $idImage = ImageServices::ajouteNouvelleImage($dbb, $nomImage, $urlServeur, $_SESSION['userId']);
    if ($idImage == 0) {
        $erreur = "Extension lors de l'ajout de l'image en BDD.";
    
        redirectWithError($erreur);
    }
    header("Location: ".URL_SITE."/pages/listImages.php?msg=Ok");
    exit();
    
} else {
    $erreur = "Erreur lors du transfert vers le serveur ".$resultat;
    
    redirectWithError($erreur);
}

?>