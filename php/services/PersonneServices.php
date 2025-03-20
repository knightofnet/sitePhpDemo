<?php


class PersonneServices
{
    /**
     * Indique s'il y a au moins un utilisateur dans la table 'personne'
     * 
     * @param \PDO $dbb La connexion à la BDD.
     * 
     * @return boolean Renvoie true s'il y a au moins un utilisateur, false sinon.
     */
    public static function isThereOneUser(\PDO $dbb)
    {
        // On enregistre la requête en mémoire (dans la session) pour pouvoir l'afficher ensuite 
        // (en bas de page). Ceci est purement pédagogique.
        // On ne prépare pas spécialement la requête car il n'y a pas de paramètre à protéger.
        $requeteSql = "SELECT count(*) as C FROM personne";
        $_SESSION['requeteSqlMemoire'][] = $requeteSql;

        // On exécute la requête SQL.
        $stmt = $dbb->query($requeteSql);

        // On récupère le résultat de la requête.
        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);

        // La fonction retourne true si le résultat est égal à 1, false sinon.
        if ($resultat != null) {
            return $resultat['C'] > 0;
        }

        // Sinon, la fonction retourne false.
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
    public static function isUserExists(\PDO $dbb, $nom)
    {
        // On enregistre la requête en mémoire (dans la session) pour pouvoir l'afficher ensuite 
        // (en bas de page). Ceci est purement pédagogique.       
        $_SESSION['requeteSqlMemoire'][] = "SELECT count(*)  as C FROM personne WHERE nompersonne='$nom'";

        // On écrit la requête SQL avec des paramètres '?' pour les valeurs à insérer
        $requeteSql = "SELECT count(*)  as C FROM personne WHERE nompersonne= ?";
        // On prépare la requête SQL
        $stmt = $dbb->prepare($requeteSql);
        // On associe les paramètres avec les valeurs
        $stmt->bindValue(1, $nom, PDO::PARAM_STR);
        // On exécute la requête
        $stmt->execute();

        // On récupère LE résultat de la requête
        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);

        // La fonction retourne le résultat si celui-ci n'est pas null
        if ($resultat != null) {
            return $resultat['C'] == 1;
        }

        // Sinon, la fonction retourne false


    }

    public static function isUserExistsWithPassword(\PDO $dbb, $nom, $passwd)
    {

        // On enregistre la requête en mémoire (dans la session) pour pouvoir l'afficher ensuite 
        // (en bas de page). Ceci est purement pédagogique.
        $_SESSION['requeteSqlMemoire'][] = "SELECT count(*) as C FROM personne WHERE nompersonne='$nom' AND passwd='$passwd'";

        // On écrit la requête SQL avec des paramètres '?' pour les valeurs à insérer
        $requeteSql = "SELECT count(*) as C FROM personne WHERE nompersonne= ? AND passwd= ?";
        // On prépare la requête SQL
        $stmt = $dbb->prepare($requeteSql);
        // On associe les paramètres avec les valeurs
        $stmt->bindValue(1, $nom, PDO::PARAM_STR);
        $stmt->bindValue(2, $passwd, PDO::PARAM_STR);
        // On exécute la requête
        $stmt->execute();

        // On récupère LE résultat de la requête
        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);

        // La fonction retourne le résultat si celui-ci n'est pas null
        if ($resultat != null) {
            return $resultat['C'] == 1;
        }

        // Sinon, la fonction retourne false
        return false;
    }

    /**
     * Retourne l'identifiant de l'utilisateur transmis dans la variable $nom
     * 
     * @param \PDO $dbb La connexion à la BDD.
     * @param string $nom Le nom de l'utilisateur.
     * @param string $passwd Le mot de passe de l'utilisateur.
     * 
     * @return int L'identifiant de l'utilisateur, ou -1 si l'utilisateur n'existe pas.
     */
    public static function getUserId(\PDO $dbb, $nom, $passwd)
    {

        // On enregistre la requête en mémoire (dans la session) pour pouvoir l'afficher ensuite 
        // (en bas de page). Ceci est purement pédagogique.
        $_SESSION['requeteSqlMemoire'][] = "SELECT idpersonne FROM personne WHERE nompersonne='$nom' AND passwd='$passwd'";

        // On écrit la requête SQL avec des paramètres '?' pour les valeurs à insérer
        $requeteSql = "SELECT idpersonne FROM personne WHERE nompersonne= ? AND passwd=?";
        // On prépare la requête SQL
        $stmt = $dbb->prepare($requeteSql);
        // On associe les paramètres avec les valeurs
        $stmt->bindValue(1, $nom, PDO::PARAM_STR);
        $stmt->bindValue(2, $passwd, PDO::PARAM_STR);
        // On exécute la requête
        $stmt->execute();

        // On récupère LE résultat de la requête
        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);

        // La fonction retourne le résultat si celui-ci n'est pas null
        if ($resultat != null) {
            return $resultat['idpersonne'];
        }

        // Sinon, la fonction retourne -1
        return -1;
    }

    public static function getUserById(\PDO $dbb, $idPersonne)
    {

        // On enregistre la requête en mémoire (dans la session) pour pouvoir l'afficher ensuite 
        // (en bas de page). Ceci est purement pédagogique.
        $_SESSION['requeteSqlMemoire'][] = "SELECT nompersonne FROM personne WHERE idpersonne=$idPersonne";

        $requeteSql = "SELECT nompersonne FROM personne WHERE idpersonne = ?";
        // On prépare la requête SQL
        $stmt = $dbb->prepare($requeteSql);
        // On associe les paramètres avec les valeurs
        $stmt->bindValue(1, $idPersonne, PDO::PARAM_INT);
        // On exécute la requête
        $stmt->execute();

        // On récupère LE résultat de la requête
        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);

        // La fonction retourne le résultat si celui-ci n'est pas null
        if ($resultat != null) {
            return $resultat['nompersonne'];
        }

        // Sinon, la fonction retourne null
        return null;
    }

    /**
     * Ajoute un nouvel utilisateur dans la table 'personne'
     * 
     * @param \PDO $dbb La connexion à la BDD.
     * @param string $nom Le nom de l'utilisateur.
     * @param string $passwd Le mot de passe de l'utilisateur.
     * 
     * @return int L'identifiant de l'utilisateur ajouté.
     */
    public static function addNewPersonne(\PDO $dbb, $nom, $passwd)
    {
        // On enregistre la requête en mémoire (dans la session) pour pouvoir l'afficher ensuite
        // (en bas de page). Ceci est purement pédagogique.
        $_SESSION['requeteSqlMemoire'][] = "INSERT personne(nompersonne, passwd) VALUES ('$nom', '$passwd')";

        // On écrit la requête SQL avec des paramètres '?' pour les valeurs à insérer
        $requeteSql = "INSERT personne(nompersonne, passwd) VALUES (?,?)";
        // On prépare la requête SQL
        $stmt = $dbb->prepare($requeteSql);
        // On associe les paramètres avec les valeurs
        $stmt->bindValue(1, $nom, PDO::PARAM_STR);
        $stmt->bindValue(2, $passwd, PDO::PARAM_STR);
        // On exécute la requête
        $stmt->execute();

        // On retourne l'identifiant de l'utilisateur ajouté
        return $dbb->lastInsertId();
    }
}
