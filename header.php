<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title ?? 'Ifrocean'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="synthese.css">
    <link rel="stylesheet" href="formulaireAjouterNouvelleEtude.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="zoneScientifique.css?v=3">
</head>
<body>

<header class="header">
    <div class="container-fluid header-contenu">
        <div class="row align-items-center py-2">

            <!-- Gauche : logo -->
            <div class="col-4 text-start">
                <a href="../index.php" class="header-logo">
                    <img src="../img/logo.png" alt="Logo Ifrocean">
                </a>
            </div>

            <!-- Centre : nom du site -->
            <div class="col-4 text-center">
                <div class="titre-mer">
                    <h1 class="header-title m-0" aria-label="Ifrocean">
                        <span style="--i:0">I</span><span style="--i:1">f</span><span style="--i:2">r</span><span style="--i:3">o</span><span style="--i:4">c</span><span style="--i:5">e</span><span style="--i:6">a</span><span style="--i:7">n</span>
                    </h1>
                    <span class="poisson poisson-a">🐟</span>
                    <span class="poisson poisson-b">🐠</span>
                    <span class="poisson-saut">🐬</span>
                </div>
            </div>

            <!-- Droite : Bouton Burger pour ouvrir le menu -->
            <div class="col-4 text-end">
                <button class="btn btn-burger fs-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuBurger" aria-controls="menuBurger">
                    ☰
                </button>
            </div>

        </div> <!-- Fin de .row -->
    </div> <!-- Fin de .container-fluid -->

    <!-- Décor : bulles, poisson, vagues -->
    <div class="bulles">
        <span></span><span></span><span></span><span></span><span></span><span></span>
    </div>
    <div class="header-poisson">🐠</div>
    <div class="vagues">
        <div class="vague vague1"></div>
        <div class="vague vague2"></div>
        <div class="vague vague3"></div>
    </div>
</header>

<!-- Menu qui s'ouvre au clic sur le burger -->
<div class="offcanvas offcanvas-end menu-mer" tabindex="-1" id="menuBurger">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Fermer"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="list-unstyled">
            <li><a href="index.php">🏠 Accueil</a></li>
            <li><a href="login.php">🐠 Connexion</a></li>
            <li><a href="logout.php">🐋 Déconnexion</a></li>
        </ul>

        <div class="poisson-nage">🐟</div>
    </div>
</div>