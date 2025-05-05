<?php
include("../../db.php");

function eliminarProducto($conn, $txtID) {
    try {
        // Obtener los datos del producto antes de eliminarlo
        $sentencia = $conn->prepare("SELECT foto FROM productos WHERE id = :id");
        $sentencia->bindParam(':id', $txtID, PDO::PARAM_INT);
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
        $sentencia->bindParam(':id', $txtID, PDO::PARAM_INT);
        $sentencia->execute();

        header('Location:index.php');
        exit;
    } catch (PDOException $e) {
        echo "Error al eliminar el producto: " . $e->getMessage();
    }
}

if (isset($_GET['txtID']) && !empty($_GET['txtID'])) {
    eliminarProducto($conn, $_GET['txtID']);
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
<div class="card shadow-lg border-0 rounded-4">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">📋 Lista de productos</h5>
        <a class="btn btn-light btn-sm rounded-pill" href="crear.php">
            <i class="bi bi-plus-circle"></i> Agregar producto
        </a>
    </div>

    <div class="card-body bg-white">
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center" id="tabla_id">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Categoría</th>
                        <th>Foto</th>
                        <th>Fecha</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody id="productTable">
                    <?php foreach ($listar_tbl_pan as $registro): ?>
                        <tr>
                            <td><?= htmlspecialchars($registro["id"]) ?></td>
                            <td><?= htmlspecialchars($registro["nombre"]) ?></td>
                            <td>$<?= number_format($registro["precio"], 2) ?></td>
                            <td><?= htmlspecialchars($registro["categoria"]) ?></td>
                            <td>
                                <img class="rounded" width="80" src="../../uploads/<?= htmlspecialchars($registro['foto']) ?>" alt="Foto del producto">
                            </td>
                            <td><?= htmlspecialchars($registro["fecha"]) ?></td>
                            <td>
                                <a class="btn btn-outline-info btn-sm rounded-pill" href="editar.php?txtID=<?= htmlspecialchars($registro['id']) ?>">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button class="btn btn-outline-danger btn-sm rounded-pill delete-product" data-product-id="<?= htmlspecialchars($registro['id']) ?>">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-product').forEach(function (button) {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                const productId = this.getAttribute('data-product-id');
                if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
                    window.location.href = `index.php?txtID=${productId}`;
                }
            });
        });
    });
</script>
<script>
    alert(id);
    Swal.fire("Borrar...");
    </script>
<?php include("../../templates/footer.php"); ?>


<!-- Script de AJAX para la búsqueda en vivo y eliminar producto -->
<!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // AJAX para búsqueda en vivo
        $('#searchInput').on('keyup', function() {
            var searchText = $(this).val().toLowerCase();
            $.ajax({
                url: 'search_products.php',
                method: 'POST',
                data: { searchText: searchText },
                success: function(response) {
                    $('#productTable').html(response);
                }
            });
        });

        // AJAX para eliminar producto
        $('.delete-product').on('click', function(e) {
            e.preventDefault();
            var productId = $(this).data('product-id');
            if (confirm('¿Está seguro de eliminar este producto?')) {
                $.ajax({
                    url: 'index.php',
                    method: 'GET',
                    data: { txtID: productId },
                    success: function(response) {
                        alert('Producto eliminado correctamente');
                        // Recargar la lista de productos después de eliminar
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        alert('Error al eliminar el producto');
                        console.error(error);
                    }
                });
            }
        });
    });
</script>-->
