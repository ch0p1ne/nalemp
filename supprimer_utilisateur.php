<?php
session_start();
include('database_connection.php'); // Connexion à la base de données

// Vérifier si l'ID est présent dans l'URL et est valide
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: list_utilisateurs.php");
    exit();
}

$id = (int)$_GET['id']; // Assurer que l'ID est un entier

// Vérifier si l'utilisateur existe
$query = $connect->prepare("SELECT * FROM users WHERE id = :id");
$query->bindParam(':id', $id, PDO::PARAM_INT);
$query->execute();
$user = $query->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: list_utilisateurs.php");
    exit();
}

// Archiver l'utilisateur au lieu de le supprimer
$updateQuery = $connect->prepare("UPDATE users SET archived = 1 WHERE id = :id");
$updateQuery->bindParam(':id', $id, PDO::PARAM_INT);
$updateQuery->execute();

// Rediriger immédiatement vers la liste des utilisateurs
header("Location: list_utilisateurs.php");
exit();
?>