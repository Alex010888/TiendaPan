<?php
include("../../db.php");

// Procesar el borrado del producto
if (isset($_GET['txtID']) && !empty($_GET['txtID'])) {
    $txtID = $_GET['txtID'];

    try {
        // Obtener los datos del producto antes de eliminarlo
        $sentencia = $conn->prepare("SELECT foto FROM productos WHERE id = :id");
        $sentencia->bindParam(':id', $txtID);
        $sentencia->execute();
        $registro_recuperado = $sentencia->fetch(PDO::FETCH_ASSOC);

        // Eliminar la foto del producto si existe
        if (isset($registro_recuperado['foto']) && !empty($registro_recuperado['foto'])) {
            $rutaFoto = "../../uploads/" . $registro_recuperado['foto'];
            if (file_exists($rutaFoto)) {
                unlink($rutaFoto);
            }
        }

        // Preparar y ejecutar la sentencia SQL para eliminar el producto
        $sentencia = $conn->prepare("DELETE FROM productos WHERE id = :id");
        $sentencia->bindParam(':id', $txtID);
        $sentencia->execute();

        echo "Producto eliminado correctamente";
        exit;
    } catch (PDOException $e) {
        echo "Error al eliminar el producto: " . $e->getMessage();
    }
}

// Obtener la lista de productos
try {
    $sentencia = $conn->prepare("SELECT *, (SELECT nombre FROM categorias WHERE categorias.id = productos.categoria_id LIMIT 1) AS categoria FROM productos");
    $sentencia->execute();
    $listar_tbl_pan = $sentencia->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al obtener la lista de productos: " . $e->getMessage();
}

?>

<?php include("../../templates/header.php"); ?>

<br>
<div class="card">
    <div class="table-responsive-sm">
        <table class="table" id="tabla_id">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Categoría</th>
                    <th scope="col">Foto</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody id="productTable">
                <?php foreach ($listar_tbl_pan as $registro) { ?>
                    <tr>
                        <td scope="row"><?php echo $registro["id"]; ?></td>
                        <td><h3><?php echo $registro["nombre"]; ?></h3></td>
                        <td><h3><?php echo $registro["precio"]; ?></h3></td>
                        <td><h3><?php echo $registro["categoria"]; ?></h3></td>
                        <td>
                            <img width="100" src="../../uploads/<?php echo $registro['foto']; ?>" alt="Foto del producto">
                        </td>
                        <td><h3><?php echo $registro["fecha"]; ?></h3></td>
                        <td>
                            <input type="number" min="1" value="1" class="form-control quantity" data-product-price="<?php echo $registro['precio']; ?>">
                        </td>
                        <td>
                            <a class="btn btn-warning add-to-list" href="#" data-product-id="<?php echo $registro['id']; ?>" data-product-name="<?php echo $registro['nombre']; ?>" data-product-price="<?php echo $registro['precio']; ?>">Agregar</a>
                            
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Lista de productos seleccionados -->
<div class="card mt-3">
    <div class="card-body">
        <h5 class="card-title">Lista de Productos Seleccionados</h5>
        <ul id="selectedProductsList" class="list-group"></ul>
        <h5 class="mt-3">Total a Pagar: <span id="totalAmount">$0.00</span></h5>
    </div>
</div>

<?php include("../../templates/footer.php"); ?>
