<?php
require_once '../config.php';
// session_start(); // décommente si tu gères la connexion par session
$points = ['A', 'B', 'C', 'D'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Zone étudiante - Ifrocean</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../header/header.css">
    <link rel="stylesheet" href="zone_etudiante.css">
</head>
<body>

<?php include '../header/header.php'; ?>

<main class="container my-4">
    <h1 class="ze-title">Zone étudiante</h1>

    <form method="post" action="zone_etudiante_suite.php">

        <!-- Infos de l'étude -->
        <section class="ze-card">
            <div class="row g-4">
                <div class="col-md-6">
                    <label for="nom_etude" class="form-label">Nom de l’étude</label>
                    <input type="text" id="nom_etude" name="nom_etude" class="form-control" required>

                    <label for="nom_plage" class="form-label mt-3">Nom de la plage</label>
                    <input type="text" id="nom_plage" name="nom_plage" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="date_prelevement" class="form-label">Date de prélèvement</label>
                    <input type="date" id="date_prelevement" name="date_prelevement" class="form-control" required>
                </div>
            </div>
        </section>

        <!-- Zone géographique -->
        <section class="ze-card">
            <h2 class="ze-subtitle">Zone géographique</h2>
            <div class="row g-4">
                <div class="col-lg-9">
                    <?php foreach ($points as $p): ?>
                        <div class="row g-2 align-items-center mb-2">
                            <div class="col-12 col-sm-2 fw-bold">Point <?= $p ?></div>
                            <div class="col-6 col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-text">X</span>
                                    <input type="number" step="any" min="-90" max="90"
                                           name="point_<?= strtolower($p) ?>_x" class="form-control" placeholder="latitude" required>
                                </div>
                            </div>
                            <div class="col-6 col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-text">Y</span>
                                    <input type="number" step="any" min="-180" max="180"
                                           name="point_<?= strtolower($p) ?>_y" class="form-control" placeholder="longitude" required>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="col-lg-3">
                    <p class="small mb-2">X = latitude<br>Y = longitude</p>
                    <div class="ze-planet">🌍<span>Image planète</span></div>
                </div>
            </div>
        </section>

        <!-- Image exemple -->
        <section class="ze-card text-center">
            <div class="ze-example">
                <span>Image exemple<br>zone étude</span>
                <!-- Remplace par : <img src="../img/exemple_zone.png" alt="Exemple de zone d'étude" class="img-fluid"> -->
            </div>
        </section>

        <div class="text-end">
            <button type="submit" class="btn ze-btn">Suivant →</button>
        </div>
    </form>
</main>

<?php include '../footer.php'; ?>

</body>
</html>