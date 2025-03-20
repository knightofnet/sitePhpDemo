<?php
/*
 * Fichier : 
 *      consulterImage.php 
 * Nom de la page :
 *      Permet la consultation d'une image individuellement.
 * Description :
 *      Cette page permet d'illustrer comment fonctionne une page dédiée à l'affichage d'un élément (ici une image,
 *      mais cela pourrait servir à afficher un produit par exemple). L'identifiant de ce qu'il faut afficher est
 *      récupéré à partir de $_GET['id'].
 * Traitements possibles :
 *      - Normal : affiche la page (si aucun $_GET)
 *      - $_GET : avec le paramètre 'id' dans l'URL, cela indiquera quelle image il faudra charger.
 * 
 */

// Cette instruction se retrouve dans toutes les pages :
// Elle permet d'inclure les fichiers PHP nécessaires au fonctionnement du site, ainsi que
// les éléments en commun pour que le site fonctionne.
//
// Le fichier initCore.php est chargé : c'est comme si son code était écris ici.
require_once("../initCore.php");

// Si utilisateur non connecté, ou session inexistante
// on renvoie une erreur 404 et on arrête le chargement de la page
if (!isset($_SESSION['isConnected']) || $_SESSION['isConnected'] == false) {
    header("HTTP/1.0 404 Not Found");
    echo "Page inexistante";
    exit();
}

// La fonction connectBDD de la classe/fichier BddUtils permet de se connecter à la base de données.
// La connexion (si ok) est sauvegardée dans la variable $bdd.
$dbb = BddUtils::connectBDD();

// On initialise quelques variables pour l'affichage, plus bas, dans l'HTML.
$imgTitle = "";
$urlImg = "";
$imgPersonneName = "";

// Ici la variable $messagePourHtml est initialisée. Cette variable - si son contenu est différent de vide - permettra de faire passer un message à l'utilisateur.
$messagePourHtml = "";

// On regarde si l'id a été transmis dans l'URL (si $_GET['id'] existe)...
if (isset($_GET['id'])) {
    // Si oui, on récupère sa valeur, en testant qu'elle soit correcte, c'est-à-dire que ce soit bien un entier.
    // Si $_GET['id'] n'est pas un entier, $imageId = -1.
    $imageId = VerifierUtils::verifierEstEntier(htmlspecialchars($_GET['id']));

    // On ne va récupérer les informations en BDD que si $imageId est supérieur à 0, et que l'id corresponde à une image
    // en BDD.
    if ($imageId > 0 && ImageServices::isExistsImageWithId($dbb, $imageId)) {

        // Si oui, alors on récupère les données de l'image en BDD.
        $imageFromBdd = ImageServices::getImageWithId($dbb, $imageId);

        // Et on peut remplir les données pour afficher plus bas dans le HTML.
        // ... Ici le nom de l'image
        $imgTitle = $imageFromBdd['nomImage'];
        // ... Ici l'URL de l'image
        $urlImg = URL_SITE . '/' . $imageFromBdd['path'];

        // ... Ici le nom de la personne qui a mis en ligne l'image.
        // $imageFromBdd['idPersonnne'], on a l'id de la personne; si on veut son nom, il va falloir aller le récupérer. 
        $imgPersonneName = PersonneServices::getUserById($dbb, $imageFromBdd['idpersonne']);

        $classTableauImages = "";
    } else {

        // Si $imageId est <= 0, c'est que $_GET['id'] n'était pas valide, ou alors c'est qu'il était valide mais l'image avec cet 
        // id n'existe pas en BDD.

        // On prépare un texte d'erreur.
        $imgTitle = "Aucune image avec l'id passé dans l'URL";
        $messagePourHtml = '<p>La page a été chargée avec dans l\'URL, le paramètre "id" (<code>pages/consulterImage.php?id=' . $_GET['id'] . '</code>). Mais cet id n\'est pas celui d\'une image ou alors il est invalide. Du coup, on ne peut rien afficher.</p><p>Pour ce site de démo, rien ne s\'affiche hormis ce message; dans un vrai site, il aurait peut-être fallu rediriger l\'utilisateur ou lui indiquer que la page qu\'il désirait voir n\'existe plus.</p><p>Exemple d\'un id qui ne mène à rien, car il y a une erreur dans la dernière partie de l\'URL : <a href="https://www.amazon.fr/Concevez-votre-site-avec-MySQL-ebook/dp/B078475d7KF" target="_blank">https://www.amazon.fr/Concevez-votre-site-avec-MySQL-ebook/dp/B078475d7KF</a></p>';
        $classTableauImages = " d-none";
    }
} else {
    // Si $_GET['id'] n'existe pas, alors on ne peut rien faire.

    // On prépare un texte d'erreur.
    $imgTitle = "Il n'y a aucun id qui a été passé dans l'URL";
    $messagePourHtml = '<p>La page a été chargée sans le paramètre "id" qui permet d\'indiquer quelle image afficher. Du coup, on ne peut rien afficher.</p><p>Pour ce site de démo, rien ne s\'affiche hormis ce message; dans un vrai site, il aurait peut-être fallu rediriger l\'utilisateur ou lui indiquer que la page qu\'il désirait voir n\'existe plus.</p>';
    $classTableauImages = " d-none";
}


$navbarHtml['consulterImage']['active'] = true;

include("../header.php");
?>

<div class="row">
    <div class="col">

        <h1><?= ucfirst($imgTitle) ?></h1>

    </div>
</div>

<div class="row<?= $classTableauImages ?>">
    <div class="col-sm-6 affiche-image">
        <figure>
            <img alt="<?= ucfirst($imgTitle) ?>" src="<?= $urlImg ?>" />
            <figcaption>Image nommée <?= $imgTitle ?>, mise en ligne par <?= $imgPersonneName ?></figcaption>
        </figure>


    </div>

    <div class="col-sm-6">
        <a href="javascript:history.back()">Retour à la page précédente</a> |
        <a href="<?= URL_SITE . '/pages/listImages.php' ?>">Liste des images</a>
    </div>

</div>
<div class="row">
    <div class="col-sm-8">
        <?php
        if ($messagePourHtml != "") {
            echo "<div class=\"alert alert-warning\" >$messagePourHtml</div>";
            echo "<a href=\"javascript:history.back()\">Retour à la page précédente</a> | ";
            echo "<a href=\"" . URL_SITE . '/pages/listImages.php' . "\">Liste des images</a>";
        }
        ?>
    </div>
</div>

<?php
include("../footer.php");
?>