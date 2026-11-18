<?php
$title = "Connexion";
include "header.php";

?>

<!-- Ton formulaire de connexion ici -->
<form method="POST" action="traitement_login.php">
    <!-- tes champs input (email, mot de passe...) -->
</form>


<?php
session_start();
$erreur = "";

if (isset($_POST['email']) && isset($_POST['mot_de_passe'])) { //isset on le met car on utilise sa pro
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';

    if (empty($email) || empty($mot_de_passe)) {
        $erreur = "Veuillez remplir tous les champs.";
    } else {
        include "config.php";
        try {
            $db = new PDO('mysql:host='.Config::HOST.';dbname='.Config::DBNAME, Config::USER, Config::PASSWORD);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $req = $db->prepare("SELECT * FROM utilisateurs WHERE email = :email");
            $req->execute([':email' => $email]);
            $utilisateur = $req->fetch(PDO::FETCH_ASSOC);

            if ($utilisateur) {
                $connexion_reussie = false;

                if (password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
                    $connexion_reussie = true;
                }
                elseif ($mot_de_passe === $utilisateur['mot_de_passe']) {
                    $connexion_reussie = true;

                    $nouveau_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
                    $update = $db->prepare("UPDATE utilisateurs SET mot_de_passe = :hash WHERE id = :id");
                    $update->execute([
                            ':hash' => $nouveau_hash,
                            ':id' => $utilisateur['id']
                    ]);
                }

                if ($connexion_reussie) {
                    $_SESSION['user'] = $utilisateur;
                    header("Location: index.php");
                    exit();
                } else {
                    $erreur = "Identifiants ou mot de passe erronés.";
                }
            } else {
                $erreur = "Identifiants ou mot de passe erronés.";
            }
        } catch (PDOException $e) {
            $erreur = "Erreur de connexion à la base de données.";
        }
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Connexion</h1>
<?php if ($erreur != ""): ?>
    <p style="color: red;"><?php echo $erreur; ?></p>
<?php endif; ?>
<form action="login.php" method="POST">
    Email : <input type="email" name="email" required>
    <br>
    Mot de passe : <input type="password" name="mot_de_passe" required>
    <br>
    <input type="submit" value="OK">
</form>
</body>
</html>
<?php
include "footer.php";
?>