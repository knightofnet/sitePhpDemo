<?php
/*
 * Fichier : 
 *      index.php (c'est le fichier par défaut que le serveur web - APACHE - sert quand vous entrez l'URL).
 * Nom de la page :
 *      Page de connexion et d'enregistrement.
 * Description :
 *      Cette page va vous permettre de lire des renseignements sur le site. Avec, vous pourrez également
 *      vous enregistez / vous connectez à la partie réservée aux utilisateurs connectés
 * Traitements possibles :
 *      - Normal : affiche la page (si aucun $_GET et $_POST)
 *      - $_GET : avec le paramètre 'action' dans l'URL associé à la valeur 'logout', permettra à l'utilisateur
 *          connecté de se déconnecter.
 *      - $_POST : permet de traiter le formulaire de connexion/d'enregistrement.
 * 
 */ 

/* 
 * Cette instruction se retrouve dans toutes les pages :
 * Elle permets d'inclure les fichiers PHP nécessaires au fonctionnement du site, ainsi que
 * les éléments en commun pour que le site fonctionne (comme l'inclusion des fichiers PHP nécessaires ainsi que la gestion de la navbar).
 * Le fichier initCore.php est chargé : c'est comme si son code était écrit ici.
 */ 
require_once("initCore.php");

// Ici la variable $messagePourHtml est initialisée. Cette variable - si son contenu est différent de vide - permettra de faire passer un message à l'utilisateur.
$messagePourHtml = "";
// La fonction connectBDD de la classe/fichier BddUtils permets de se connecter à la base de données.
// La connexion (si ok) est sauvegardée dans la variable $bdd.
$dbb = BddUtils::connectBDD();


/*
 * Traitement de la variable $_GET si utilisateur connecté.
 * 
 */ 
// Si l'utilisateur est connecté actuellement...
if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] == true) {
    
    // ... Et si 'il existe un paramètre nommé 'action' dans l'URL...
    if (isset($_GET) && isset($_GET['action']) ) {

        // On vérifie que ce paramètres est valide avec la fonction verifierGetAction(). 
        // Voir le détail dans le fichier ./php/VerifierUtils.
        $action = VerifierUtils::verifierGetAction($_GET['action']);
        if ($action == "logout") {
            // si l'utilisateur a demander à se deconnecter, on détruit la session. 
            session_destroy();

            // Et on en reinitialise une.
            session_start();

            // On passe un message à l'utilisateur.
            $messagePourHtml = "L'utilisateur a été déconnecté : La session a été détruite. Idéalement, on aurait du rédiriger l'utilisateur vers une URL sans paramètres : <a href=\"".URL_SITE."\">comme ici</a>";
        }
    } else {
    
        // Si rien de spéciale, l'utilisateur est connecté on le renvoi vers la page
        // des utilisateurs connectés.
        header("Location: ".URL_SITE."/pages/mainPage.php"    );

        // La fonction exit() arrête le traitement de ce fichier à ce niveau.
        // Comme on va rediriger l'utilisateur, pas le peine de continuer le traitement de ce fichier.
        exit();
    }
    
} 
else // Sinon (= l'utilisateur n'est pas connecté)
{
    // ... et il y a un paramètre nommé 'action' dans l'URL...
    if (isset($_GET) && isset($_GET['action']) ) {
        // ... Alors on va rediriger l'utilisateur vers une URL sans paramètres (juste pour que ça soit propre).
        header("Location: ".URL_SITE);

        // La fonction exit() arrête le traitement de ce fichier à ce niveau.
        // Comme on va rediriger l'utilisateur, pas le peine de continuer le traitement de ce fichier.
        exit();
    }
}


/*
 * Traitement de la variable $_POST (= des données d'un formulaire ont été postée).
 * 
 */ 
