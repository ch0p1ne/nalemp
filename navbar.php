
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Nalemp <sup>2</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <?php if ($_SESSION['role'] === 'admin') : ?>
        <!-- Nav Item - Dashboard (visible uniquement pour l'admin) -->
        <li class="nav-item active">
            <a class="nav-link" href="dashboard.php">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Nav Item - Liste des Utilisateurs (visible uniquement pour l'admin) -->
        <li class="nav-item">
            <a class="nav-link" href="list_utilisateurs.php">
                <i class="fas fa-fw fa-users"></i>
                <span>Liste des Utilisateurs</span>
            </a>
        </li>

        <hr class="sidebar-divider d-none d-md-block">
    <?php endif; ?>

    <?php if ($_SESSION['role'] === 'user') : ?>
        <!-- Nav Item - Liste des Pointages (visible uniquement pour les utilisateurs simples) -->
        <li class="nav-item">
            <a class="nav-link" href="list_pointage.php">
                <i class="fas fa-fw fa-table"></i>
                <span>Liste des Pointages</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="pointage_jour.php">
                <i class="fas fa-fw fa-table"></i>
                <span>Pointer</span>
            </a>
        </li>
        <hr class="sidebar-divider">
    <?php endif; ?>

</ul>
