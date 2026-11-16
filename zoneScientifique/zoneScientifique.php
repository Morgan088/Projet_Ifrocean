<?php
session_start();

// Vérification de la connexion
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$message_succes = "";

// Traitement lors de la validation du formulaire (clic sur ENVOYER + confirmation)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_envoyer'])) {

    // ------------------------------------------------------------------
    // ICI : Ajoute ta connexion et ta requête SQL vers ta base de données
    // Exemple :
    // require_once "db.php";
    // $stmt = $pdo->prepare("INSERT INTO ...");
    // $stmt->execute([...]);
    // ------------------------------------------------------------------

    $message_succes = "Les données ont bien été enregistrées dans la base de données !";
}

$title = "Zone scientifique";
include "header.php";
?>

<div class="page-scientifique">

    <h1 class="titre-scientifique">Zone scientifique</h1>

    <?php if (!empty($message_succes)): ?>
        <div class="alerte-succes">
            <?php echo htmlentities($message_succes); ?>
        </div>
    <?php endif; ?>

    <!-- Les 3 grands boutons principaux -->
    <div class="liste-boutons-sci">
        <a href="creer_plage.php" class="bouton-sci">
            Crée plage
        </a>
        <a href="creer_etude.php" class="bouton-sci">
            Crée / Ajouter Nouvelle etude
        </a>
        <a href="ajouter_espece.php" class="bouton-sci">
            Ajouter espece marine
        </a>
    </div>

    <!-- Formulaire pour le bouton ENVOYER en bas à droite -->
    <form method="POST" action="" class="zone-envoyer" onsubmit="return confirmerEnvoi();">
        <button type="submit" name="action_envoyer" class="bouton-envoyer">
            ENVOYER
        </button>
    </form>

</div>

<!-- Script de demande de confirmation -->
<script>
    function confirmerEnvoi() {
        return confirm("Êtes-vous sûr de n'avoir rien oublié ?");
    }
</script>

<?php
include "footer.php";
?>
