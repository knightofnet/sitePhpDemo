<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

/*
* Définition des constantes pour la connexion à la BDD.
* 
* À MODIFIER SELON VOTRE CONFIGURATION.
* À MODIFIER SELON VOTRE CONFIGURATION.
* À MODIFIER SELON VOTRE CONFIGURATION.
*/
// L'adresse du serveur de base de données.
define("BDD_ADRESSE_SERVEUR", "127.0.0.1");
// Le port du serveur de base de données.
define("BDD_PORT", '3306');
// L'utilisateur pour se connecter à la BDD.
define("BDD_UTILISATEUR", 'root');
// Le mot de passe de l'utilisateur pour se connecter à la BDD.
define("BDD_MDP", '');

// ===============================================

/* On définit 2 constantes :
     * - DIR_SRV : chemin du projet côté serveur  (exemple : c:\wamp\www\phpExempleUtilisationSession)
     * - URL_SITE : l'URL du site (exemple : http:\\localhost\phpExempleUtilisationSession)
     * 
     * Ici, la valeur de ces constantes est définie de façon dynamique.
     */
define("DIR_SRV", str_replace('\\', '/', __DIR__) . "/");
$s = explode("/", $_SERVER['SCRIPT_NAME']);
define("URL_SITE", "http://" . $_SERVER['HTTP_HOST'] . '/' . $s[1]);


define("BDD_NOM_BASE_DE_DONNEES", "bddexemple");

define("PWD_SALT", 'a1z2e3r4t5y6u7i8o9p0_');

// On charge les fichiers nécessaires pour le site.
// - Ils sont chargés une seule fois ("require_once").
require_once(DIR_SRV . 'php/AutoCreateNewBdd.php');
require_once(DIR_SRV . 'php/BddUtils.php');
require_once(DIR_SRV . 'php/VerifierUtils.php');

require_once(DIR_SRV . 'php/services/PersonneServices.php');
require_once(DIR_SRV . 'php/services/ImageServices.php');

// On démarre la session avec session_start() :
session_start();

// Crée la BDD si elle n'existe pas. Uniquement pour la démo (à ne pas reproduire).
AutoCreateNewBdd::create();


// Gestion de la navbar
$navbarHtml = [
    'accueil' => [
        'nom' => 'Accueil',
        'lien' => isset($_SESSION['isConnected']) ? URL_SITE . '/pages/mainPage.php' : URL_SITE . '/index.php',
        'active' => false,
        'title' => isset($_SESSION['isConnected']) ? "Page d'accueil de l'utilisateur connecté" : "Page d'accueil"
    ],
    'bddEnDetail' => [
        'nom' => 'Base de données',
        'lien' => URL_SITE . '/pages/bddEnDetail.php',
        'active' => false,
        'title' => "Plus d'informations sur la base de données"
    ],
    'lstImage' => [
        'nom' => 'Liste images',
        'lien' => URL_SITE . '/pages/listImages.php',
        'active' => false,
        'title' => "Liste des images. Nécessite d'être connecté"
    ],
    'connect' => [
        'nom' => isset($_SESSION['isConnected']) ? "Déconnexion" : "Connexion",
        'lien' => URL_SITE . '/index.php' . (isset($_SESSION['isConnected']) ? '?action=logout' : ''),
        'active' => false,
        'title' => isset($_SESSION['isConnected']) ? "Cliquez pour vous déconnecter et retourner sur la page d'introduction" : "La page d'introduction, là où vous êtes actuellement"
    ],
    'razSite' => [
        'nom' => "R-à-Z",
        'lien' => URL_SITE . '/pages/razSite.php',
        'active' => false,
        'title' => "Page pour remettre à zéro le site"
    ],
    'consulterImage' => [
        'nom' => "Consulter une image",
        'lien' => URL_SITE . '/pages/consulterImage.php',
        'active' => false,
        'title' => "Consulter une image"
    ],


];

/**
 * Redirige l'utilisateur vers la page listImages.php avec un message d'erreur.
 * 
 * @param string $error Le message d'erreur à afficher.
 */
function redirectWithError($error)
{
    header("Location: " . URL_SITE . "/pages/listImages.php?error=" . $error);
    exit();
}

/**
 *
 */
/**
 * Capture la sortie de la fonction `var_dump` et la retourne sous forme de chaîne.
 *
 * Cette fonction est utile lorsque vous souhaitez inspecter le contenu d'une variable
 * sans l'afficher directement dans le navigateur ou la console.
 *
 * @param mixed $mixed La variable à analyser. Peut être de n'importe quel type.
 * @return string La sortie de `var_dump` sous forme de chaîne.
 */
function var_dump_ret($mixed = null)
{
    ob_start();
    var_dump($mixed);
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

/**
 * Convertit des octets en taille de fichier humainement compréhensible.
 *
 * @param string $bytes Taille en octets.
 * @return string Taille de fichier lisible par un humain.
 * 
 * @author Mogilev Arseny
 */
function fileSizeConvert($bytes)
{
    $bytes = floatval($bytes);
    $arBytes = array(
        0 => array(
            "UNIT" => "TB",
            "VALUE" => pow(1024, 4)
        ),
        1 => array(
            "UNIT" => "GB",
            "VALUE" => pow(1024, 3)
        ),
        2 => array(
            "UNIT" => "MB",
            "VALUE" => pow(1024, 2)
        ),
        3 => array(
            "UNIT" => "KB",
            "VALUE" => 1024
        ),
        4 => array(
            "UNIT" => "B",
            "VALUE" => 1
        ),
    );

    $result = $bytes;

    foreach ($arBytes as $arItem) {
        if ($bytes >= $arItem["VALUE"]) {
            $result = $bytes / $arItem["VALUE"];
            $result = str_replace(".", ",", strval(round($result, 2))) . " " . $arItem["UNIT"];
            break;
        }
    }
    return $result;
}
