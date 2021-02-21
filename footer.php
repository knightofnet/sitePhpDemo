

    </div> <!-- fin de la div class container (ouverte dans le fichier header.php) -->

    <footer id="footer">
        <div class="container">
            
            <div class="row ligne-tech">
                <div class="col-sm-6">
                    <ul>
                        <li title="Une instruction session_start() a été déclarée"><span class="font-weight-bold">Session initialisée : </span><?=session_status() == PHP_SESSION_ACTIVE ? "oui" : "non"?></li>
                        <li><span class="font-weight-bold">Utilisateur connecté : </span><?=isset($_SESSION['isConnected']) && $_SESSION['isConnected'] == true ? "oui" : "non"?> </li>
                        <li><span class="font-weight-bold">Nom de l'utilisateur connecté : </span><?=isset($_SESSION['user']) ? $_SESSION['user'] : "ABSENT"?> </li>
                    </ul>
                </div>
                <div class="col-sm-2">
                    <div class="long">
                        <div title="Requêtes SQL durant le dernier chargement" class="font-weight-bold">Requêtes SQL</div>
                    <?php
                    $requetesSqlMemory = "";
                    if (isset($_SESSION['requeteSqlMemoire']) && count($_SESSION['requeteSqlMemoire'])>0) {
                        $requetesSqlMemory = implode('<br>', $_SESSION['requeteSqlMemoire']);
                        $_SESSION['requeteSqlMemoire'] = [];
                    }
                    echo $requetesSqlMemory;
                    ?>
                    </div>
                </div>

                <div class="col">
                    Par Aryx (Arnaud Leblanc) - <a href="https://code.dacendi.net/Aryx/SitePhpDemo" target="_blank">https://code.dacendi.net/Aryx/SitePhpDemo</a>
                </div>

            </div>
        </div>
    </footer>


    <script src="<?=URL_SITE?>/js/bootstrap.js"></script>
</body>

</html>