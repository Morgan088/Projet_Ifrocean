<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$title = "Accueil";
include "header.php";
?>

    <h1>Bienvenue <?php echo htmlentities($user['prenom']); ?> <?php echo htmlentities($user['nom']); ?></h1>

<?php if ($user['role'] === 'admin'): ?>
    <p>Menu Admin : Gestion du site</p>
<?php elseif ($user['role'] === 'preleveur'): ?>
    <p>Menu Préleveur : Saisie des données</p>
<?php endif; ?>


    <div class="conteneur-boutons">
        <a href="zoneScientifique.php" class="gros-bouton">
            Zone<br>Scientifique
        </a>
        <a href="synthese.php" class="gros-bouton">
            Synthèse<br>étude
        </a>
    </div>

    <div class="zone-description">
        <h3>À propos du projet</h3>
        <p>Le but de cette étude est de recenser et quantifier les populations animales présentes sur l’estran des grandes plages de sable du littoral nord-ouest de la côte atlantique française. Cette étude se concentrera sur les annélides..</p>
    </div>
    <br>
    <a href="logout.php">Se déconnecter</a>

<?php
include "footer.php";
?>