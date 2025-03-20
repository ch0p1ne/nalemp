<?php
session_start();
include('database_connection.php'); // Connexion à la base de données

/// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirige vers la connexion si non connecté
    exit();
}

// Vérifie si l'utilisateur est un admin
if ($_SESSION['role'] !== 'user') {
    header("Location: unauthorized.php"); // Redirige vers une page d'erreur
    exit();
}

$user_id = $_SESSION['user_id']; // ID de l'utilisateur connecté

// Configurer la locale en français (avant d'utiliser strftime)
setlocale(LC_TIME, 'fr_FR.UTF-8', 'fr_FR', 'French_France', 'French');

// Récupérer les pointages de l'utilisateur connecté
$stmt = $connect->prepare("SELECT * FROM pointage WHERE user_id = :user_id ORDER BY date DESC");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$pointages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fonction pour formater la date en "Jeudi 13 mai 2024"
function formatDate($date) {
    return strftime('%A %d %B %Y', strtotime($date));
}
?>







<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Tables</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

    <?php include "navbar.php"?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include "topbar.php"?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Tables</h1>
                   
 <!-- Affichage des données -->

<!-- Affichage des données -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Utilisateurs ayant fait des heures supplémentaires</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-bordered" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>#</th>
            <th>Heure Entrée</th>
            <th>Heure Sortie</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($pointages)): ?>
            <?php foreach ($pointages as $index => $pointage): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td>
                        <?= htmlspecialchars($pointage['heure_entree']) ?> 
                       
                    </td>
                    <td> <?= $pointage['heure_sortie'] ? htmlspecialchars($pointage['heure_sortie']) : '-' ?></td>
                    <td><?= ucfirst(formatDate($pointage['date'])) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3" class="text-center">Aucun pointage trouvé</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

        </div>
    </div>
</div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>