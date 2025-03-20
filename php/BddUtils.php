<?php

class BddUtils
{


    public static function connectBDD(): \PDO
    {

        $dsn = "mysql:host=" . BDD_ADRESSE_SERVEUR . ";port=" . BDD_PORT . ";dbname=" . BDD_NOM_BASE_DE_DONNEES . ";charset=utf8";

        $db = new PDO(
            $dsn,
            BDD_UTILISATEUR,
            BDD_MDP
        );

        //echo 'Succés... ' . $db->host_info . "\n";

        return $db;
    }

    public static function connectSgbdNoBdd(): \PDO
    {
        try {
            $dsn = "mysql:host=" . BDD_ADRESSE_SERVEUR . ";port=" . BDD_PORT . ";charset=utf8";

            $db = new PDO(
                $dsn,
                BDD_UTILISATEUR,
                BDD_MDP
            );

            //echo 'Succés... ' . $db->host_info . "\n";

            return $db;
        } catch (Exception $e) {
            include_once("pages/connectionBddErreur.php");
            die();
        }
    }
}
