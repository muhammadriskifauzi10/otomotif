<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Otomotif</title>
    <link rel="stylesheet" href="public/css/bootstrap.min.css">
    <link rel="stylesheet" href="public/css/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        @keyframes showLoading {
            from {
                left: -100%;
                width: 0%;
                opacity: 0;
            }

            to {
                left: 0;
                width: 100%;
                opacity: 1;
            }
        }

        @keyframes pulse {
            from {
                opacity: 1;
                transform: scale(1);
            }

            to {
                opacity: .25;
                transform: scale(.75);
            }
        }

        * {
            box-sizing: border-box;
        }

        body {
            position: relative;
        }

        #parent-spinner-box {
            display: none;
        }

        .spinner-box {
            position: fixed;
            top: 0;
            height: 100vh;
            background-color: rgba(0, 0, 0, .9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            animation: showLoading .1s linear forwards;
        }

        /* PULSE BUBBLES */
        .pulse-container {
            width: 120px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .pulse-bubble {
            width: 20px;
            height: 20px;
            border-radius: 50%;
        }

        .pulse-bubble-1 {
            animation: pulse .4s ease 0s infinite alternate;
        }

        .pulse-bubble-2 {
            animation: pulse .4s ease .2s infinite alternate;
        }

        .pulse-bubble-3 {
            animation: pulse .4s ease .4s infinite alternate;
        }

        table * {
            text-align: center !important;
            vertical-align: middle !important;
        }
    </style>
</head>

<body>

    <?php
    $url = $_SERVER['REQUEST_URI'];
    $explode_url = explode('/', $url);
    ?>

    <?php if (end($explode_url) == '' || end($explode_url) == 'index.php' || end($explode_url) == 'data.php') : ?>
        <nav class="navbar navbar-expand-lg bg-primary navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="#">Otomotif</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.php">Beranda</a>
                        </li>
                    </ul>
                    <?php
                    if (isset($_SESSION['email'])) {
                        echo '<div class="d-flex btn-group">
                        <button type="button" class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            ' . $auth->authUserName . '
                        </button>
                        <form action="' . htmlspecialchars($auth->logout()) . '" method="POST" class="dropdown-menu dropdown-menu-end">
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
    <?php endif; ?>
    <!-- Loading -->
    <div id="parent-spinner-box">
        <div class="spinner-box">
            <div class="pulse-container">
                <div class="bg-info pulse-bubble pulse-bubble-1"></div>
                <div class="bg-info pulse-bubble pulse-bubble-2"></div>
                <div class="bg-info pulse-bubble pulse-bubble-3"></div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" id="universalModal">
        </div>
    </div>