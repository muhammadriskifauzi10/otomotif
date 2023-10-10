<?php
include 'application/Auth.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    unset($_SESSION['email']);
    header('location: index.php');
}
?>

<nav class="navbar navbar-expand-lg bg-primary navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Otomotif</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Beranda</a>
                </li>
            </ul>
            <?php
            if (isset($_SESSION['email'])) {
                echo '<div class="d-flex btn-group">
                        <button type="button" class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            ' . $auth->authUserName . '
                        </button>
                        <form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="POST" class="dropdown-menu dropdown-menu-end">
                            <button type="submit" class="dropdown-item" type="button">Keluar</button></li>
                        </form>
                    </div>';
            } else {
                echo '<div class="d-flex btn-group">
                <a href="login.php" class="btn btn-dark">Login</a>
                </div>';
            }
            ?>
        </div>
    </div>
</nav>