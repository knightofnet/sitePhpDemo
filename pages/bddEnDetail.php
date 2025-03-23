<?php
/*
 * Fichier : 
 *      mainPage.php 
 * Nom de la page :
 *      Page principale de l'espace membre.
 * Description :
 *      Page principale de l'espace membre.
 * Traitements possibles :
 *      - Normal : affiche la page (si aucun $_GET)
 * 
 */

// Cette instruction se retrouve dans toutes les pages :
// Elle permet d'inclure les fichiers PHP nécessaires au fonctionnement du site, ainsi que
// les éléments en commun pour que le site fonctionne.
//
// Le fichier initCore.php est chargé : c'est comme si son code était écrit ici.
require_once("../initCore.php");

$navbarHtml['bddEnDetail']['active'] = true;

include("../header.php");
?>


<div class="row">

</div>
<?php
include("../footer.php");
