<?php

class AutoCreateNewBdd
{
    
    /**
     * Fonction qui crée la BDD, et les tables.     * 
     */
    public static function create() {
        
        mysqli_report(MYSQLI_REPORT_STRICT); 

        $srv = BddUtils::$adresseServeurMysql;
        $usr = BddUtils::$utilisateurMysql;
        $pwd = BddUtils::$mdpMySql;
        $db  = BddUtils::$nomBdd;
        
        // Hors-programme (mais bien utile) :
        // Ici il s'agit d'une structure de programme appellé Try-catch.
        // Le principe est de mettre des instructions dans le corps du try. Si l'une d'elles échoue,
        // alors une exception sera lancée. Cette dernière peut-être recupérée par un catch. Un traitement
        // de l'exception peut alors être réalisé.
        // C'est le même principe que le Try-except vu en python au S1.
        try {
            // On essaie dans un premier temps de se connecter à la BDD 'bddExemple'.
            // Si la bdd n'existe pas, alors on va la créer.
            $dbb = BddUtils::connectBDD();
        } catch (Exception $ex) {
         
            // Si on arrive ici, c'est qu'il n'a pas été possible de se connecter à la BDD.
            // On va tester s'il s'agit d'un souci avec la BDD > si oui, on crée la BDD et les tables,
            // Sinon, on affichera l'erreur et on arrêtera le traitement.
            if (strpos($db, $ex->getMessage()) > 0) {
                die(sprintf(
                    "Une erreur s'est produite : %s<br><br>"
                    ."Vérifiez les identifiants de connexion à la BDD dans le fichier BddUtils (situé ici '%s')",
                    $ex->getMessage(),
                    "php/BddUtils.php"
                ));
            }

            $dbb = new mysqli($srv,$usr,$pwd);
            
            $requeteSql = "CREATE DATABASE IF NOT EXISTS `bddexemple` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
            $_SESSION['requeteSqlMemoire'][] = $requeteSql;
            $dbb->query($requeteSql);
            $dbb->close();

            $dbb = BddUtils::connectBDD();

            $requeteSql = "DROP TABLE IF EXISTS `personne`";
            $_SESSION['requeteSqlMemoire'][] = $requeteSql;
            $dbb->query($requeteSql);          
            
            $requeteSql = "CREATE TABLE IF NOT EXISTS `personne` (  `idpersonne` int(11) NOT NULL AUTO_INCREMENT,   `nompersonne` varchar(50) NOT NULL,   passwd varchar(255) NOT NULL,   PRIMARY KEY (`idpersonne`) )  ENGINE=InnoDB DEFAULT CHARSET=utf8";
            $_SESSION['requeteSqlMemoire'][] = $requeteSql;
            $dbb->query($requeteSql);    

            $requeteSql = "DROP TABLE IF EXISTS `image`";
            $_SESSION['requeteSqlMemoire'][] = $requeteSql;
            $dbb->query($requeteSql);  

            $requeteSql = "CREATE TABLE `image` ( `idimage` INT NOT NULL AUTO_INCREMENT , `nomImage` VARCHAR(100) NOT NULL ,  `path` TEXT NOT NULL, `idpersonne` int(11) NOT NULL, PRIMARY KEY (`idimage`))  ENGINE=InnoDB DEFAULT CHARSET=utf8";
            $_SESSION['requeteSqlMemoire'][] = $requeteSql;
            $dbb->query($requeteSql);  

            $requeteSql = "ALTER TABLE `image` ADD FOREIGN KEY (`idpersonne`) REFERENCES `personne` (`idpersonne`)";
            $_SESSION['requeteSqlMemoire'][] = $requeteSql;
            $dbb->query($requeteSql);
                        
            $dbb->close();
        }
        
        

    }

    public static function razSite() {
        $srv = BddUtils::$adresseServeurMysql;
        $usr = BddUtils::$utilisateurMysql;
        $pwd = BddUtils::$mdpMySql;


        $dbb = new mysqli($srv,$usr,$pwd);
            
        $dbb->query("DROP DATABASE `bddexemple`");
        $dbb->close();
    }
    
}

