# Site PHP de démonstration

Ce site web est à usage pédagogique, à destination d'étudiants de 1er cycle débutant dans la programmation web. En utilisant PHP comme langage serveur, il illustre des concepts tels que la séparation des fichiers PHP/HTML, l’inclusion de fichiers, l'utilisation des sessions, etc.

Ce dépôt contient l'ensemble des fichiers nécessaires au fonctionnement du site de démonstration.

## Prérequis techniques

- Serveur web local (Apache, WampServer, XAMPP, etc.)
- PHP installé (version >= 7.4 recommandée)
- MySQL ou MariaDB installé et fonctionnel

## Installation

1. Télécharger les fichiers depuis le [dépôt GitHub (fichier ZIP)](https://github.com/knightofnet/sitePhpDemo/archive/refs/heads/master.zip).
2. Créer un nouveau dossier à la racine du serveur web. Exemple : `sitePhpDemo`.
3. Placer les fichiers téléchargés dans ce dossier.
4. Configurer l'accès à la base de données en modifiant le fichier `initCore.php`.
5. Accéder au site depuis un navigateur internet à l'adresse suivante (selon votre configuration) : [http://localhost/sitePhpDemo](http://localhost/sitePhpDemo).

## Remarques

- Le site crée automatiquement sa base de données nommée `'bddexemple'` si elle n'existe pas (MySQL ou MariaDB doit être installé et accessible via PHP).
- Sur les navigateurs récents, vous pouvez ouvrir les outils de développement avec la touche **F12**. Ils permettent d'inspecter le code HTML, les styles CSS, ainsi que d'autres informations utiles.
- En complément de la navigation sur le site, il est fortement recommandé d'explorer directement les fichiers PHP du projet. Pour une expérience optimale, vous pouvez utiliser un environnement de développement intégré (EDI/IDE). Comme outil gratuit, vous pouvez par exemple utiliser _Visual Studio Code_, avec les extensions suivantes :
  - **PHP Intelephense** : Après installation, cliquez sur la roue dentée puis sur "Extension settings" pour ajuster les paramètres.
  - **Format HTML in PHP** : pour faciliter la lecture et l'édition des fichiers contenant du HTML et du PHP.

