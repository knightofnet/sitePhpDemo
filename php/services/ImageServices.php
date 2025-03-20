<?php

class ImageServices
{
    
    public static function getAllImages(\PDO $dbb) {
        
        $requeteSql = "SELECT * FROM image";
        $_SESSION['requeteSqlMemoire'][] = $requeteSql;

        $resultat = $dbb->query($requeteSql);
        
        $tabloEnSortie = [];
        while(($l=$resultat->fetch(PDO::FETCH_ASSOC))) {
            $tabloEnSortie[] = $l;
        }
        
        return $tabloEnSortie;
        
    }

    public static function getAllImagesFromUserId(\PDO $dbb, $userId) {
        
        $requeteSql = "SELECT * FROM image WHERE idpersonne=$userId";
        $_SESSION['requeteSqlMemoire'][] = $requeteSql;

        $resultat = $dbb->query($requeteSql);
        
        $tabloEnSortie = [];
        while(($l=$resultat->fetch(PDO::FETCH_ASSOC))) {
            $tabloEnSortie[] = $l;
        }
        
        return $tabloEnSortie;
        
    }
    
    public static function ajouteNouvelleImage(\PDO $dbb, $nomImage, $urlServeur, $userId) {
        $requeteSql = "INSERT into image(nomImage, path, idpersonne ) VALUES ('$nomImage', '$urlServeur', $userId)";
        $_SESSION['requeteSqlMemoire'][] = $requeteSql;

        $dbb->query($requeteSql);

        // Avec $dbb->insert_id, on récupère l'identifiant de la dernière insertion, c'est-à-dire celui de l'image insérée.
        return $dbb->lastInsertId();
    }

    public static function isExistsImageWithId(\PDO $dbb, $idImage) {
        $requeteSql = "SELECT count(*) as C FROM image WHERE idimage = ".$idImage;
        $_SESSION['requeteSqlMemoire'][] = $requeteSql;

        $res = $dbb->query($requeteSql);
        
  
        while ( ($l=$res->fetch(PDO::FETCH_ASSOC)) != null) {
            
            return $l['C'] == 1;
        }
        
        return false;
    }
        
    public static function getImageWithId(\PDO $dbb, $idImage) {
        $requeteSql = "SELECT nomImage, path, idpersonne FROM image WHERE idimage = ".$idImage;
        $_SESSION['requeteSqlMemoire'][] = $requeteSql;

        $res = $dbb->query($requeteSql);
        
        if ( ($l=$res->fetch(PDO::FETCH_ASSOC)) != null) {
            
            return $l;
        }

        return null;
    }

    public static function deleteImageAvecId(\PDO $dbb, $idImage) {
        $requeteSql = "DELETE FROM image WHERE idimage = ".$idImage;
        $_SESSION['requeteSqlMemoire'][] = $requeteSql;

        $dbb->query($requeteSql);
    }
    
    
}

