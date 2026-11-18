<?php
session_start();
require_once 'config.php'; // doit définir $pdo
include "header.php";

// ---------- Traitement des formulaires ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'ajouter') {
        $nom    = trim($_POST['nom_etude'] ?? '');
        $plages = array_filter(array_map('trim', $_POST['plages'] ?? []), fn($p) => $p !== '');

        if ($nom === '' || count($plages) === 0) {
            $_SESSION['flash'] = ['danger', 'Indique un nom d’étude et au moins une plage.'];
        } else {
            try {
                $pdo->beginTransaction();
                $pdo->prepare('INSERT INTO etude (nom) VALUES (?)')->execute([$nom]);
                $etudeId = $pdo->lastInsertId();
                $stmt = $pdo->prepare('INSERT INTO plage (etude_id, nom) VALUES (?, ?)');
                foreach ($plages as $p) {
                    $stmt->execute([$etudeId, $p]);
                }
                $pdo->commit();
                $_SESSION['flash'] = ['success', 'Étude « ' . $nom . ' » ajoutée.'];
            } catch (Exception $e) {
                $pdo->rollBack();
                $_SESSION['flash'] = ['danger', 'Erreur lors de l’enregistrement.'];
            }
        }
    }

    if ($action === 'supprimer') {
        $id = (int)($_POST['etude_id'] ?? 0);
        if ($id > 0) {
            $pdo->prepare('DELETE FROM etude WHERE id = ?')->execute([$id]);
            $_SESSION['flash'] = ['success', 'Étude supprimée.'];
        } else {
            $_SESSION['flash'] = ['danger', 'Choisis une étude à supprimer.'];
        }
    }

    header('Location: formulaireAjouterNouvelleEtude.php');
    exit;
}

// ---------- Lecture des données ----------
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$etudes = $pdo->query('SELECT id, nom FROM etude ORDER BY nom')->fetchAll(PDO::FETCH_ASSOC);
$lignes = $pdo->query(
    'SELECT e.nom AS etude, p.nom AS plage
     FROM etude e LEFT JOIN plage p ON p.etude_id = e.id
     ORDER BY e.nom, p.nom'
)->fetchAll(PDO::FETCH_ASSOC);
?>




<main class="container my-4">

    <?php if ($flash): ?>
        <div class="alert alert-<?= $flash[0] ?>"><?= htmlspecialchars($flash[1]) ?></div>
    <?php endif; ?>

    <!-- Nouvelle étude -->
    <form method="post" class="ne-card">
        <input type="hidden" name="action" value="ajouter">

        <div class="row align-items-center mb-4">
            <label for="nom_etude" class="col-sm-3 col-form-label ne-label">Nouvelle étude :</label>
            <div class="col-sm-9">
                <input type="text" id="nom_etude" name="nom_etude" class="form-control" required>
            </div>
        </div>

        <div class="row">
            <label class="col-sm-3 col-form-label ne-label">Plage de l’étude :</label>
            <div class="col-sm-9">
                <div id="liste-plages">
                    <div class="input-group mb-2 ligne-plage">
                        <input type="text" name="plages[]" class="form-control" placeholder="Nom d’une plage">
                        <button type="button" class="btn btn-outline-danger btn-suppr-plage" title="Retirer">✕</button>
                    </div>
                </div>
                <button type="button" id="btn-ajout-plage" class="btn btn-sm btn-outline-primary">+ Ajouter une plage</button>
            </div>
        </div>

        <div class="text-end mt-3">
            <button type="submit" class="btn ne-btn">Envoyer</button>
        </div>
    </form>

    <!-- Liste des études -->
    <section class="ne-card">
        <div class="ne-liste">
            <?php if (!$lignes): ?>
                <p class="text-muted mb-0">Aucune étude pour le moment.</p>
            <?php endif; ?>
            <?php foreach ($lignes as $l): ?>
                <div class="row g-2 align-items-center mb-2">
                    <div class="col-sm-6">
                        <div class="input-group">
                            <span class="input-group-text">Nom de l’étude</span>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($l['etude']) ?>" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <span class="input-group-text">Plage</span>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($l['plage'] ?? '—') ?>" readonly>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Suppression -->
    <form method="post" class="ne-card" onsubmit="return confirm('Supprimer cette étude et ses plages ?');">
        <input type="hidden" name="action" value="supprimer">
        <div class="row g-2 align-items-center">
            <label for="etude_id" class="col-sm-4 ne-label">Étude qui veut être supprimée :</label>
            <div class="col-sm-5">
                <select id="etude_id" name="etude_id" class="form-select">
                    <option value="">— Choisir —</option>
                    <?php foreach ($etudes as $e): ?>
                        <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-sm-3 text-end">
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </div>
        </div>
    </form>

    <div class="text-start">
        <a href="formulaireCreePlage.php" class="btn ne-btn">Suivant →</a>
    </div>
</main>

<?php include 'footer.php'; ?>

<script>
    const liste = document.getElementById('liste-plages');

    document.getElementById('btn-ajout-plage').addEventListener('click', () => {
        const ligne = liste.firstElementChild.cloneNode(true);
        ligne.querySelector('input').value = '';
        liste.appendChild(ligne);
    });

    liste.addEventListener('click', (e) => {
        if (!e.target.classList.contains('btn-suppr-plage')) return;
        if (liste.children.length > 1) {
            e.target.closest('.ligne-plage').remove();
        } else {
            e.target.closest('.ligne-plage').querySelector('input').value = '';
        }
    });
</script>

</body>
</html>