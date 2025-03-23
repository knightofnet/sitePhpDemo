<?php
/*
 * Fichier : 
 *      mainPage.php 
 * Nom de la page :
 *      Page principale de l'espace membre.
 * Description :
 *      Page principale de l'espace membre.
 * Traitements possibles :
 *      - Normal : affiche la page (si aucun $_GET)
 * 
 */

// Cette instruction se retrouve dans toutes les pages :
// Elle permet d'inclure les fichiers PHP nécessaires au fonctionnement du site, ainsi que
// les éléments en commun pour que le site fonctionne.
//
// Le fichier initCore.php est chargé : c'est comme si son code était écrit ici.
require_once("../initCore.php");

activeNavbar($navbarHtml, 'bddEnDetail');

include("../header.php");
?>


<div class="row">

    <div class="col-12">
        <h1>La Base de données en détail</h1>
    </div>

    <div class="col-sm-12 mb-3">
        <h2>Conception de la base de données</h2>
        <p>La base de données de ce site de démonstration est nommée : <code>bddexemple</code>.</p>
        <p>
            Son MCD est composé de 2 entités : personne et image. Une personne est identifiée par un identifiant (<code>idpersonne</code>), et d'autres attributs comme le nom/email (<code>nom</code>) et un mot de passe (<code>passwd</code>). Une image est identifiée par un identifiant (<code>idimage</code>), et d'autres attributs comme le nom de l'image (<code>nomImage</code>), le chemin de l'image sur le serveur (<code>path</code>).
        </p>
        <p>
            Une personne peut s'enregistrer sur le site. Une fois enregistrée, elle peut ajouter des images (0,N). Les fichiers des images sont stockés sur le serveur de fichiers, et les informations des images sont stockées en base de données. Une image est associée à une et une seule personne (1,1).
        </p>
        <p>On a le MCD suivant :</p>
        <p>
            <img src="../imgsite/MCD_mocodo.png" alt="MCD">
        </p>

        <p>
            Code pour reproduire ce MCD sur <a href="https://www.mocodo.net/?mcd=eNoLSC0qzs_LS1WwUshMKYBydBTy8nN1FAoSi4vLU7gcs_JLS1KL4jNzE9OBUgZ-CgFwdYaGCp4gYS4wCTYEqgxogieEVZBYkgEATj0jRA==" target="_blank">MOCODO</a> :
        </p>
        <pre class="codeSql"><code>Personne : idpersonne, nom, passwd
Ajouter_image, 0N Personne, 11 Image
Image : idimage, nomImage, path</code></pre>
    </div>

    <div class="col-sm-12 mb-3">
        <h2>Structure de la base de données</h2>

        <p>
            Le MCD précédent a ensuite été transformé en MLD :
        </p>

        <ul>
            <li>Règle 1 : Chaque entité du MCD devient une table dans le MLD, avec les attributs comme colonnes.</li>
            <li>Règle 2 : Pour les associations fonctionnelles, on ajoute une clé étrangère dans la table du côté de la cardinalité (x,1).</li>
            <li class="text-secondary">Règle 3 : Pour les associations porteuses, on crée une table de relation dédiée, avec deux clés étrangères pointant vers les clés primaires des deux tables en relation. Pas utilisée ici.</li>
        </ul>

        <p>
            On obtient le MLD suivant :
        </p>

        <p>
            <img src="../imgsite/MLD_mocodo.png" alt="MLD">
        </p>

        <p>
            Code pour reproduire ce MLD sur <a href="https://www.mocodo.net/?mcd=eNpTVc3NT85Pyeey4gpILSrOz8tLtVLITCmAsnUU8vJzdRQKEouLy1OAajxzE9PBCjJBDLCsJ4RVkFiSoaOgjNCqYKcQgGAixIGmAAAjtCen" target="_blank">MOCODO</a> :
        </p>
        <pre class="codeSql">:
