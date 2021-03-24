# Site PHP de démonstration #

Site web à usage pédagogique, à l'intention d'étudiants de 1er cycle débutant dans la programmation web. En utilisant PHP comme langage serveur, ce site illustre un ensemble de concepts tels que la segmentation des fichiers PHP/HTML, l’inclusion d’autres fichiers, les sessions, etc.

Vous êtes ici sur le dépôt du site Démo : c'est dans cet espace que sont stockés et centralisés les fichiers de code du site Démo.

 
## Installation

- Télécharger les fichiers [en cliquant ici](https://github.com/knightofnet/sitePhpDemo/archive/refs/heads/master.zip).
- Créer un nouveau dossier la racine du serveur web. Exemple : sitePhpDemo.
- Placez les fichiers du site dans ce dossier.
- Configurez l'accès à la BDD, en modifiant le fichier ./php/BddUtils.
- Naviguez sur ce site à l'aide de votre navigateur internet. Selon la configuration, le site est peut-être accessible à l'aide de cette URL : http://localhost/sitephpdemo.

## Remarques

- Le site crée sa base de données, nommée *'bddexemple'*, automatiquement si elle n'existe pas. 
- Sur les navigateurs internet récents, vous pouvez ouvrir les outils de développement avec la touche **F12**. Ils vous permettront d'inspecter le code HTML, le style CSS et bien plus encore.
- En plus de la navigation sur le site, il est indispensable d'ouvrir les différents fichiers PHP. Afin de rendre cela plus confortable, vous pouvez utiliser un EDI - Environnement de Developpement Integré - (IDE en anglais). Comme EDI gratuit, vous pouvez utiliser _Visual Studio Code_, avec les extensions suivantes (à installer une fois _Visual Studio Code_ installé) : 'PHP Intelephense*' et 'Format HTML in PHP'.


\* : Une fois PHP Intelephense installé, cliquez sur la petite roue dentée, puis sur "Extension settings" pour accéder aux paramètres de l'extension. Cherchez le paramètre "Environnement: PHP Version", et donnez-lui la valeur "5.6.40".


   
