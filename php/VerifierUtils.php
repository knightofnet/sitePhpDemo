<?php

/**
 * Classe PHP permettant de ranger/d'organiser quelques fonctions pour la vérification de données
 * transmises par les formulaires.
 * 
 * Rappel : il faut toujours vérifier les données lorsqu'on traite celles envoyées par un formulaire.
 */
class VerifierUtils
{

    /**
     * Fonction permettant de vérifier que la chaîne transmise contient un entier.
     * 
     * Exemple d'utilisation dans le fichier .\deleteImage.php
     * 
     * @param string $entierAtester La chaîne à tester.
     * @return int L'entier converti ou -1 si la chaîne n'est pas un entier valide.
     */
    public static function verifierEstEntier($entierAtester)
    {
        if (preg_match("#^\d+$#", $entierAtester)) {
            return intval($entierAtester);
        }
        return -1;
    }

    /**
     * Fonction permettant de vérifier si l'action GET transmise correspond à "logout".
     * 
     * @param string $aVerifier L'action à vérifier.
     * @return string L'action validée ou une chaîne vide si elle est invalide.
     */
    public static function verifierGetAction($aVerifier)
    {
        if (preg_match("#^logout$#", $aVerifier)) {
            return $aVerifier;
        }

        return "";
    }

    /**
     * Fonction permettant de vérifier si une valeur correspond à un champ booléen.
     * 
     * @param mixed $valeurAverifier La valeur à vérifier.
     * @return bool True si la valeur est "1", sinon False.
     */
    public static function verifierChampsBooleen($valeurAverifier)
    {
        // Si la variable $valeurAverifier est null ou si elle ne contient qu'un espace,
        // on renvoie faux (false).
        if (empty($valeurAverifier)) {
            return false;
        }

        // Sinon, on teste si elle vaut 1 (ce qui est synonyme de vrai (true)).
        return $valeurAverifier == "1";
    }

    /**
     * Fonction permettant de vérifier si une valeur est une adresse email valide.
     * 
     * @param string $valeurAverifier L'adresse email à vérifier.
     * @return string|null L'adresse email validée ou null si elle est invalide.
     */
    public static function verifierEmail($valeurAverifier)
    {
        return filter_var($valeurAverifier, FILTER_VALIDATE_EMAIL) ? $valeurAverifier : null;
    }

    /**
     * Fonction permettant de vérifier si un mot de passe est valide.
     * 
     * @param string $valeurAverifier Le mot de passe à vérifier.
     * @return string|null Le mot de passe validé ou null s'il est invalide.
     */
    public static function verifierMotDePasse($valeurAverifier)
    {
        if (empty($valeurAverifier)) {
            return null;
        }

        // On vérifie que la chaîne contient entre 5 et 15 caractères
        // alphanumériques, ou des caractères spéciaux _ - .        
        if (preg_match("/^[a-zA-Z0-9_\-\.]{5,15}$/m", $valeurAverifier)) {
            return $valeurAverifier;
        }

        return null;
    }
}
