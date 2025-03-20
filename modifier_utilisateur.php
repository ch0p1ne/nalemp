<?php
session_start();
include('database_connection.php'); // Connexion à la base de données
// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirige vers la connexion si non connecté
    exit();
}

// Vérifie si l'utilisateur est un admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: unauthorized.php"); // Redirige vers une page d'erreur
    exit();
}
// Vérifier si l'ID est présent dans l'URL et est valide
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID utilisateur invalide.");
}

$id = (int)$_GET['id']; // S'assurer que l'ID est bien un entier

// Récupérer les informations de l'utilisateur
$query = $connect->prepare("SELECT * FROM users WHERE id = :id");
$query->bindParam(':id', $id, PDO::PARAM_INT);
$query->execute();
$user = $query->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Utilisateur non trouvé.");
}

// Si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['login_name'] ?? '';
    $phone = $_POST['login_number'] ?? '';
    $password = $_POST['password_sp'] ?? '';

    // Vérification de la validité des données
    if (empty($name) || empty($phone)) {
        echo "<script>alert('Veuillez remplir tous les champs obligatoires.');</script>";
    } else {
        // Mise à jour des informations
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hachage du mot de passe
            $updateQuery = $connect->prepare("UPDATE users SET name = :name, phone = :phone, password = :password WHERE id = :id");
            $updateQuery->bindParam(':password', $hashed_password);
        } else {
            $updateQuery = $connect->prepare("UPDATE users SET name = :name, phone = :phone WHERE id = :id");
        }

        $updateQuery->bindParam(':name', $name);
        $updateQuery->bindParam(':phone', $phone);
        $updateQuery->bindParam(':id', $id, PDO::PARAM_INT);

        if ($updateQuery->execute()) {
            echo "<script>alert('Utilisateur modifié avec succès.'); window.location.href='list_utilisateurs.php';</script>";
        } else {
            echo "<script>alert('Erreur lors de la modification.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Utilisateur</title>
    <link rel="stylesheet" href="./css1/styles.css">
</head>
<body>

<form method="post" action="">
    <div class="login">
        <div class="login-screen">
            <div class="app-title">
                <h1>Modifier Utilisateur</h1>
            </div>

            <div class="register-form">
                <div class="control-group">
                    <input type="text" class="login-field" value="<?= isset($user['name']) ? htmlspecialchars($user['name']) : '' ?>" placeholder="Nom & Prénom" name="login_name" id="login_name" required>
                    <label class="login-field-icon fui-user" for="login_name"></label>
                </div>
                
                <div class="control-group">
                    <input type="text" class="login-field" value="<?= isset($user['phone']) ? htmlspecialchars($user['phone']) : '' ?>" placeholder="Numéro" name="login_number" id="login_number" required>
                    <label class="login-field-icon fui-user" for="login_number"></label>
                </div>

                <div class="control-group">
                    <input type="password" class="login-field" value="" placeholder="Laisser vide pour ne pas changer" name="password_sp" id="signinSrPasswordExample2">
                    <label class="login-field-icon fui-lock" for="signinSrPasswordExample2"></label>
                </div>

                <button type="submit" class="btn btn-block btn-sm btn-primary transition-3d-hover">Modifier</button>
                <a class="login-link" href="list_utilisateurs.php">Retour</a>
            </div>
        </div>
    </div>
</form>

</body>
</html>
