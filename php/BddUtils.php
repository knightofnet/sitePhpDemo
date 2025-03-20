<?php

class BddUtils
{
    /** @var string L'adresse du serveur. */
    public static $adresseServeurMysql = "127.0.0.1";

    /** @var string Le nom d'utilisateur pour se connecter à la BDD. */
    public static $utilisateurMysql = "root";

    /** @var string Le mot de passe de l'utilisateur pour se connecter à la BDD. */
    public static $mdpMySql = "";


    /** @var string le nom de la BDD. A NE PAS MODIFIER. */
    public static $nomBdd = "bddexemple";


    public static function connectBDD(): \PDO
    {

        $dsn = "mysql:host=" . self::$adresseServeurMysql . ";dbname=" . self::$nomBdd . ";charset=utf8";

        $db = new PDO(
            $dsn,
            self::$utilisateurMysql,
            self::$mdpMySql
        );

        //echo 'Succés... ' . $db->host_info . "\n";

        return $db;
    }

    public static function connectSgbdNoBdd(): \PDO
    {

        $dsn = "mysql:host=" . self::$adresseServeurMysql . ";charset=utf8";

        $db = new PDO(
            $dsn,
            self::$utilisateurMysql,
            self::$mdpMySql
        );

        //echo 'Succés... ' . $db->host_info . "\n";

        return $db;
    }
}
