<?php

session_start();

require 'connexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $matricule = $_POST["matricule"] ?? null;
    $dateNaiss = $_POST["dateNaiss"] ?? null;
    $nom = $_POST["nom"] ?? null;
    $prenom = $_POST["prenom"] ?? null;
    $sexe = $_POST["sexe"] ?? null;
    $grade = filter_var($_POST["grade"] ?? null, FILTER_VALIDATE_INT);
    $tel = $_POST["tel"] ?? null;
    $bip = $_POST["bip"] ?? null;

    if ($matricule !== null && $dateNaiss !== null && $nom !== null && $prenom !== null && $sexe !== null && $grade !== null && $tel !== null) {

        $checkGrade = $pdo->prepare("SELECT idGrade FROM grade WHERE idGrade = :idGrade LIMIT 1");
        $checkGrade->execute([':idGrade' => $grade]);

        if (!$checkGrade->fetch()) {
            $_SESSION['flash'] = [
                'message' => 'Le grade sélectionné n’existe pas dans la base.',
                'type' => 'danger'
            ];

            header('Location: ajout_pompier.php');
            exit;
        }

        try {
            $sql = "INSERT INTO `pompier` (`matricule`, `nom`, `prenom`, `dateNaiss`, `tel`, `bip`, `sexe`, `idGrade`)
                    VALUES (:matricule, :nom, :prenom, :dateNaiss, :tel, :bip, :sexe, :idGrade)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':matricule' => $matricule,
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':dateNaiss' => $dateNaiss,
                ':tel' => $tel,
                ':bip' => $bip,
                ':sexe' => $sexe,
                ':idGrade' => $grade
            ]);

            $_SESSION['flash'] = [
                'message' => 'Le pompier a bien été ajouté.',
                'type' => 'success'
            ];

        } catch (PDOException $e) {
            $_SESSION['flash'] = [
                'message' => 'Erreur SQL : ' . $e->getMessage(),
                'type' => 'danger'
            ];
        }

    } else {
        $_SESSION['flash'] = [
            'message' => 'Veuillez remplir tous les champs obligatoires.',
            'type' => 'warning'
        ];
    }

    header('Location: ajout_pompier.php');
    exit;
}
