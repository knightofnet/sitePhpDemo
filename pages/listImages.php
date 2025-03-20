<?php
/*
 * Fichier : 
 *      listImages.php 
 * Nom de la page :
 *      Page listant les images mises en ligne pour l'utilisateur courant. Permet aussi d'en ajouter.
 * Description :
 *      Cette page va permettre d'afficher les images mises en ligne par l'utilisateur. Un formulaire est
 * 		également présent pour en ajouter. Avec le tableau, l'utilisateur aura la possibilité de supprimer
 * 		une de ces images.
 *      Les traitements se font ailleurs, dans le fichier /php/traitements/envoiImage.php pour l'ajout d'une image
 * 		et dans le fichier /php/traitements/deleteImage.php pour la suppression d'une image.
 * Traitements possibles :
 *      - Normal : affiche la page (si aucun $_GET)
 *      - $_GET : avec le paramètre 'error' dans l'URL, affichera un message d'erreur.
 * 
 */ 

// Cette instruction se retrouve dans toutes les pages :
// Elle permet d'inclure les fichiers PHP nécessaires au fonctionnement du site, ainsi que
// les éléments en commun pour que le site fonctionne.
//
// Le fichier initCore.php est chargé : c'est comme si son code était écris ici.
require_once("../initCore.php");

// Si utilisateur non connecté, ou session inexistante
//  on renvoie une erreur 404 et on arrête le chargement de la page
if (!isset($_SESSION['isConnected']) || $_SESSION['isConnected'] == false) {
    header("HTTP/1.0 404 Not Found");
    echo "Page inexistante";
    exit();
}

/**
 * Fonction transformant les images (récupérées de la BDD), en un tableau HTML.
 */
function transformeImagesEnHtml(array $images) {
	$htmlAretourner = "";
	if (count($images) == 0) {
		$htmlAretourner .= "<tr><td colspan=\"4\">Aucune image</td></tr>";
	} else {
    	for($i=0; $i< count($images); $i++) {        
			$image = $images[$i];
			
			$htmlAretourner .= '<tr>';
			
			$htmlAretourner .= '<td>'.$image['idimage'].'</td>';
			$htmlAretourner .= '<td>'.$image['nomImage'].' <a href="'.URL_SITE.'/pages/consulterImage.php?id='.$image['idimage'].'" title="Consulter l\'image dans une page"><i class="fa fa-eye" aria-hidden="true"></i>
			</a></td>';        
				
			$htmlAretourner .= '<td><a href="'.URL_SITE.'/pages/consulterImage.php?id='.$image['idimage'].'" title="Consulter l\'image dans une page"><img alt="'.$image['nomImage'].'" src="'.URL_SITE.'/'.$image['path'].'" /></a></td>';
			$htmlAretourner .= '<td><a href="#" onClick="adaptModaleForDeleteImg('.$image['idimage'].')" title="Supprimer l\'image"><i class="fa fa-trash" aria-hidden="true"></i>
			</a></td>';
			
			$htmlAretourner .= '</tr>';        
		}
	}
    
    return $htmlAretourner;
    
}

// Ici la variable $messagePourHtml est initialisée. Cette variable - si son contenu est différent de vide - permettra de faire passer un message à l'utilisateur. Exemple, une erreur s'est produite lors de l'envoi d'une image.
$messagePourHtml = "";
// Si une erreur est passé par l'URL, avec le paramétre 'error', on va l'afficher.
if (isset($_GET) && isset($_GET['error'])) {
    $messagePourHtml = $_GET['error'];
}

// La fonction connectBDD de la classe/fichier BddUtils permet de se connecter à la base de données.
// La connexion (si ok) est sauvegardée dans la variable $bdd.
$dbb = BddUtils::connectBDD();

// Variables qui seront utiles dans le HTML
// - On récupère toutes les images postées par l'utilisateur courant.
$images = ImageServices::getAllImagesFromUserId($dbb, $_SESSION['userId']);
$imagesHtml = transformeImagesEnHtml($images);

