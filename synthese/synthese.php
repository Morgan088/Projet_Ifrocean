<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }

include "config.php";
$db = new PDO('mysql:host='.Config::HOST.';dbname='.Config::DBNAME, Config::USER, Config::PASSWORD);

$id = filter_input(INPUT_GET, 'id_etude', FILTER_VALIDATE_INT);
$etudes = $db->query("SELECT id_etude, code_titre FROM etude_scientifique")->fetchAll(PDO::FETCH_ASSOC);

$etudeInfo = null;
$sessions = [];

if ($id) {
    // Récupérer les infos globales de l'étude
    $req = $db->prepare("SELECT * FROM etude_scientifique WHERE id_etude = :id");
    $req->execute([':id' => $id]);
    $etudeInfo = $req->fetch(PDO::FETCH_ASSOC);

    // Récupérer toutes les sessions de prélèvement liées à cette étude (avec ST_AsText pour le polygone)
    $reqSessions = $db->prepare("
        SELECT sp.*, rg.nom_usuel, rg.commune, rg.departement, ST_AsText(sp.zone_polygon) AS wkt_polygon
        FROM sessions_prelevement sp
        JOIN referentiel_geographique rg ON sp.id_plage = rg.id_plage
        WHERE sp.id_etude = :id
    ");
    $reqSessions->execute([':id' => $id]);
    $sessions = $reqSessions->fetchAll(PDO::FETCH_ASSOC);
}

$title = "Synthèse";
include "header.php";
?>

    <link rel="stylesheet" href="synthese.css">

    <div class="container mt-4">
        <h1>Synthèse étude</h1>

        <form method="GET" class="mb-4">
            <div class="mb-2">
                <select name="id_etude" class="form-select" required>
                    <option value="">-- Choisir une étude --</option>
                    <?php foreach ($etudes as $e): ?>
                        <option value="<?= $e['id_etude'] ?>" <?= ($id == $e['id_etude']) ? 'selected' : '' ?>>
                            <?= htmlentities($e['code_titre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Générer</button>
            <?php if ($id): ?>
                <a href="modifierEtude.php?id=<?= $id ?>" class="btn btn-warning">Modifier</a>
            <?php endif; ?>
        </form>

        <?php if ($id && $etudeInfo): ?>
            <div class="card p-4 mb-3">
                <h3><?= htmlentities($etudeInfo['code_titre']) ?> (<?= $etudeInfo['annee_lancement'] ?>)</h3>
                <p><strong>Descriptif :</strong> <?= htmlentities($etudeInfo['descriptif']) ?></p>
            </div>

            <h4 class="mb-3">Sessions de prélèvement et plages associées :</h4>

            <?php if (!empty($sessions)): ?>
                <?php foreach ($sessions as $session): ?>
                    <div class="card p-3 mb-3 bg-light">
                        <p><strong>Plage :</strong> <?= htmlentities($session['nom_usuel']) ?> (<?= htmlentities($session['commune']) ?> - <?= htmlentities($session['departement']) ?>)</p>
                        <p><strong>Date de sortie :</strong> <?= htmlentities($session['date_sortie']) ?></p>
                        <p><strong>Nombre de préleveurs :</strong> <?= htmlentities($session['nb_preleveurs']) ?></p>
                        <p><strong>Surface réelle :</strong> <?= htmlentities($session['surface_reelle']) ?> m²</p>
                        <p><strong>Coordonnées zone (Polygon) :</strong> <small class="text-break"><code><?= htmlentities($session['wkt_polygon']) ?></code></small></p>

                        <h5>Espèces et effectifs :</h5>
                        <ul>
                            <?php
                            $reqEsp = $db->prepare("
                            SELECT em.nom_vernaculaire, em.nom_scientifique, ci.nombre_individus
                            FROM comptage_des_individus ci
                            JOIN especes_marines em ON ci.id_espece = em.id_espece
                            WHERE ci.id_session = :id_session
                        ");
                            $reqEsp->execute([':id_session' => $session['id_session']]);
                            $especes = $reqEsp->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <?php if (!empty($especes)): ?>
                                <?php foreach ($especes as $esp): ?>
                                    <li><?= htmlentities($esp['nom_vernaculaire']) ?> (<em><?= htmlentities($esp['nom_scientifique']) ?></em>) : <strong><?= $esp['nombre_individus'] ?></strong></li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li><em>Aucune espèce enregistrée pour cette session.</em></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-warning">Aucune session de prélèvement trouvée pour cette étude.</div>
            <?php endif; ?>

        <?php elseif ($id): ?>
            <div class="alert alert-danger">Étude introuvable.</div>
        <?php endif; ?>
    </div>

<?php include "footer.php"; ?>