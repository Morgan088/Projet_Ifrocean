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

    <!-- ZONE DE DESCRIPTION DU PROJET -->
    <div style="background: #f1f1f1; padding: 15px; margin: 15px 0; border-radius: 5px;">
        <h3>À propos du projet</h3>
        <p>Ici, tu peux mettre la description de ton projet Ifrocean.</p>
    </div>

<?php if ($user['role'] === 'admin'): ?>
    <p>Menu Admin : Gestion du site</p>
<?php elseif ($user['role'] === 'preleveur'): ?>
    <p>Menu Préleveur : Saisie des données</p>
<?php endif; ?>

    <br>
    <a href="logout.php">Se déconnecter</a>

<?php
include "footer.php";
?>