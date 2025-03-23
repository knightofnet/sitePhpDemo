<?php

function renderNavItem($navElt)
{
    if (!isset($navElt['auto']) || !$navElt['auto']) {
        return;
    }

    if (isset($navElt['sousElts'])) {
        renderDropdown($navElt);
    } else {
?>
        <li class="nav-item<?= $navElt['active'] ? " active" : "" ?>">
            <a class="nav-link" title="<?= $navElt['title'] ?>" href="<?= $navElt['lien'] ?>"><?= $navElt['nom'] ?></a>
        </li>
    <?php
    }
}

function renderDropdown($navElt)
{
    if (!isset($navElt['auto']) || !$navElt['auto']) {
        return;
    }
    ?>
    <li class="nav-item dropdown<?= $navElt['active'] ? " active" : "" ?>">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?= $navElt['nom'] ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <?php
            foreach ($navElt['sousElts'] as $sousElt) {
            ?>
                <a class="dropdown-item<?= $sousElt['active'] ? " active" : "" ?>" href="<?= $sousElt['lien'] ?>" title="<?= $sousElt['title'] ?>"><?= $sousElt['nom'] ?></a>
            <?php
            }
            ?>
        </div>
    </li>
<?php
}



?>

<!doctype html>
<html>

<head>
    <meta charset="utf-8">

    <link href="<?= URL_SITE ?>/css/bootstrap.css" rel="stylesheet" />
    <link href="<?= URL_SITE ?>/css/perso.css" rel="stylesheet" />

    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <title>Page public</title>

</head>


<body>


    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Site démo</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <?php
                foreach ($navbarHtml as $navElt) {
                    renderNavItem($navElt);
                }
                ?>
            </ul>

            <ul class="navbar-nav">
                <?php
                $l = $navbarHtml['connect'];
                ?>
                <li class="nav-item<?= $l['active'] ? " active" : "" ?>">
                    <a class="nav-link" title="<?= $l['title'] ?>" href="<?= $l['lien'] ?>"><?= $l['nom'] ?></a>
                </li>
            </ul>

        </div>
    </nav>


    <div class="container main">
        <!-- Balise fermante dans le footer -->