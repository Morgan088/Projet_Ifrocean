<?php
session_start();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { header("Location: synthese.php"); exit(); }

include "config.php";
$db = new PDO('mysql:host='.Config::HOST.';dbname='.Config::DBNAME, Config::USER, Config::PASSWORD);

// Récupérer l'étude
$req = $db->prepare("SELECT * FROM etude_scientifique WHERE id_etude = :id");
$req->execute([':id' => $id]);
$etude = $req->fetch(PDO::FETCH_ASSOC);

// Récupérer les sessions associées
$reqSessions = $db->prepare("
    SELECT sp.*, rg.nom_usuel 
    FROM sessions_prelevement sp
    JOIN referentiel_geographique rg ON sp.id_plage = rg.id_plage
    WHERE sp.id_etude = :id
");
$reqSessions->execute([':id' => $id]);
$sessions = $reqSessions->fetchAll(PDO::FETCH_ASSOC);

$title = "Modifier l'étude";
include "header.php";
?>

    <div class="container mt-4">
        <h1>Modifier l'étude : <?= htmlentities($etude['code_titre']) ?></h1>

        <!-- Le formulaire pointe vers le fichier de traitement à la racine -->
        <form action="modifierEtudeAction.php" method="POST" class="mb-5">
            <input type="hidden" name="id_etude" value="<?= $etude['id_etude'] ?>">

            <div class="card p-4 mb-3">
                <h3>Informations générales</h3>
                <div class="mb-3">
                    <label class="form-label">Code Titre :</label>
                    <input type="text" name="code_titre" class="form-control" value="<?= htmlentities($etude['code_titre']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Année de lancement :</label>
                    <input type="number" name="annee_lancement" class="form-control" value="<?= htmlentities($etude['annee_lancement']) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Descriptif :</label>
                    <textarea name="descriptif" class="form-control" rows="3"><?= htmlentities($etude['descriptif']) ?></textarea>
                </div>
            </div>

            <h3 class="mb-3">Sessions et Comptages</h3>
            <?php foreach ($sessions as $session): ?>
                <div class="card p-3 mb-3 bg-light">
                    <h5>Plage : <?= htmlentities($session['nom_usuel']) ?> (Session #<?= $session['id_session'] ?>)</h5>
                    <input type="hidden" name="sessions[<?= $session['id_session'] ?>][id_session]" value="<?= $session['id_session'] ?>">

                    <div class="mb-2">
                        <label class="form-label">Date de sortie :</label>
                        <input type="date" name="sessions[<?= $session['id_session'] ?>][date_sortie]" class="form-control" value="<?= $session['date_sortie'] ?>">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Nombre de préleveurs :</label>
                        <input type="number" name="sessions[<?= $session['id_session'] ?>][nb_preleveurs]" class="form-control" value="<?= $session['nb_preleveurs'] ?>">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Surface réelle (m²) :</label>
                        <input type="text" name="sessions[<?= $session['id_session'] ?>][surface_reelle]" class="form-control" value="<?= $session['surface_reelle'] ?>">
                    </div>

                    <h6 class="mt-3">Effectifs des espèces :</h6>
                    <?php
                    $reqEsp = $db->prepare("
                    SELECT ci.id_comptage, ci.nombre_individus, em.nom_vernaculaire 
                    FROM comptage_des_individus ci
                    JOIN especes_marines em ON ci.id_espece = em.id_espece
                    WHERE ci.id_session = :id_session
                ");
                    $reqEsp->execute([':id_session' => $session['id_session']]);
                    $comptages = $reqEsp->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <?php foreach ($comptages as $c): ?>
                        <div class="mb-2 row align-items-center">
                            <div class="col-md-6">
                                <?= htmlentities($c['nom_vernaculaire']) ?>
                            </div>
                            <div class="col-md-6">
                                <input type="hidden" name="comptages[<?= $c['id_comptage'] ?>][id_comptage]" value="<?= $c['id_comptage'] ?>">
                                <input type="number" name="comptages[<?= $c['id_comptage'] ?>][nombre]" class="form-control" value="<?= $c['nombre_individus'] ?>">
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>

            <button type="submit" class="btn btn-success">Enregistrer toutes les modifications</button>
            <a href="synthese.php?id_etude=<?= $id ?>" class="btn btn-secondary">Annuler</a>
        </form>
    </div>

<?php include "footer.php"; ?>