// Si la variable $_POST existe (isset($_POST)) et que le nombre d'éléments dans cette variable (rappel : $_POST et $_GET sont
// des tableaux) est au moins de 2 (on attend le mail et le mot de passe), alors on va traiter tout ça.
if (isset($_POST) && count($_POST) >= 2 ) {

    // Le formulaire nous a transmis 3 informations normalement : l'email, le mot de passe et s'il le fallait, un booléen indiquant
    // qu'il faut enregister l'utilisateur. On va récupèrer ces variables en les vérifiants.

    // On initialise le booléen suivant à 'true'. 
    // Il nous servira à savoir s'il y a eu une erreur. Si c'est le cas, on le placera à false,
    // ce qui aura pour effet plus bas [if ($isOkToContinue) ...] de ne pas rentrer dans le if, et donc
    // de ne faire aucun traitement.
    $isOkToContinue = true;

    // On récupère et vérifie l'email.
    // - Pour cela on utilise la fonction verifierEmail() (de la classe statique/du fichier VerifierUtils)
    // - Si la fonction renvoie null dans la variable $user => c'est qu'il y a eu un souci. On ne fera pas de traitement plus bas.
    // - Si la fonction ne renvoie pas null, alors on a dans la variable $user, le mail.
    $user = VerifierUtils::verifierEmail($_POST['email']);
    if ($user == null) {
        $messagePourHtml = "L'adresse email transmise n'a pas le bon format. ";
        $isOkToContinue = false;
    }

    // On récupère et vérifie le mot de passe.
    // - Pour cela on utilise la fonction verifierMotDePasse() (de la classe statique/du fichier VerifierUtils)
    // - Si la fonction renvoie null dans la variable $passwd => c'est qu'il y a eu un souci. On ne fera pas de traitement plus bas.
    // - Si la fonction ne renvoie pas null, alors on a dans la variable $passwd, le mot de passe.
    $passwd = VerifierUtils::verifierMotDePasse($_POST['passwd']);
    if ($passwd == null) {
        $messagePourHtml .= "Le mot de passe ne respecte pas les conditions. ";
        $isOkToContinue = false;
    }
    
    // On récupère la valeur de la checkbox ici. 
    // La valeur est facultative, donc on l'initialise a false par défaut.
    $isRegisterMode = false;
    // Si le champs a été transmis dans $_POST, alors on vérifie la valeur transmise. Si le format n'est pas bon, alors on mettre
    // false.
    if (isset($_POST['isRegister'])) {
        $isRegisterMode = VerifierUtils::verifierChampsBooleen($_POST['isRegister']);
    }
    

    // Si la valeur de la variable $isOkToContinue est toujours true, alors le traitement poursuivra.
    // Sinon cette partie sera ignoré = aucun traitement.
    if ($isOkToContinue) {
    
        if ($isRegisterMode) {
            // Le formulaire a demandé à enregister une nouvelle personne.
            
            if (PersonneServices::isUserExists($dbb, $user)) {
                // la personne existe, il faut avertir l'utilisateur
                // qu'il ne peut pas s'enregistrer, car le compte existe déjà
                $messagePourHtml = "Un compte existe déjà avec cette adresse email";
            } else {
                // la personne n'existe pas, on va l'enregistrer
                
                $userId = PersonneServices::addNewPersonne($dbb, $user, $passwd);
                $_SESSION['isConnected'] = true;
                $_SESSION['user'] = $user;
                $_SESSION['userId'] = $userId;
                
                // On redirige l'utilisateur
                header("Location: ".URL_SITE."/pages/mainPage.php");

                // La fonction exit() arrête le traitement de ce fichier à ce niveau.
                // Comme on va rediriger l'utilisateur, pas le peine de continuer le traitement de ce fichier.
                exit();
                
            }
            
        } else { // Sinon (= L'utilisateur souhaite se connecter)
            
            if (PersonneServices::isUserExistsWithPassword($dbb, $user, $passwd) ) {
                // la personne existe : on connecte l'utilisateur
                
                $_SESSION['userId'] = PersonneServices::getUserId($dbb, $user, $passwd);

                $_SESSION['isConnected'] = true;
                $_SESSION['user'] = $user;
                
                // On redirige l'utilisateur
                header("Location: ".URL_SITE."/pages/mainPage.php");

                // La fonction exit() arrête le traitement de ce fichier à ce niveau.
                // Comme on va rediriger l'utilisateur, pas le peine de continuer le traitement de ce fichier.
                exit();

            } else {
                $messagePourHtml = "Aucun utilisateur n'existe avec ce nom d'utilisateur ou ce mot de passe";
                // On n'indique pas si c'est le mot de passe qui est faux, ou le nom d'utilisateur : un utilisateur
                // malveillant pourrait utiliser cette indication à des fins malhonnètes.
                
            }
        }

    } // Fin : if($isOkToContinue) 
}

