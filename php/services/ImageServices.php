<?php

class ImageServices
{

    /**
     * Retourne toutes les images.
     * @param \PDO $dbb La connexion à la BDD.
     * @return array Un tableau contenant toutes les images.
     */
    public static function getAllImages(\PDO $dbb)
    {

        // On enregistre la requête en mémoire (dans la session) pour pouvoir l'afficher ensuite
        // (en bas de page). Ceci est purement pédagogique.
        // Pour la suite, on ne va pas préparer la requête SQL, car il n'y a pas de paramètre à passer. 
        $requeteSql = "SELECT * FROM image";
        $_SESSION['requeteSqlMemoire'][] = $requeteSql;

        // On exécute la requête SQL.
        $resultat = $dbb->query($requeteSql);

        // On récupère tous les résultats de la requête dans un array/tableau PHP.
        $tabloEnSortie = $resultat->fetchAll(PDO::FETCH_ASSOC);

        return $tabloEnSortie;
    }

    /**
     * Retourne toutes les images d'un utilisateur.
     * @param \PDO $dbb La connexion à la BDD.
     * @param int $userId L'identifiant de l'utilisateur.
     * @return array Un tableau contenant toutes les images de l'utilisateur.
     */
    public static function getAllImagesFromUserId(\PDO $dbb, $userId)
    {

        // On enregistre la requête en mémoire (dans la session) pour pouvoir l'afficher ensuite 
        // (en bas de page). Ceci est purement pédagogique.
        $_SESSION['requeteSqlMemoire'][] = "SELECT * FROM image WHERE idpersonne=$userId";

        // On écris la requête SQL avec un paramètre '?' pour le $userId.
        // On parle de requête préparée car elle ne contient pas directement la valeur de $userId.
        $requeteSql = "SELECT * FROM image WHERE idpersonne=?";

        // On prépare la requête SQL.
        $stmt = $dbb->prepare($requeteSql);
        // On associe le paramètre en 1ère position, avec la valeur $userId.
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        // On exécute la requête.
        $stmt->execute();

        // On récupère tous les résultats de la requête dans un array/tableau PHP.
        $tabloEnSortie = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $tabloEnSortie;
    }

    /**
     * Ajoute une nouvelle image.
     * 
     * @param \PDO $dbb La connexion à la BDD.
     * @param string $nomImage Le nom de l'image.
     * @param string $urlServeur L'URL de l'image.
     * @param int $userId L'identifiant de l'utilisateur.
     * 
     * @return int L'identifiant de l'image ajoutée.
     */
    public static function ajouteNouvelleImage(\PDO $dbb, $nomImage, $urlServeur, $userId)
    {

        // On enregistre la requête en mémoire (dans la session) pour pouvoir l'afficher ensuite 
        // (en bas de page). Ceci est purement pédagogique.
        $_SESSION['requeteSqlMemoire'][] = "INSERT into image(nomImage, path, idpersonne ) VALUES ('$nomImage', '$urlServeur', $userId)";

        // On écrit la requête SQL avec des paramètres '?' pour les valeurs à insérer.
        $requeteSql = "INSERT into image(nomImage, path, idpersonne ) VALUES (?, ?, ?)";
        // On prépare la requête SQL.
        $stmt = $dbb->prepare($requeteSql);

        // On associe les paramètres avec les valeurs.
        $stmt->bindValue(1, $nomImage, PDO::PARAM_STR);
        $stmt->bindValue(2, $urlServeur, PDO::PARAM_STR);
        $stmt->bindValue(3, $userId, PDO::PARAM_INT);

        // On exécute la requête.
        $stmt->execute();


        // Avec $dbb->lastInsertId(), on récupère l'identifiant de la dernière insertion, 
        // c'est-à-dire celui de l'image insérée.
        return $dbb->lastInsertId();
    }

    /**
     * Vérifie si une image existe avec un identifiant donné.
     * 
     * @param \PDO $dbb La connexion à la BDD.
     * @param int $idImage L'identifiant de l'image.
     * 
     * @return boolean Renvoie true si l'image existe, false sinon.
     */
    public static function isExistsImageWithId(\PDO $dbb, $idImage)
    {
        // On enregistre la requête en mémoire (dans la session) pour pouvoir l'afficher ensuite 
        // (en bas de page). Ceci est purement pédagogique.
        $_SESSION['requeteSqlMemoire'][] = "SELECT count(*) as C FROM image WHERE idimage = " . $idImage;

        // On écrit la requête SQL avec un paramètre '?' pour le $idImage.
        // On parle de requête préparée car elle ne contient pas directement la valeur de $idImage.
        $requeteSql = "SELECT count(*) as C FROM image WHERE idimage = ?";

        // On prépare la requête SQL.
        $stmt = $dbb->prepare($requeteSql);
        // On associe le paramètre en 1ère position, avec la valeur $idImage.
        $stmt->bindValue(1, $idImage, PDO::PARAM_INT);
        // On exécute la requête.
        $stmt->execute();

        // On récupère LE résultat de la requête.
        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);

        // La fonction retourne true si le résultat est égal à 1, false sinon.
        if ($resultat != null) {
            return $resultat['C'] == 1;
        }

        // Sinon, la fonction retourne false.
        return false;
    }

    /**
     * Retourne une image avec un identifiant donné.
     * 
     * @param \PDO $dbb La connexion à la BDD.
     * @param int $idImage L'identifiant de l'image.
     * 
     * @return array Un tableau contenant les informations de l'image.
     */
    public static function getImageWithId(\PDO $dbb, $idImage)
    {
        // On enregistre la requête en mémoire (dans la session) pour pouvoir l'afficher ensuite 
        // (en bas de page). Ceci est purement pédagogique.
        $_SESSION['requeteSqlMemoire'][] = "SELECT nomImage, path, idpersonne FROM image WHERE idimage = " . $idImage;

        // On écrit la requête SQL avec un paramètre '?' pour le $idImage.
        $requeteSql = "SELECT nomImage, path, idpersonne FROM image WHERE idimage = ?";
        // On prépare la requête SQL.
        $stmt = $dbb->prepare($requeteSql);
        // On associe le paramètre en 1ère position, avec la valeur $idImage.
        $stmt->bindValue(1, $idImage, PDO::PARAM_INT);
        // On exécute la requête.
        $stmt->execute();

        // On récupère LE résultat de la requête.
        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);

        // La fonction retourne le résultat si celui-ci n'est pas null.
        if ($resultat != null) {
            return $resultat;
        }

        // Sinon, la fonction retourne null.
        return null;
    }

    /**
     * Supprime une image avec un identifiant donné.
     * 
     * @param \PDO $dbb La connexion à la BDD.
     * @param int $idImage L'identifiant de l'image.
     */
    public static function deleteImageAvecId(\PDO $dbb, $idImage)
    {

        // On enregistre la requête en mémoire (dans la session) pour pouvoir l'afficher ensuite 
        // (en bas de page). Ceci est purement pédagogique.
        $_SESSION['requeteSqlMemoire'][] = "DELETE FROM image WHERE idimage = " . $idImage;

        // On écrit la requête SQL avec un paramètre '?' pour le $idImage.
        $requeteSql = "DELETE FROM image WHERE idimage = ?";
        // On prépare la requête SQL.
        $stmt = $dbb->prepare($requeteSql);
        // On associe le paramètre en 1ère position, avec la valeur $idImage.
        $stmt->bindValue(1, $idImage, PDO::PARAM_INT);
        // On exécute la requête.
        $stmt->execute();
    }
}
