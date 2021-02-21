<?php

/**
 * Classe PHP permettant de ranger/d'organiser quelques fonctions pour la vérification de données
 * transmises par les formulaires.
 * 
 * Rappel : il faut toujours vérifier les données quand on traite celles envoyées par un formulaire.
 */
class VerifierUtils
{
    
    /**
     * Fonction permettant de vérifier que la chaine transmise contient un entier.
     * 
     * Exemple d'utilisation dans le fichier .\deleteImage.php
     */
    public static function verifierEstEntier($entierAtester) {
        if( preg_match("#^\d+$#", $entierAtester)) {
            return $entierAtester;
        }
        return -1;
    }
    
    public static function verifierGetAction($aVerifier) {
        if( preg_match("#^logout$#", $aVerifier)) {
            return $aVerifier;
        }
        
        return "";
    }
    
    public static function verifierChampsBooleen($valeurAverifier) {
        // si la variable $valeurAverifier est null ou si elle ne contient qu'un espace,
        // on renvoie faux (false).
        if (empty($valeurAverifier)) {
            return false;
        }
        
        // sinon, on teste si elle vaut 1 (ce qui est synonyme de vrai(true) ) [sinon cela reverra false].
        return $valeurAverifier == "1";
    }
    
    public static function verifierEmail($valeurAverifier) {
        if (empty($valeurAverifier)) {
            return null;
        }

        if (preg_match("/^.+?@.+?\..+$/m", $valeurAverifier)) {
            return htmlspecialchars($valeurAverifier);
        }      

        return null;
    }

    public static function verifierMotDePasse($valeurAverifier) {
        if (empty($valeurAverifier)) {
            return null;
        }

        if (preg_match("/^[a-zA-Z0-9_\-\.]{5,15}$/m", $valeurAverifier)) {
            return $valeurAverifier;
        }      

        return null;
    }


    
}

