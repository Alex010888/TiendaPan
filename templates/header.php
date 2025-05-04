<?php
//URL base
$url_base="http://localhost/TiendaPan/";
//$url_base="http:// 192.168.1.44/TiendaPan/";
?>
<!doctype html>
<html lang="es">
    <head>
        
        <title>Sistema de panaderia</title>
        <style>
    footer {
            text-align: center;
            padding: 20px;
            background-color: #f1f1f1; /* Opcional: añade un color de fondo al footer */
        }
        </style>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">


        <script src="https://code.jquery.com/jquery-3.7.1.min.js" 
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" 
        crossorigin="anonymous">
        </script>

<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" />
 
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    </head>

    <body>
        <header>
            <!-- place navbar here -->
        </header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light shadow mb-4">
  <div class="container justify-content-center">
    <ul class="navbar-nav d-flex flex-row gap-4 text-center">
      <li class="nav-item">
     <!--     <a class="nav-link" href="../../index.php"> -->
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
        <a class="nav-link" href="<?php echo $url_base?>sessiones/pan/">
          <i class="bi bi-box-seam"></i><br>Ingreso productos
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo $url_base?>sessiones/usuario/">
          <i class="bi bi-people-fill"></i><br>Usuarios
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-danger" href="#">
          <i class="bi bi-box-arrow-right"></i><br>Cerrar sesión
        </a>
      </li>
    </ul>
  </div>
</nav>
        <main class="container">