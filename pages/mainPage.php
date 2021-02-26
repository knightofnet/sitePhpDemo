<?php
/*
 * Fichier : 
 *      mainPage.php 
 * Nom de la page :
 *      Page principale de l'espace membre.
 * Description :
 *      Page princpale de l'espace membre.
 * Traitements possibles :
 *      - Normal : affiche la page (si aucun $_GET)
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

$navbarHtml['accueil']['active'] = true;

include("../header.php");
?>


<div class="row">

    <h2>Bienvenue <?=$_SESSION['user']?></h2>

</div>

<div class="row">

    <!-- Tout le texte des explications -->
    <div class="col-sm-10 offset-1">

        <div class="accordion" id="indexExplications">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h2 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Vous êtes désormais dans un espace membre
                        </button>
                    </h2>
                </div>

                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#indexExplications">
                    <div class="card-body">
                        
                        <p>Vous venez de la page de connexion <code>index.php</code>. Votre connexion a été effectuée avec succès, et donc il y a eu une redirection en utilisant la fonction php <code>header.php</code>.</p>

                        <p>Cette page - <code>mainPage.php</code> - n'est pas accessible si vous n'êtes pas connecté. Pour s'assurer de cela, un test est fait au début de ce fichier :</p>
                        <pre>
    if (!isset($_SESSION['isConnected']) || $_SESSION['isConnected'] == false) {
        header("HTTP/1.0 404 Not Found"); 
        echo "Page inexistante";
        exit();
    }                   </pre>
                        <p>Ce bloc <code>if</code> teste si l'entrée du tableau <code>$_SESSION['isConnected']</code> n'existe pas, ou que si elle existe sa valeur ne soit pas égale à <code>false</code>. Si c'est le cas, alors on revoit un code 404 au navigateur, un message (avec <code>echo</code>), et une instruction <code>exit()</code> pour arrêter le chargement de la page.</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h2 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Les sessions PHP
                        </button>
                    </h2>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#indexExplications">
                    <div class="card-body">
                        <p><span class="font-weight-bold">Comprendre l'intérêt des sessions et leurs rôles dans la création d'espaces utilisateur : </span>Avec la variable <code>$_SESSION</code> il est possible de retenir des informations d'une page à l'autre. Pour toutes les variables classiques, la fin du chargement de la page implique leurs retrait de la mémoire; pas par la variable <code>$_SESSION</code>. Une autre variable spéciale a un comportement persistant entre les différents chargements, c'est la variable <code>$_COOKIE</code>, pour gérer les fameux cookies.</p>

                        <p><span class="font-weight-bold">Session vs Cookie : </span> Si les deux permettent de sauvegarder des données entre les différents chargements de pages, ils ont de nombreuses différences :</p>
                        <ul>
                            <li>Serveur vs client : les données sauvegardées dans la variable <code>$_SESSION</code> le sont côté serveur, ce qui veut aussi dire qu'elles ne sont pas accessibles par le client = le navigateur internet. Les cookies eux sont sauvegardés côté client : ils peuvent donc être plus facilement lus et modifiés (par un script Javascript par exemple, ce qui peut être une façon - non sécurisée - de transmettre des informations entre langage serveur = PHP et langage client = Javascript).
                            <li>Sécurité : le point précédent implique donc que les cookies ne sont pas un moyen sécurisé de sauvegarder des informations.
                            <li>Durée de vie : les sessions se terminent quand l'utilisateur ferme le navigateur. Les cookies peuvent perdurer à travers le temps : c'est vous qui décidez combien de temps.</li>
                            <li>Les sessions utilisent un cookie pour fonctionner : en ouvrant les outils de développement web (touche <kbd>F12</kbd>), puis en vous rendant dans l'onglet Stockage et enfin dans les cookie de <code>http://localhost</code> vous pourrez visualiser les cookies enregistrés pour ce domaine. Un cookie est spécial : PHPSESSID. Il s'agit d'un cookie enregistré par PHP qui permet de garder l'identifiant de la session qui est sauvegardé sur le serveur. Cette valeur (<code><?=session_id()?></code>) permet donc à PHP de savoir à qui sont les données stockées en mémoire, coté serveur, et lui réattribuer dans la variable <code>$_SESSION</code>.</li>
                        </ul>

                        <p><span class="font-weight-bold">Utilisation des sessions  : </span> en inspectant le code source de ce site, avec VisualStudioCode par exemple, vous avez pu apercevoir comment fonctionnait les sessions en PHP. Quelques rappels :                            
                        </p>
                        <ul>
                            <li>Déclarer l'utilisation des sessions le plus tôt possible : Pour utiliser les sessions PHP, il faut que l'instructions <code>session_start()</code> soit écrite le plutot possible dans votre fichier PHP. Pour ce site de démonstration, elle est écrite dans le fichier <code>initCore.php</code>, fichier qui est inclu dans toutes les pages du site. Aucun affichage/envoi de contenu ne doit avoir eu lieu avant son utilisation (donc pas de <code>echo</code>, pas de <code>print()</code> et pas de <code>var_dump()</code>). Si du contenu était envoyé avant, il y aurait une erreur indiquant <code>headers already sent</code> : si tel est le cas, il faudra vérifier votre code. </li>

                        </ul>

                    </div>
                </div>
            </div>

          

        </div>

    </div>
    <!-- [FIN] Tout le texte des explications -->
</div>
<?php
include("../footer.php");