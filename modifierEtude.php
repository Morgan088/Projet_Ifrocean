<?php
session_start();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    http_response_code(400);
    die("ID d'étude invalide.");
}

include_once "config.php";
$db = new PDO('mysql:host='.Config::HOST.';dbname='.Config::DBNAME, Config::USER, Config::PASSWORD);

$req = $db->prepare("SELECT * FROM etude_scientifique WHERE id_etude = :id");
$req->execute([':id' => $id]);
$etude = $req->fetch(PDO::FETCH_ASSOC);

if (!$etude) {
    http_response_code(404);
    die("Étude introuvable.");
}

$title = "Modifier l'étude";
include "header.php";
?>

    <div class="container my-4">
        <h1>Modifier l'étude : <?php echo htmlentities($etude['code_titre']); ?></h1>

        <form action="actions/modifierEtudeAction.php" method="POST">
            <input type="hidden" name="id_etude" value="<?php echo $etude['id_etude']; ?>">

            <div class="mb-3">
                <label class="form-label">Code Titre :</label>
                <input type="text" name="code_titre" class="form-control" value="<?php echo htmlentities($etude['code_titre']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Année de lancement :</label>
                <input type="number" name="annee_lancement" class="form-control" value="<?php echo htmlentities($etude['annee_lancement']); ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Descriptif :</label>
                <textarea name="descriptif" class="form-control" rows="4"><?php echo htmlentities($etude['descriptif']); ?></textarea>
            </div>

            <button type="submit" class="btn btn-success">Enregistrer les modifications</button>
            <a href="synthese.php?id_etude=<?php echo $id; ?>" class="btn btn-secondary">Annuler</a>
        </form>
    </div>

<?php
include "footer.php";
?>