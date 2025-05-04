<?php include("templates/header.php");
 ?>
 

 <div class="p-5 mb-4 bg-warning bg-opacity-25 rounded-4 shadow-sm">
    <div class="container-fluid py-5 text-center">
        <h1 class="display-4 fw-bold text-dark">
            <i class="bi bi-bag-heart-fill me-2"></i>¡Sabores que unen familias!
        </h1>
        <p class="fs-5 text-secondary col-md-10 mx-auto">
            En nuestra tienda Dulce Vida encontrarás el pan más fresco, dulces para cada sonrisa y piñatas que llenan de alegría tus celebraciones.
        </p>
        <a href="<?php echo $url_base ?>sessiones/pan/venta.php" class="btn btn-outline-primary btn-lg mt-3">
            <i class="bi bi-cart-plus-fill me-1"></i> Comenzar una venta
        </a>
    </div>
</div>

         

         <?php include("templates/footer.php");?>
