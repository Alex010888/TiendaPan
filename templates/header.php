<?php
// URL base
//$url_base = "http://localhost/TiendaPan/";
$url_base = "http://192.168.158.207/TiendaPan/";
session_start();

// Control de caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION["usuario"])) {
    header("Location: " . $url_base . "login.php");
    exit();
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Sistema de Panadería</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" 
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" 
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" />

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" 
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" 
            crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        footer {
            text-align: center;
            padding: 20px;
            background-color: #f1f1f1;
        }
        .camera-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        margin-top: 20px;
    }

    #video {
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        width: 300px;
        height: 220px;
    }

    .btn-container {
        display: flex;
        gap: 10px;
        margin-top: 10px;
        justify-content: center;
    }

    .btn {
        min-width: 150px;
    }
    </style>
</head>
<body>
<header>
    <!-- Puedes agregar un logo aquí si deseas -->
</header>

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow mb-4">
    <div class="container justify-content-center">
        <ul class="navbar-nav d-flex flex-row gap-4 text-center">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_base ?>index.php">
                    <i class="bi bi-house-door-fill"></i><br>Sistema
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_base ?>sessiones/pan/venta.php">
                    <i class="bi bi-cart-check-fill"></i><br>Venta rápida
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_base ?>sessiones/pan/">
                    <i class="bi bi-box-seam"></i><br>Ingreso productos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_base ?>sessiones/usuario/">
                    <i class="bi bi-people-fill"></i><br>Usuarios
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-danger" href="<?php echo $url_base ?>salir.php">
                    <i class="bi bi-box-arrow-right"></i><br>Cerrar sesión
                </a>
            </li>
        </ul>
    </div>
</nav>

<main class="container">
<!-- Aquí va el contenido principal -->