$classTableauImages = "col-sm-8";
if (empty($images)) {
	$classTableauImages = "col-sm-4";	
}

// Variable contenant la taille maximale en octets qu'il est possible d'envoyer sur le serveur.
$maxFileSizeCoteNavigateur = "8388608";

$navbarHtml['lstImage']['active'] = true;
include("../header.php");
?>

<div class="row">	
	
	<div class="<?=$classTableauImages?>">
		<?php 
			if ($messagePourHtml != "") {
				echo "<div class=\"alert alert-warning\" >$messagePourHtml</div>";   
			}   
			?> 
				
				
		<table class="table thead-light table-striped">
			<thead>
				<tr><th>Id</th><th>Nom de l'image</th><th>Image</th><th></th></tr>
			</thead>
			
			<tbody>
				
				<?=$imagesHtml?>
				
			</tbody>
		
		</table>
	</div>
	
	<div class="col-sm">
			
		<form class="form-signin col-sm-12" method="post" action="<?=URL_SITE?>\php\traitements\envoiImage.php" enctype="multipart/form-data">
			<h3>Ajouter une image</h3>

            <div class="form-group">
                <label for="nomImage">Nom de l'image</label>
                <input name="nomImage" type="text" id="nomImage" class="form-control mb-3" required autofocus> 
                <small class="form-text text-muted">Le nom de l'image, tel qu'il apparaitra dans le site. Il peut être différent du nom du fichier de l'image.</small>
            </div>
				
            <div class="form-group">
				<label for="nomImage">MAX_FILE_SIZE (normalement ce champs doit être caché <code>&lt;input type="hidden" ...&gt;</code>)</label>				
				<input type="text" name="MAX_FILE_SIZE" value="<?=$maxFileSizeCoteNavigateur?>" disabled />  
				<span><?=$maxFileSizeCoteNavigateur." octets = ".fileSizeConvert($maxFileSizeCoteNavigateur)?></span>
                <small class="form-text text-muted">Le champ MAX_FILE_SIZE (mesuré en octets) doit précéder le champ input de type file et sa valeur représente la taille maximale acceptée du fichier par PHP. Cet élément de formulaire doit toujours être utilisé car il permet d'informer l'utilisateur que le transfert désiré est trop lourd avant d'atteindre la fin du téléchargement. Gardez à l'esprit que ce paramètre peut être "trompé" du côté du navigateur facilement, aussi ne faites pas confiance à ce dernier, ne s'agissant finalement que d'une fonctionnalité de convenance côté client. Le paramètre PHP (côté serveur) <code>post_max_size</code> (actuellement paramétré à <?=ini_get('post_max_size')?>) à propos de la taille maximale d'un fichier téléchargé, ne peut, lui, être trompé.</small>
            </div>
		
			<label for="fichier" class="">Sélectionner le fichier</label> 
			<input name="fichier" type="file" id="fichier" class="form-control mb-3" required> 
	
			<button id="form-submit-btn" class="btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#modalSubmit" type="button">Envoyer</button>

			<input id="form-submit" type="submit" value="valider et envoyer" class="d-none">
		
		</form>
	
	</div>
</div>