Personne: idpersonne, nom, passwd
:
Image: idimage, nomImage, path, #idpersonne > Personne > idpersonne
:</pre>
    </div>

    <div class="col-sm-12 mb-3">
        <h2>MPD de la base de données</h2>

        <p>
            Le MLD précédent a ensuite été transformé en MPD pour la base de données MySQL/MariaDB :
        </p>
        <p>
            On obtient le MPD suivant :
        </p>
        <p>
            <img src="../imgsite/MPD.png" alt="MPD">
        </p>
    </div>

    <div class="col-sm-12 mb-3">
        <h2>Du MPD à la base de données</h2>

        <p>
            Le MPD précédent a ensuite été transformé en base de données pour MySQL/MariaDB. Cette opération peut se faire directement dans un outil comme PhpMyAdmin, ou alors en écrivant directement le script SQL pour créer la base de données. Voici le script SQL pour créer cette base de données :
        </p>

        <div class="codeSql">
            <ul>
                <li><span class="comment">--</span></li>
                <li><span class="comment">-- Base de données : </span><span class="string">`bddexemple`</span></li>
                <li><span class="comment">--</span></li>
                <li><span class="keyword">CREATE DATABASE</span> <span class="keyword">IF NOT EXISTS</span> <span class="string">`bddexemple`</span> <span class="keyword">DEFAULT CHARACTER SET</span> <span class="string">utf8mb3</span> <span class="keyword">COLLATE</span> <span class="string">utf8mb3_general_ci</span>;</li>
                <li><span class="keyword">USE</span> <span class="string">`bddexemple`</span>;</li>
                <li></li>
                <li><span class="comment">-- --------------------------------------------------------</span></li>
                <li></li>
                <li><span class="comment">--</span></li>
                <li><span class="comment">-- Structure de la table `image`</span></li>
                <li><span class="comment">--</span></li>
                <li></li>
                <li><span class="keyword">DROP TABLE IF EXISTS</span> <span class="string">`image`</span>;</li>
                <li><span class="keyword">CREATE TABLE</span> <span class="string">`image`</span> (</li>
                <li> <span class="string">`idimage`</span> <span class="keyword">int</span>(<span class="number">11</span>) <span class="keyword">NOT NULL</span>,</li>
                <li> <span class="string">`nomImage`</span> <span class="keyword">varchar</span>(<span class="number">100</span>) <span class="keyword">NOT NULL</span>,</li>
                <li> <span class="string">`path`</span> <span class="keyword">text</span> <span class="keyword">NOT NULL</span>,</li>
                <li> <span class="string">`idpersonne`</span> <span class="keyword">int</span>(<span class="number">11</span>) <span class="keyword">NOT NULL</span></li>
                <li>) <span class="keyword">ENGINE=InnoDB DEFAULT CHARSET</span>=<span class="string">utf8mb3</span> <span class="keyword">COLLATE</span>=<span class="string">utf8mb3_general_ci</span>;</li>
                <li></li>
                <li><span class="comment">-- --------------------------------------------------------</span></li>
                <li></li>
                <li><span class="comment">--</span></li>
                <li><span class="comment">-- Structure de la table `personne`</span></li>
                <li><span class="comment">--</span></li>
                <li></li>
                <li><span class="keyword">DROP TABLE IF EXISTS</span> <span class="string">`personne`</span>;</li>
                <li><span class="keyword">CREATE TABLE</span> <span class="string">`personne`</span> (</li>
                <li> <span class="string">`idpersonne`</span> <span class="keyword">int</span>(<span class="number">11</span>) <span class="keyword">NOT NULL</span>,</li>
                <li> <span class="string">`nompersonne`</span> <span class="keyword">varchar</span>(<span class="number">50</span>) <span class="keyword">NOT NULL</span>,</li>
                <li> <span class="string">`passwd`</span> <span class="keyword">varchar</span>(<span class="number">255</span>) <span class="keyword">NOT NULL</span></li>
                <li>) <span class="keyword">ENGINE=InnoDB DEFAULT CHARSET</span>=<span class="string">utf8mb3</span> <span class="keyword">COLLATE</span>=<span class="string">utf8mb3_general_ci</span>;</li>
                <li></li>
                <li><span class="comment">--</span></li>
                <li><span class="comment">-- Index pour les tables déchargées</span></li>
                <li><span class="comment">--</span></li>
                <li></li>
                <li><span class="comment">--</span></li>
                <li><span class="comment">-- Index pour la table `image`</span></li>
                <li><span class="comment">--</span></li>
                <li><span class="keyword">ALTER TABLE</span> <span class="string">`image`</span></li>
                <li> <span class="keyword">ADD PRIMARY KEY</span> (<span class="string">`idimage`</span>),</li>
                <li> <span class="keyword">ADD KEY</span> <span class="string">`idpersonne`</span> (<span class="string">`idpersonne`</span>);</li>
                <li></li>
                <li><span class="comment">--</span></li>
                <li><span class="comment">-- Index pour la table `personne`</span></li>
                <li><span class="comment">--</span></li>
                <li><span class="keyword">ALTER TABLE</span> <span class="string">`personne`</span></li>
                <li> <span class="keyword">ADD PRIMARY KEY</span> (<span class="string">`idpersonne`</span>);</li>
                <li></li>
                <li><span class="comment">--</span></li>
                <li><span class="comment">-- AUTO_INCREMENT pour les tables déchargées</span></li>
                <li><span class="comment">--</span></li>
                <li></li>
                <li><span class="comment">--</span></li>
                <li><span class="comment">-- AUTO_INCREMENT pour la table `image`</span></li>
                <li><span class="comment">--</span></li>
                <li><span class="keyword">ALTER TABLE</span> <span class="string">`image`</span></li>
                <li> <span class="keyword">MODIFY</span> <span class="string">`idimage`</span> <span class="keyword">int</span>(<span class="number">11</span>) <span class="keyword">NOT NULL AUTO_INCREMENT</span>;</li>
                <li></li>
                <li><span class="comment">--</span></li>
                <li><span class="comment">-- AUTO_INCREMENT pour la table `personne`</span></li>
                <li><span class="comment">--</span></li>
                <li><span class="keyword">ALTER TABLE</span> <span class="string">`personne`</span></li>
                <li> <span class="keyword">MODIFY</span> <span class="string">`idpersonne`</span> <span class="keyword">int</span>(<span class="number">11</span>) <span class="keyword">NOT NULL AUTO_INCREMENT</span>;</li>
                <li></li>
                <li><span class="comment">--</span></li>
                <li><span class="comment">-- Contraintes pour les tables déchargées</span></li>
                <li><span class="comment">--</span></li>
                <li></li>
                <li><span class="comment">--</span></li>
                <li><span class="comment">-- Contraintes pour la table `image`</span></li>
                <li><span class="comment">--</span></li>
                <li><span class="keyword">ALTER TABLE</span> <span class="string">`image`</span></li>
                <li> <span class="keyword">ADD CONSTRAINT</span> <span class="string">`image_ibfk_1`</span> <span class="keyword">FOREIGN KEY</span> (<span class="string">`idpersonne`</span>) <span class="keyword">REFERENCES</span> <span class="string">`personne`</span> (<span class="string">`idpersonne`</span>);</li>
                <li><span class="keyword">COMMIT</span>;</li>
            </ul>

        </div>

    </div>

    <div class="col-sm-12 mb-3">
        <h2>Spécificité de ce site : auto-création de la BDD</h2>

        <p>
            Afin de faciliter la mise en place de ce site de démonstration sur un serveur web local, un script PHP a été créé pour auto-créer la base de données. Le tout se passe dans le fichier
            <a href="https://github.com/knightofnet/sitePhpDemo/blob/master/php/AutoCreateNewBdd.php" target="_blank"><code>AutoCreateNewBdd.php</code></a>.
        </p>

        <p>
            Le code fonctionne ainsi à chaque chargement de page :
        </p>

        <ul>
            <li>On tente de se connecter à la base de données <code>bddexemple</code>.</li>
            <li>Si la connexion échoue, on crée la base de données <code>bddexemple</code> et les tables <code>personne</code> et <code>image</code> (l'échec peut aussi être dû à un problème avec les informations de connexion. On examine le message d'erreur pour essayer de déterminer si c'est le cas).</li>
            <li>
                Sinon, si la connexion réussit, on ne fait rien.
            </li>
            <li>
                Le site se charge normalement.
            </li>
        </ul>

        <p>
            Cette façon de faire est totalement hors-sujet avec un site en production, mais est très pratique pour un site de démonstration. <span class="fw-bold">Vous ne devez pas utiliser ce script</span>.
        </p>

    </div>


</div>
<?php
include("../footer.php");
?>