<?php

session_start();

require 'connexion.php';

$message = "";
$messageType = "";

try {
    $gradeStmt = $pdo->query("SELECT idGrade, libelle FROM grade ORDER BY idGrade");
    $grades = $gradeStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $grades = [];
    $message = "Erreur de chargement des grades : " . $e->getMessage();
    $messageType = "danger";
}

if (isset($_SESSION['flash'])) {
    $message = $_SESSION['flash']['message'];
    $messageType = $_SESSION['flash']['type'];
    unset($_SESSION['flash']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $matricule = $_POST["matricule"] ?? null;
    $dateNaiss = $_POST["dateNaiss"] ?? null;
    $nom = $_POST["nom"] ?? null;
    $prenom = $_POST["prenom"] ?? null;
    $sexe = $_POST["sexe"] ?? null;
    $grade = $_POST["grade"] ?? null;
    $tel = $_POST["tel"] ?? null;
    $bip = $_POST["bip"] ?? null;

    if ($matricule !== null && $dateNaiss !== null && $nom !== null && $prenom !== null && $sexe !== null && $grade !== null && $tel !== null) {

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

            $message = "Le pompier a bien été ajouté.";
            $messageType = "success";

        } catch (PDOException $e) {
            $message = "Erreur SQL : " . $e->getMessage();
            $messageType = "danger";
        }

    } else {
        $message = "Veuillez remplir tous les champs obligatoires.";
        $messageType = "warning";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ajout Pompier</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body>

    <div class="container mt-5">

        <h1>Ajout Pompier</h1>

        <?php if (!empty($message)) : ?>
            <div class="alert alert-<?= htmlspecialchars($messageType) ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <hr>

        <!-- Formulaire -->
        <form method="POST">

            <div class="row">

                <!-- Matricule -->
                <div class="col-md-6 mb-3">

                    <label for="matricule" class="form-label">
                        Matricule
                    </label>

                    <input
                        type="number"
                        class="form-control"
                        id="matricule"
                        name="matricule"
                    >

                </div>


                <!-- Date de naissance -->
                <div class="col-md-6 mb-3">

                    <label for="dateNaiss" class="form-label">
                        Date de naissance
                    </label>

                    <input
                        type="date"
                        class="form-control"
                        id="dateNaiss"
                        name="dateNaiss"
                    >

                </div>


                <!-- Nom -->
                <div class="col-md-6 mb-3">

                    <label for="nom" class="form-label">
                        Nom
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="nom"
                        name="nom"
                    >

                </div>


                <!-- Prénom -->
                <div class="col-md-6 mb-3">

                    <label for="prenom" class="form-label">
                        Prénom
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="prenom"
                        name="prenom"
                    >

                </div>


                <!-- Sexe -->
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Sexe :
                    </label>

                    <div>

                        <div class="form-check form-check-inline">

                            <input
                                type="radio"
                                class="form-check-input"
                                id="feminin"
                                name="sexe"
                                value="féminin"
                            >

                            <label
                                class="form-check-label"
                                for="feminin"
                            >
                                féminin
                            </label>

                        </div>


                        <div class="form-check form-check-inline">

                            <input
                                type="radio"
                                class="form-check-input"
                                id="masculin"
                                name="sexe"
                                value="masculin"
                            >

                            <label
                                class="form-check-label"
                                for="masculin"
                            >
                                masculin
                            </label>

                        </div>

                    </div>

                </div>


                <!-- Grade -->
                <div class="col-md-6 mb-3">

                    <label for="grade" class="form-label">
                        Grade
                    </label>

                    <select
                        class="form-select"
                        id="grade"
                        name="grade"
                    >

                        <option value="">
                            Choisir un grade
                        </option>

                        <?php foreach ($grades as $gradeItem) : ?>
                            <option value="<?= (int)$gradeItem['idGrade'] ?>">
                                <?= htmlspecialchars($gradeItem['libelle']) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>

                </div>


                <!-- Téléphone -->
                <div class="col-md-6 mb-3">

                    <label for="tel" class="form-label">
                        Téléphone
                    </label>

                    <input
                        type="tel"
                        class="form-control"
                        id="tel"
                        name="tel"
                    >

                </div>

                <!-- Bip -->
                <div class="col-md-6 mb-3">

                    <label for="bip" class="form-label">
                        Bip
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="bip"
                        name="bip"
                    >

                </div>


                <!-- Caserne -->
                <div class="col-md-6 mb-3">

                    <label for="caserne" class="form-label">
                        Caserne
                    </label>

                    <select
                        class="form-select"
                        id="caserne"
                        name="caserne"
                    >

                        <option value="">
                            Choisir une caserne
                        </option>

                        <option value="1">Ouessant</option>
                        <option value="2">Carcassonne</option>
                        <option value="3">Lille</option>

                    </select>

                </div>


                <!-- Type -->
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Type :
                    </label>

                    <div>

                        <div class="form-check form-check-inline">

                            <input
                                type="radio"
                                class="form-check-input"
                                id="professionnel"
                                name="type"
                                value="professionnel"
                            >

                            <label
                                class="form-check-label"
                                for="professionnel"
                            >
                                Professionnel
                            </label>

                        </div>


                        <div class="form-check form-check-inline">

                            <input
                                type="radio"
                                class="form-check-input"
                                id="volontaire"
                                name="type"
                                value="volontaire"
                            >

                            <label
                                class="form-check-label"
                                for="volontaire"
                            >
                                Volontaire
                            </label>

                        </div>

                    </div>

                </div>


                <!-- Bouton -->
                <div class="col-12 mt-3">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Valider
                    </button>

                </div>

            </div>

        </form>

    </div>

</body>

</html>