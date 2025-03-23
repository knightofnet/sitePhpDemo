<!DOCTYPE html>
<html>

<head>
    <title>Erreur de connexion à la base de données</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <h1>Erreur de connexion à la base de données</h1>

                <div>
                    <code>
                        <?php
                        if (isset($e)) {
                            echo $e->getMessage();
                        }
                        ?>
                    </code>
                    <code>
                        <?php
                        if (isset($db)) {
                            echo $e->getTraceAsString();;
                        }
                        ?>
                    </code>
                </div>

                <p>Il y a eu une erreur lors de la connexion à la base de données.</p>
                <p>Il est possible que le serveur de base de données ne soit pas démarré, ou que les informations de connexion soient incorrectes.</p>

            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <h3>SGBD non démarré ?</h3>
                <p>
                    Si le serveur de base de données n'est pas démarré, vous pouvez essayer de le démarrer.
                </p>
                <p>
                    Sur Windows avec WAMP :<br>
                    <code>Menu Démarrer > WampServer</code><br>
                    Assurez-vous que les services Apache et MySQL sont démarrés.
                    L'icone de WampServer doit être verte.

                </p>
            </div>

            <div class="col-6">
                <h3>Information de connexion au SGBD invalides ?</h3>
                <p>
                    Si le serveur de base de données n'est pas démarré, vous pouvez essayer de le démarrer.
                </p>
                <p>
                    Pour que ce site de démonstration fonctionne, il faut que les informations de connexion à la base de données soient correctes.
                    <br>
                    Ces informations sont dans le fichier <code>initCore.php</code>.<br>
                </p>
                <p>
                    Actuellement les informations de connexion sont :
                </p>
                <ul>
                    <li>Hôte/adresse du SGBD : <code><?= BDD_ADRESSE_SERVEUR ?></code></li>
                    <li>Utilisateur : <code><?= BDD_UTILISATEUR ?></code></li>
                    <li>
                        Mot de passe : <code><?php
                                                if (BDD_MDP == "") {
                                                    echo "Aucun mot de passe";
                                                } else {
                                                    echo "****";
                                                }
                                                ?></code>
                    </li>


                </ul>
            </div>
        </div>
    </div>
</body>

</html>