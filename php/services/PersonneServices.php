<?php


class PersonneServices
{
    public static function isThereOneUser(mysqli $dbb) {
        $requeteSql = "SELECT count(*)  as C FROM personne";
        $_SESSION['requeteSqlMemoire'][] = $requeteSql;

        $res = $dbb->query($requeteSql);
        
        while ( ($l=$res->fetch_assoc()) != null) {
            
            return $l['C'] > 0;
        }
        
        return false;
    }


    /**
     * Indique sur l'utilisateur transmis dans la variable $nom existe dans la table 'personne'
     * 
     * @param mysqli $dbb La connexion à la BDD.
     * @param string $nom Le nom d'utilisateur à tester.
     * 
     * @return boolean Renvoie true si l'utilisateur existe, false sinon.
     */
    public static function isUserExists(mysqli $dbb, $nom) {
        $requeteSql = "SELECT count(*)  as C FROM personne WHERE nompersonne='$nom'";
        $_SESSION['requeteSqlMemoire'][] = $requeteSql;

        $res = $dbb->query($requeteSql);
        
        while ( ($l=$res->fetch_assoc()) != null) {
            
            return $l['C'] == 1;
        }
        
        return false;
    }
    
    public static function isUserExistsWithPassword(mysqli $dbb, $nom, $passwd) {
        
        $requeteSql = "SELECT count(*) as C FROM personne WHERE nompersonne='$nom' AND passwd='$passwd'";
        $_SESSION['requeteSqlMemoire'][] = $requeteSql;
        
        $res = $dbb->query($requeteSql );
        
        while ( ($l=$res->fetch_assoc()) != null) {
            
            return $l['C'] == 1;
        }
        
        return false;
        
    }
    
    public static function getUserId(mysqli $dbb, $nom, $passwd) {
        
        $requeteSql = "SELECT idpersonne FROM personne WHERE nompersonne='$nom' AND passwd='$passwd'";
        $_SESSION['requeteSqlMemoire'][] = $requeteSql;
        
        $res = $dbb->query($requeteSql );
        
        while ( ($l=$res->fetch_assoc()) != null) {
            
            return $l['idpersonne'];
        }
        
        return -1;
        
    }

    public static function getUserById(mysqli $dbb, $idPersonne) {
        
        $requeteSql = "SELECT nompersonne FROM personne WHERE idpersonne=$idPersonne";
        $_SESSION['requeteSqlMemoire'][] = $requeteSql;
        
        $res = $dbb->query($requeteSql );
        
        while ( ($l=$res->fetch_assoc()) != null) {
            
            return $l['nompersonne'];
        }
        
        return null;
        
    }

    public static function addNewPersonne(mysqli $dbb, $nom, $passwd) {
        $requeteSql = "INSERT personne(nompersonne, passwd) VALUES ('$nom', '$passwd')";
        $_SESSION['requeteSqlMemoire'][] = $requeteSql;

        $dbb->query($requeteSql);

        return $dbb->insert_id;
    }
    
}