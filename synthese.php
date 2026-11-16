<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }

include "config.php";
$db = new PDO('mysql:host='.Config::HOST.';dbname='.Config::DBNAME, Config::USER, Config::PASSWORD);

$id = filter_input(INPUT_GET, 'id_etude', FILTER_VALIDATE_INT);
$etudes = $db->query("SELECT id_etude, code_titre FROM etude_scientifique")->fetchAll(PDO::FETCH_ASSOC);

$data = null;
$especes = [];

if ($id) {
    $req = $db->prepare("
        SELECT es.*, rg.nom_usuel, sp.date_sortie, sp.zone_polygon, sp.id_session
        FROM etude_scientifique es
        JOIN etude_plage ep ON es.id_etude = ep.id_etude
        JOIN referentiel_geographique rg ON ep.id_plage = rg.id_plage
        LEFT JOIN sessions_prelevement sp ON es.id_etude = sp.id_etude
        WHERE es.id_etude = :id
    ");
    $req->execute([':id' => $id]);
    $data = $req->fetch(PDO::FETCH_ASSOC);

    if ($data && $data['id_session']) {
        $reqEsp = $db->prepare("
            SELECT em.nom_vernaculaire, ci.nombre_individus
            FROM comptage_des_individus ci
            JOIN especes_marines em ON ci.id_espece = em.id_espece
            WHERE ci.id_session = :id_session
        ");
        $reqEsp->execute([':id_session' => $data['id_session']]);
        $especes = $reqEsp->fetchAll(PDO::FETCH_ASSOC);
    }
}

$title = "Synthèse";
include "header.php";
?>

    <div class="container mt-4">
        <h1>Synthèse étude</h1>

        <form method="GET" class="mb-3">
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

        <?php if ($id && $data): ?>
            <div class="card p-3">
                <p><strong>Plage :</strong> <?= htmlentities($data['nom_usuel']) ?></p>
                <p><strong>Date :</strong> <?= htmlentities($data['date_sortie']) ?></p>
                <p><strong>Coordonnées zone :</strong> <?= htmlentities($data['zone_polygon']) ?></p>

                <h5>Espèces et effectifs :</h5>
                <ul>
                    <?php foreach ($especes as $esp): ?>
                        <li><?= htmlentities($esp['nom_vernaculaire']) ?> : <?= $esp['nombre_individus'] ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif ($id): ?>
            <div class="alert alert-danger">Aucune donnée trouvée pour cette étude.</div>
        <?php endif; ?>
    </div>

<?php include "footer.php"; ?>