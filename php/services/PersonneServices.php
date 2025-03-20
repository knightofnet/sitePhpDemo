<?php


class PersonneServices
{
    public static function isThereOneUser(\PDO $dbb) {
        $requeteSql = "SELECT count(*)  as C FROM personne";
        $_SESSION['requeteSqlMemoire'][] = $requeteSql;

        $res = $dbb->query($requeteSql);
        
        while ( ($l=$res->fetch(PDO::FETCH_ASSOC)) != null) {
            
            return $l['C'] > 0;
        }
        
        return false;
    }


    /**
     * Indique sur l'utilisateur transmis dans la variable $nom existe dans la table 'personne'
     * 
     * @param \PDO $dbb La connexion à la BDD.
     * @param string $nom Le nom d'utilisateur à tester.
     * 
     * @return boolean Renvoie true si l'utilisateur existe, false sinon.
     */
    public static function isUserExists(\PDO $dbb, $nom) {
        $requeteSql = "SELECT count(*)  as C FROM personne WHERE nompersonne='$nom'";
        $_SESSION['requeteSqlMemoire'][] = $requeteSql;

        $res = $dbb->query($requeteSql);
        
        while ( ($l=$res->fetch(PDO::FETCH_ASSOC)) != null) {
            
            return $l['C'] == 1;
        }
        
        return false;
    }
    
    public static function isUserExistsWithPassword(\PDO $dbb, $nom, $passwd) {
        
        $requeteSql = "SELECT count(*) as C FROM personne WHERE nompersonne='$nom' AND passwd='$passwd'";
        $_SESSION['requeteSqlMemoire'][] = $requeteSql;
        
        $res = $dbb->query($requeteSql );
        
        while ( ($l=$res->fetch(PDO::FETCH_ASSOC)) != null) {
            
            return $l['C'] == 1;
        }
        
        return false;
        
    }
    
    public static function getUserId(\PDO $dbb, $nom, $passwd) {
        
        $requeteSql = "SELECT idpersonne FROM personne WHERE nompersonne='$nom' AND passwd='$passwd'";
        $_SESSION['requeteSqlMemoire'][] = $requeteSql;
        
        $res = $dbb->query($requeteSql );
        
        while ( ($l=$res->fetch(PDO::FETCH_ASSOC)) != null) {
            
            return $l['idpersonne'];
        }
        
        return -1;
        
    }

    public static function getUserById(\PDO $dbb, $idPersonne) {
        
        $requeteSql = "SELECT nompersonne FROM personne WHERE idpersonne=$idPersonne";
        $_SESSION['requeteSqlMemoire'][] = $requeteSql;
        
        $res = $dbb->query($requeteSql );
        
        while ( ($l=$res->fetch(PDO::FETCH_ASSOC)) != null) {
            
            return $l['nompersonne'];
        }
        
        return null;
        
    }

    public static function addNewPersonne(\PDO $dbb, $nom, $passwd) {
        $requeteSql = "INSERT personne(nompersonne, passwd) VALUES ('$nom', '$passwd')";
        $_SESSION['requeteSqlMemoire'][] = $requeteSql;

        $dbb->query($requeteSql);

        return $dbb->lastInsertId();
    }
    
}