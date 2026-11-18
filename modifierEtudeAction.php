<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include "config.php"; // Chemin direct car tout est à la racine

    try {
        $db = new PDO('mysql:host='.Config::HOST.';dbname='.Config::DBNAME, Config::USER, Config::PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id_etude = filter_input(INPUT_POST, 'id_etude', FILTER_VALIDATE_INT);

        if ($id_etude) {
            // 1. Mettre à jour l'étude scientifique
            $req = $db->prepare("UPDATE etude_scientifique SET code_titre = :code, annee_lancement = :annee, descriptif = :desc WHERE id_etude = :id");
            $req->execute([
                ':code' => $_POST['code_titre'],
                ':annee' => $_POST['annee_lancement'],
                ':desc' => $_POST['descriptif'],
                ':id' => $id_etude
            ]);

            // 2. Mettre à jour les sessions de prélèvement
            if (isset($_POST['sessions']) && is_array($_POST['sessions'])) {
                foreach ($_POST['sessions'] as $session) {
                    $reqSess = $db->prepare("UPDATE sessions_prelevement SET date_sortie = :date, nb_preleveurs = :nb, surface_reelle = :surface WHERE id_session = :id_session");
                    $reqSess->execute([
                        ':date' => $session['date_sortie'],
                        ':nb' => $session['nb_preleveurs'],
                        ':surface' => $session['surface_reelle'],
                        ':id_session' => $session['id_session']
                    ]);
                }
            }

            // 3. Mettre à jour les comptages d'espèces
            if (isset($_POST['comptages']) && is_array($_POST['comptages'])) {
                foreach ($_POST['comptages'] as $comptage) {
                    $reqComp = $db->prepare("UPDATE comptage_des_individus SET nombre_individus = :nombre WHERE id_comptage = :id_comptage");
                    $reqComp->execute([
                        ':nombre' => $comptage['nombre'],
                        ':id_comptage' => $comptage['id_comptage']
                    ]);
                }
            }
        }

        // Redirection vers la page de synthèse (sans ../ puisque tout est à la racine)
        header("Location: synthese.php?id_etude=" . $id_etude);
        exit();

    } catch (PDOException $e) {
        die("Erreur lors de la modification : " . $e->getMessage());
    }
}