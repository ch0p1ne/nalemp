<?php

session_start(); // Démarrer la session

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

include('database_connection.php'); // Connexion à la base de données

// Définir l'heure limite de pointage (exemple : 09h00)
$heure_limite = "09:00:00";  
$date_du_jour = date('Y-m-d'); 

// Récupérer le nombre total d'utilisateurs
$stmt_total = $connect->prepare("SELECT COUNT(*) AS total_users FROM users");
$stmt_total->execute();
$result_total = $stmt_total->fetch(PDO::FETCH_ASSOC);
$total_users = $result_total['total_users'] ?? 0;

// Récupérer le nombre d'utilisateurs ayant pointé aujourd'hui
$stmt = $connect->prepare("SELECT COUNT(DISTINCT user_id) AS total FROM pointage WHERE date = :date_du_jour");
$stmt->bindParam(':date_du_jour', $date_du_jour);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$total_users_today = $result['total'] ?? 0;

// 🔴 Correction ici
$total_pointe = $total_users_today;

// Calculer ceux qui n'ont pas pointé
$total_non_pointe = max(0, $total_users - $total_pointe);

// Récupérer le nombre d'utilisateurs ayant pointé en retard aujourd'hui
$stmt_retard = $connect->prepare("SELECT COUNT(DISTINCT user_id) AS total_retard FROM pointage WHERE date = :date_du_jour AND heure_entree > :heure_limite");
$stmt_retard->bindParam(':date_du_jour', $date_du_jour);
$stmt_retard->bindParam(':heure_limite', $heure_limite);
$stmt_retard->execute();
$result_retard = $stmt_retard->fetch(PDO::FETCH_ASSOC);
$total_retard = $result_retard['total_retard'] ?? 0;

// Définir l'heure de descente et l'heure limite des heures supp
$heure_descente = "17:00:00";  
$heure_supp = "19:00:00"; 

// 1️⃣ Récupérer le nombre d'utilisateurs qui sont sortis avant l'heure de descente
$stmt_sortie_avant = $connect->prepare("SELECT COUNT(DISTINCT user_id) AS total_sortie_avant FROM pointage WHERE date = :date_du_jour AND heure_sortie < :heure_descente");
$stmt_sortie_avant->bindParam(':date_du_jour', $date_du_jour);
$stmt_sortie_avant->bindParam(':heure_descente', $heure_descente);
$stmt_sortie_avant->execute();
$result_sortie_avant = $stmt_sortie_avant->fetch(PDO::FETCH_ASSOC);
$total_sortie_avant = $result_sortie_avant['total_sortie_avant'] ?? 0;

// 2️⃣ Récupérer le nombre d'utilisateurs ayant fait des heures supplémentaires
$stmt_heures_supp = $connect->prepare("SELECT COUNT(DISTINCT user_id) AS total_heures_supp FROM pointage WHERE date = :date_du_jour AND heure_sortie > :heure_supp");
$stmt_heures_supp->bindParam(':date_du_jour', $date_du_jour);
$stmt_heures_supp->bindParam(':heure_supp', $heure_supp);
$stmt_heures_supp->execute();
$result_heures_supp = $stmt_heures_supp->fetch(PDO::FETCH_ASSOC);
$total_heures_supp = $result_heures_supp['total_heures_supp'] ?? 0;

?>



<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div>

                    <div class="row">
    <!-- Utilisateurs ayant pointé aujourd'hui -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2" onclick="window.location.href='users_pointe.php'">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Utilisateurs ayant pointé aujourd'hui
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $total_users_today; ?> personnes
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Utilisateurs n'ayant pas pointé aujourd'hui -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2" onclick="window.location.href='users_non_pointe.php'">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Utilisateurs n'ayant pas pointé aujourd'hui
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $total_non_pointe; ?> personnes
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-times fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Utilisateurs en retard aujourd'hui -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2" onclick="window.location.href='users_retard.php'">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Utilisateurs en retard aujourd'hui
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                    <?php echo $total_retard; ?> personnes
                                </div>
                            </div>
                            <div class="col">
                                <div class="progress progress-sm mr-2">
                                    <div class="progress-bar bg-warning" role="progressbar"
                                         style="width: <?php echo min(100, $total_retard * 10); ?>%"
                                         aria-valuenow="<?php echo $total_retard; ?>" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sortis avant l'heure de descente -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2" onclick="window.location.href='users_sortie_avant.php'">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Sortis avant l'heure de descente
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $total_sortie_avant; ?> personnes
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-door-open fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Heures supplémentaires -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2" onclick="window.location.href='users_heures_supp.php'">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Heures supplémentaires
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $total_heures_supp; ?> personnes
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


                    <!-- Content Row -->


                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
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
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>