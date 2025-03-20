<?php
/*
 * Fichier : 
 *      razSite.php 
 * Nom de la page :
 *      Page permettant de remettre le site à zéro.
 * Description :
 * 		Avec cette page, l'utilisateur - qu'il soit connecté ou non - va pouvoir remettre le site à zéro. Soit
 * 		juste pour supprimer les images enregistrées, soit pour tout effacer (sauf les fichiers php/js/css).
 * Traitements possibles :
 *      - Normal : affiche la page (si aucun $_GET)
 *      - $_GET : avec le paramètre 'action' dans l'URL associé à la valeur 'raz' / 'delImages', va effectuer
 * 			les actions demandées.
 * 
 */

// Cette instruction se retrouve dans toutes les pages :
// Elle permet d'inclure les fichiers PHP nécessaires au fonctionnement du site, ainsi que
// les éléments en commun pour que le site fonctionne.
//
// Le fichier initCore.php est chargé : c'est comme si son code était écris ici.
require_once("../initCore.php");

// La fonction connectBDD de la classe/fichier BddUtils permet de se connecter à la base de données.
// La connexion (si ok) est sauvegardée dans la variable $bdd.
$dbb = BddUtils::connectBDD();

/*
 * Traitement de la variable $_GET.
 * 
 */
// Test si dans le tableau de la variable $_GET, il y a une clé se nommant action. Si oui, est-ce que sa valeur
// est 'raz' ou 'delImages'. Si oui, on continue le traitement.
if (isset($_GET['action']) && ($_GET['action'] == "raz" || $_GET['action'] == "delImages")) {

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
	if ($_GET['action'] == "raz") {
		// Si oui, on efface la BDD.
		AutoCreateNewBdd::razSite($dbb);

		// On détruit aussi la session.
		$_SESSION = [];
		session_destroy();
	}
	// On redirige l'utilisateur vers la page index.php.
	header("Location: " . URL_SITE . "/index.php");

	// La fonction exit() arrête le traitement de ce fichier à ce niveau.
	// Comme on va rediriger l'utilisateur, pas la peine de continuer le traitement de ce fichier.
	exit();
}



$navbarHtml['razSite']['active'] = true;

include("../header.php");
?>


<div class="row">
	<div class="col">
		<h1>Remettre à zéro le site</h1>

		<p>Si, pour une raison ou une autre, vous désirez remettre à zéro le site, cliquez sur l'un des boutons suivants :</p>

		<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalAvertissement">R-à-Z</button>

		<a href="?action=deleteImages" class="btn btn-warning">Supprimer les images</a>
		<a href="<?= URL_SITE . "/index.php" ?>" class="btn btn-success">Revenir à l'accueil</a>

	</div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modalAvertissement">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Remise à zéro du site</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>Vous avez cliqué pour remettre le site à zéro. Toutes les images envoyées vont être supprimées et la base de données va être effacée.</p>
				<p>Validez en cliquant sur le bouton Continuer.</p>
			</div>
			<div class="modal-footer">
				<a href="?action=raz">
					<button type="button" class="btn btn-danger">Continuer</button>
				</a>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
			</div>
		</div>
	</div>
</div>

<?php
include("../footer.php");
?>