<!-- La div suivante, ainsi que ses filles, représente la fenêtre qui s'ouvre au moment de cliquer sur "supprimer l'image" -->
<div class="modal fade" tabindex="-1" role="dialog" id="modalDeleteImage">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Suppression de l'image</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>L'image avec l'id '<span class="spanIdImage">X</span>' va être supprimer.</p>
				<p>Voici ce qu'il va se passer une fois que vous aurez cliqué sur continuer :</p>
				<ul>
					<li>Le boutton "Continuer" est en fait un lien vers le fichier php <code>php/traitements/deleteImage.php</code>. Ce lien passe dans son URL le paramètre id et comme valeur <span class="spanIdImage">X</span>, l'id de l'image que vous souhaitez supprimer.</li>
					<li>L'id de l'image va ensuite être vérifié.</li>
					<li>Les données de l'image vont être récupérée dans la BDD avec comme critère son id.</li>
					<li>On va effacer le fichier image sur le serveur.</li>
					<li>On va effacer l'instance de l'image dans la BDD.</li>
				</ul>
				<p>En cas d'échec, le traitement s'arrêtera et l'utilisateur sera redirigé vers cette page (<code>listImages.php</code>) avec un message d'erreur que l'on va passer dans l'URL (et donc, qui sera récupéré avec la variable <code>$_GET</code>). Vous pouvez tester un retour en erreur en cliquant ici <a href="<?=URL_SITE?>/pages/listImages.php?error=On teste l'erreur !" target="_blanck"><?=URL_SITE?>/pages/listImages.php?error=On teste l'erreur !</a>.</p>
				<p>Cliquez sur continuer pour confirmer la suppression de l'image.</p>

			</div>
			<div class="modal-footer">
				<a id="linkDelFichier" class="btn btn-warning" href="URL">Continuer</a>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
			</div>
		</div>
	</div>
</div>

<!-- La div suivante, ainsi que ses filles, représente la fenêtre qui s'ouvre au moment de cliquer sur "Envoyer" -->
<div class="modal fade" tabindex="-1" role="dialog" id="modalSubmit">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Soumission du formulaire d'envoi d'image</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>Vous avez cliquez pour envoyer le formulaire. Voici ce qu'il va se passer une fois que vous aurez cliqué sur continuer :</p>
				<ul>
					<li>Les données du formulaires vont être envoyées au fichier php <code>php/traitements/envoiImage.php</code>.</li>
					<li>Afin de ne pouvoir enregister que des images, l'extension du fichier image va être vérifiée (il faut que l'image soit un fichier 'jpg' , 'jpeg' , 'gif' , 'png'.</li>
					<li>Le fichier va être copié au bon endroit sur le serveur.</li>
					<li>Puis on va ajouter une entrée dans la table image.</li>
				</ul>
				<p>En cas d'échec, le traitement s'arrêtera et l'utilisateur sera redirigé vers cette page (<code>listImages.php</code>) avec un message d'erreur que l'on va passer dans l'URL (et donc, qui sera récupéré avec la variable <code>$_GET</code>). Vous pouvez tester un retour en erreur en cliquant ici <a href="<?=URL_SITE?>/pages/listImages.php?error=On teste l'erreur !" target="_blanck"><?=URL_SITE?>/pages/listImages.php?error=On teste l'erreur !</a>.</p>
				<p>Cliquez sur continuer pour valider le formulaire et envoyer l'image.</p>

			</div>
			<div class="modal-footer">
				<button onclick="submitForm()" type="button" class="btn btn-primary">Continuer</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
			</div>
		</div>
	</div>
</div>


<script>
    function submitForm() {
        $('#modalSubmit').modal('hide');
        var eltForm = document.getElementById('form-submit');
        eltForm.click();

	}
	
	function adaptModaleForDeleteImg(imgId) {
		$("#modalDeleteImage .spanIdImage").each(function(i, e) {
			$(e).text(imgId);
		});
		$('#modalDeleteImage #linkDelFichier').attr("href", "<?=URL_SITE?>/php/traitements/deleteImage.php?id="+imgId);

		$('#modalDeleteImage').modal('show');
		
	}

    $('form input:not([type="submit"])').keydown(function (e) {
        if (e.keyCode == 13) {
            var inputs = $(this).parents("form").eq(0).find(":input");
            if (inputs[inputs.index(this) + 1] != null) {                    
                inputs[inputs.index(this) + 1].focus();
            }
            e.preventDefault();
            return false;
        }
    });

 
</script>

<?php
include("../footer.php");