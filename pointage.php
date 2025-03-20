<?php
// Connexion à la base de données
include('database_connection.php'); // Fichier qui contient la connexion `$conn` (PDO)

session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Veuillez vous connecter."]);
    exit;
}

$user_id = $_SESSION['user_id'];
$date = date("Y-m-d");

try {
    // Vérifier si l'utilisateur a déjà pointé son entrée aujourd'hui
    $stmt = $connect->prepare("SELECT id, heure_entree, heure_sortie FROM pointage WHERE user_id = :user_id AND date = :date");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':date', $date);
    $stmt->execute();
    $pointage = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($pointage) {
        if ($pointage['heure_sortie']) {
            echo json_encode(["status" => "error", "message" => "Vous avez déjà pointé votre sortie."]);
        } else {
            // Mise à jour de l'heure de sortie
            $update = $connect->prepare("UPDATE pointage SET heure_sortie = NOW() WHERE id = :id");
            $update->bindParam(':id', $pointage['id']);
            $update->execute();
            echo json_encode(["status" => "success", "message" => "Sortie enregistrée !"]);
        }
    } else {
        // Insérer une nouvelle entrée
        $insert = $connect->prepare("INSERT INTO pointage (user_id, date, heure_entree) VALUES (:user_id, :date, NOW())");
        $insert->bindParam(':user_id', $user_id);
        $insert->bindParam(':date', $date);
        $insert->execute();
        echo json_encode(["status" => "success", "message" => "Entrée enregistrée !"]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Erreur SQL : " . $e->getMessage()]);
}
?>
