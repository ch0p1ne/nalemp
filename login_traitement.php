<?php
session_start();
include('database_connection.php'); // Connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = trim($_POST['login_number']);
    $password = trim($_POST['password_sp']);

    if (empty($phone) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Tous les champs sont obligatoires."]);
        exit;
    }

    try {
        // Vérifier si l'utilisateur existe
        $stmt = $connect->prepare("SELECT id, name, password, role FROM users WHERE phone = :phone");
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Stocker les informations de l'utilisateur en session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role']; // Ajout du rôle

            // Déterminer la redirection
            if ($user['role'] === 'admin') {
                header("Location: dashboard.php"); // Redirige vers le tableau de bord admin
            } else {
                header("Location: pointage_jour.php"); // Redirige vers la page de pointage
            }
            exit;
        } else {
            echo json_encode(["status" => "error", "message" => "Numéro ou mot de passe incorrect."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Erreur SQL : " . $e->getMessage()]);
    }
}
?>
