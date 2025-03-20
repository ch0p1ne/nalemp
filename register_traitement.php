<?php
// Connexion à la base de données
include('database_connection.php'); // Assure-toi que `$conn` est bien défini dans ce fichier

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['login_name']);
    $phone = trim($_POST['login_number']);
    $password = trim($_POST['password_sp']);

    // Vérification des champs vides
    if (empty($name) || empty($phone) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Tous les champs sont obligatoires."]);
        exit;
    }

    try {
        // Vérifier si le numéro est déjà utilisé
        $stmt = $connect->prepare("SELECT id FROM users WHERE phone = :phone");
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(["status" => "error", "message" => "Ce numéro est déjà utilisé."]);
            exit;
        }

        // Hasher le mot de passe
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insérer l'utilisateur
        $stmt = $connect->prepare("INSERT INTO users (name, phone, password) VALUES (:name, :phone, :password)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':password', $hashedPassword);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Inscription réussie !"]);
            header("Location: login.php");
            exit;
        }
    } catch (PDOException $e) {
        // Gestion des erreurs SQL (violation de contrainte unique)
        if ($e->getCode() == 23000) { // Code 23000 = contrainte unique violée
            echo json_encode(["status" => "error", "message" => "Ce numéro est déjà utilisé."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Erreur SQL : " . $e->getMessage()]);
        }
    }
}
?>