$checkedInitStateHtml = "";
if (!PersonneServices::isThereOneUser($dbb)) {
    $checkedInitStateHtml = " checked";
}

$navbarHtml['connect']['active'] = true;

include("header.php");
?>

<?php 
	if ($messagePourHtml != "") {
	   echo "<div class=\"alert alert-warning\" >$messagePourHtml</div>";   
	}   
	?>

<h1>Site démo</h1>

<div class="row">

    <!-- Tout le texte des explications -->
    <div class="col-sm-8">

        <div class="accordion" id="indexExplications">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h2 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Bienvenue sur ce site de démonstration
                        </button>
                    </h2>
                </div>

                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#indexExplications">
                    <div class="card-body">
                        <h2>Bienvenue sur ce site de démonstration</h2>
                        <p>Il s'agit d'un petit site web permettant d'illustrer certains concepts de la programmation PHP. Il n'a pas pour but d'être aussi complet que ceux que vous allez/devez produire.</p>

                        <p>Si vous accédez à cette page sans qu'il n'y ait eu d'erreur, c'est que le site s'est auto-configuré. Lors de cette configuration, une base de donnée a été créée; elle se nomme <code>bddexemple</code>. Cette dernière est très simple, puisqu'elle ne contient que 2 tables : <code>personne</code> et <code>image</code>. N'hésitez pas à aller voir la BDD avec <a href="<?="http://".$_SERVER['HTTP_HOST']."/phpmyadmin/index.php?db=bddexemple&target=db_structure.php"?>" target="_blank">PhpMyAdmin</a>.</p>

                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h2 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Quelques informations sur ce site
                        </button>
                    </h2>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#indexExplications">
                    <div class="card-body">
                        <p><span class="font-weight-bold">Base de données : </span>La création de la base de données est réalisé par le code PHP présent dans ce fichier <code>./php/AutoCreateNewBdd.php</code>. Vous pouvez y jeter un coup d'oeil, mais ce genre de chose ne vous sera pas demandé.</p>

                        <p><span class="font-weight-bold">Style CSS et Bootstrap : </span>Presque l'intégralité de code CSS de ce site utilise le framework Bootstrap v4. C'est une véritable boîte à outil qui vous met à disposition toute une série de fonctionnalités très pratiques pour coder des sites web entièrement responsive facilement. Cela facilite grandement la création de l'interface. Il convient d'ajouter un fichier de style CSS afin de personnalisé tout de même le site, et ne pas se retrouver avec une interface générique (comme ce site). Vous êtres libre de l'utiliser, mais en tant que complément : votre site ne doit pas utiliser uniquement Bootstrap.</p>

                        <p><span class="font-weight-bold">Décomposition de l'interface en 3 fichiers PHP : </span>Vous l'avez vu en cours et en TP, les pages HTML (même si maintenant vous utilisez des fichiers avec l'extensions .php) ont toujours la même structure. Globalement on a le début avec la balise <code>&lt;html&gt;</code>, la partie dans le <code>&lt;head&gt;</code>, la balise <code>&lt;body&gt;</code> et le contenu de la page, etc. Le début de cette structure est toujours la même (balise html, head, body et parfois la barre de navigation du site), seul le corps du site change d'une page à l'autre. Alors, au lieu de toujours réécrire le même début, j'ai placé le début de la structure d'un fichier php nommé <code>header.php</code>. Par conséquent, pour cette page/ce fichier (<code>index.php</code>) ainsi que pour toutes les autres pages, au moment où il faut écrire le début de la structure HTML, il suffit d'inclure le fichier <code>header.php</code> en utilisant la fonction PHP <code>include()</code>. Ce qui donne : <code>include("header.php");</code>. Dès lors, le début de la structure HTML sera importée, évitant de la réécrire pour chaque page. Autre avantage, si je souhaite modifier des éléments dans le head, il me suffit de le modifier qu'une seule fois. La même technique est utilisée pour le bas de page en incluant le fichier <code>footer.php</code>.</p>

                        <p><span class="font-weight-bold">Footer technique : </span> Le footer - la barre du bas - va vous permettre de suivre quelques informations techniques sur le site. Vous pourrez à chaque chargement de page regarder si vous êtes connecté (selon la logique du site), ainsi que de consulter les requêtes SQL qui ont été effectuées durant le chargement de cette page. Par exemple, si vous regarder dans la partie contenant les requêtes SQL qui ont été jouées, vous devriez en voir une : <code>SELECT count(*) as C FROM personne</code>. Cette dernière a permis de tester s'il y a au moins un utilisateur d'inscrit sur le site. S'il y en a pas, alors la case 'S'enregistrer' du formulaire sur la droite est pré-cochée.</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" id="headingThree">
                    <h2 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Sessions utilisateur et connexion
                        </button>
                    </h2>
                </div>
                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#indexExplications">
                    <div class="card-body">
                        <p>Vous pouvez le voir sur la droite de cette page, il y a un formulaire permettant de se connecter à un compte ainsi que de s'enregister sur le site (selon que vous cochiez ou non la case 'S'enregistrer')</p>
                        <p>Le formulaire envoie les données à la page courante (fichier <code>index.php</code>). Selon le traitement demander (s'enregister ou par défaut, se connecter), il va être nécessaire d'aller vérifier en BDD que l'utilisateur existe, puis vérifier son mot de passe pour une connexion, ou ajouter le nouvel utilisateur pour un enregistrement.</p>
                        <p>Ensuite, si l'utilisateur existe et qu'il a le bon mot de passe ou s'il s'agit d'un nouvel utilisateur, on va initialiser une entrée dans le tableau de la variable <code>$_SESSION</code> indiquant que l'utilisateur est connecté (<code>$_SESSION['isConnected'] = true</code>), et une autre entrée indiquant le nom de l'utilisateur connecté (<code>$_SESSION['user'] = true</code>).</p>
                        <p>Enfin, avec la fonction PHP d'écriture d'en-tête HTTP <code>header()</code>, on redirige l'utilisateur vers la page de son espace utilisateur en écrivant ceci : <code>header("Location: ".URL_SITE."/mainPage.php");</code>. Pour information la page de l'espace membre se trouve à cette adresse <a href="<?=URL_SITE.'/mainPage.php'?>" target="_blank"><?=URL_SITE.'/mainPage.php'?></a>, mais elle est normalement inacessible pour l'instant car vous n'êtes pas connecté.</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" id="headingFour">
                    <h2 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Derniers conseils
                        </button>
                    </h2>
                </div>
                <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#indexExplications">
                    <div class="card-body">
                        <p>Ouvrez les fichiers PHP et lisez le code. J'ai essayé de mettre pas mal de commentaires afin de vous expliquer le plus de choses possibles. Vous pouvez utilisez l'IDE gratuit et multi plateforme <a href="https://code.visualstudio.com/" title="Visual Studio Code">Visual Studio Code</a> pour vous y aider.</p>

                        <p>Utilisez l'interface de développement de votre navigateur. Appuyez sur <kbd>F12</kbd> pour l'ouvrir. Avec vous pourrez observer le style CSS appliqué pour chaque élément, vous pourrez pointer un élément dans la page pour voir son code HTML, etc.</p>

                    </div>
                </div>
            </div>

        </div>

    </div>
    <!-- [FIN] Tout le texte des explications -->


    <!-- Formulaire de connexion -->
    <div class="col-sm-4"> 
        <form class="form-signin col-sm-12" method="post" id="formConnect">

            <h1 class="h3 mb-3 font-weight-normal">Merci de vous connecter</h1>

            <div class="form-group">
                <label for="inputEmail" class="sr-only">Adresse email</label>
                <input name="email" type="email" id="inputEmail" class="form-control" placeholder="Adresse email" required>
                <small class="form-text text-muted">Votre adresse email ne sera partagé avec aucun autre site.</small>
            </div>

            <div class="form-group">
                <label for="inputPassword" class="sr-only">Mot de passe</label>
                <input name="passwd" type="password" id="inputPassword" class="form-control" placeholder="Mot de passe" pattern="^[a-zA-Z0-9_\-\.]{5,15}$" required>
                <small class="form-text text-muted">Le mot de passe ne doit pas avoir d'espace et doit faire entre 5 et 15 caractères de long. (inspectez l'attribut <code>pattern</code> du input avec <kdb>F12</kdb> pour observer l'expression régulière).</small>
            </div>

            <div class="form-check">
                <input name="isRegister" type="checkbox" value="1" class="form-check-input" id="inputReg" <?=$checkedInitStateHtml?>>
                <label class="form-check-label">S'enregistrer</label>
            </div>

            <button id="form-submit-btn" class="btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#modalSubmit" type="button">Se connecter</button>

            <input id="form-submit" type="submit" value="valider et envoyer" class="d-none">
        </form>


    </div>
    <!-- [FIN] Formulaire de connexion -->


    <!-- La div suivante, ainsi que ses filles, représente la fenêtre qui s'ouvre au moment de cliquer sur "Se connecter" -->
    <div class="modal fade" tabindex="-1" role="dialog" id="modalSubmit">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Soumission du formulaire</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Vous avez cliquez pour envoyer le formulaire. Voici ce qu'il va se passer une fois que vous aurez cliqué sur continuer :</p>
                    <ul>
                        <li>Les données vont être vérifiés avant envoie. Puis, si OK, le formulaire les enverra (<code>method="post"</code>) sur cette même page.</li>
                        <li>Les données postées sont les suivantes :
                            <div class="row grey">
                                <div class="col-4">Contenu de la variable PHP $_POST après transmission du formulaire :</div>
                                <div class="col" id="divMail">Aucune donnée</div>
                            </div>
                        </li>
                        <li>C'est donc ce fichier <code>index.php</code> qui va vérifier les données, vérifier que l'utilisateur existe et si besoin créeer l'enregistrement.</li>
                        <li>Puis, la session va être initalisée (<code>$_SESSION['isConnected'] = true</code>).</li>
                        <li>Et si tout est OK, l'utilisateur est redirigé vers la page "Accueil" <code>mainPage.php</code></li>

                    </ul>
                </div>
                <div class="modal-footer">
                    <button onclick="submitForm()" type="button" class="btn btn-primary">Continuer</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                </div>
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

    function onInputChange() {
        var eltMail = document.getElementById("inputEmail");
        var eltPass = document.getElementById("inputPassword");
        var eltReg = document.getElementById("inputReg");
        var eltButton = document.getElementById("form-submit-btn");



        var divMail = document.getElementById("divMail");

        var txt = "$_POST['email'] = \"" + eltMail.value + "\";<br>" +
            "$_POST['passwd'] = \"" + eltPass.value + "\";<br>";
        if (eltReg.checked) {
            txt += "$_POST['isRegister'] = " + eltReg.value + ";<br>";
            eltButton.innerText = "S'enregistrer";
        } else {
            eltButton.innerText = "Se connecter";
        }
        
        divMail.innerHTML = txt;
    }

    document.getElementById("inputEmail").onkeyup = onInputChange;
    document.getElementById("inputPassword").onkeyup = onInputChange;
    document.getElementById("inputReg").onclick = onInputChange;
</script>

<?php
include("footer.php");
