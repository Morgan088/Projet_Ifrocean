<?php
// Inclusion du fichier config.php situé à la racine du projet
require_once __DIR__ . '/../../config.php';

// Connexion à la base de données avec votre classe config
try {
    $pdo = new PDO(
        "mysql:host=" . config::HOST . ";dbname=" . config::DBNAME . ";charset=utf8mb4",
        config::USER,
        config::PASSWORD,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (Exception $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// 1. Gestion de l'ajout
if (isset($_POST['ajouter'])) {
    $nom = $_POST['nom_plage'] ?? '';
    $commune = $_POST['commune'] ?? '';
    $departement = $_POST['departement'] ?? '';
    $superficie = $_POST['superficie'] ?? '';

    if (!empty($nom) && !empty($commune) && !empty($departement) && !empty($superficie)) {
        $sql = "INSERT INTO plages (nom_plage, commune, departement, superficie) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom, $commune, $departement, $superficie]);
    }
    header("Location: formulaireCreePlage.php");
    exit();
}

// 2. Gestion de la suppression
if (isset($_POST['supprimer'])) {
    $id = $_POST['id_a_supprimer'] ?? 0;
    if ($id > 0) {
        $sql = "DELETE FROM plages WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
    }
    header("Location: formulaireCreePlage.php");
    exit();
}

// Récupération de toutes les plages pour l'affichage et la liste déroulante
$plages = $pdo->query("SELECT * FROM plages")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Création de plage</title>
    <link rel="stylesheet" href="formulaireCreePlage.css">
</head>
<body>

<div class="container">
    <!-- FORMULAIRE HAUT -->
    <form method="POST" action="formulaireCreePlage.php">
        <div class="form-group">
            <label>Nom de la plage :</label>
            <input type="text" name="nom_plage" required>
        </div>
        <div class="form-group">
            <label>Commune de rattachement :</label>
            <input type="text" name="commune" required>
        </div>
        <div class="form-group">
            <label>département :</label>
            <input type="text" name="departement" required>
        </div>
        <div class="form-group">
            <label>Superficie totale de l'estran étudié (m2) :</label>
            <input type="number" step="any" name="superficie" required>
        </div>
        <div class="btn-container">
            <button type="submit" name="ajouter" class="btn">ENVOYER</button>
        </div>
    </form>

    <hr class="separator">

    <!-- RECTANGLE DU SCHÉMA (LISTE) -->
    <div class="cadre">
        <div class="liste-plages">
            <?php foreach ($plages as $p): ?>
                <div class="ligne-item">
                    <span>Nom de plage</span>
                    <div class="pill"><?= htmlspecialchars($p['nom_plage']) ?></div>
                    <span>Autre</span>
                    <div class="pill"><?= htmlspecialchars($p['commune']) ?> (<?= htmlspecialchars($p['departement']) ?>) - <?= htmlspecialchars($p['superficie']) ?> m²</div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="dots">...</div>
    </div>

    <!-- ZONE DE SUPPRESSION -->
    <form method="POST" action="formulaireCreePlage.php">
        <div class="delete-group">
            <label>Plage qui veut être suprimer :</label>
            <select name="id_a_supprimer" class="select-box" required>
                <option value="">-- Choisir une plage --</option>
                <?php foreach ($plages as $p): ?>
                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nom_plage']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="supprimer" class="btn">Suprimer</button>
        </div>
    </form>

    <!-- BOUTON SUIVANT -->
    <div class="next-container">
        <a href="../zoneScientifique.php" class="btn">suivant</a>
    </div>
</div>

</body>
</html>