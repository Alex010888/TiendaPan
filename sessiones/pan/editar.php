<?php
include("../../db.php"); // Incluye el archivo de conexión a la base de datos

$producto = null;
if (isset($_GET['txtID']) && !empty($_GET['txtID'])) {
    $txtID = $_GET['txtID'];

    // Preparar y ejecutar la sentencia SQL para obtener los datos del producto
    $sentencia = $conn->prepare("SELECT * FROM productos WHERE id = :id");
    $sentencia->bindParam(':id', $txtID);
    $sentencia->execute();
    $producto = $sentencia->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $txtID = $_POST['txtID'];
    $nombre = $_POST['Nombre'];
    $precio = $_POST['Precio'];
    $categoria_id = $_POST['Categoria'];
    $descripcion = $_POST['Descripcion'];
    $fecha = $_POST['Fecha'];

    // Procesar la subida de la foto
    $foto = $producto['foto'];
    if (isset($_FILES['Foto']) && $_FILES['Foto']['error'] == 0) {
        $foto = basename($_FILES['Foto']['name']);
        $rutaDestino = "../../uploads/" . $foto;
        move_uploaded_file($_FILES['Foto']['tmp_name'], $rutaDestino);
    }

    // Conexión a la base de datos usando PDO
    try {
       

        // Sentencia SQL para actualizar el producto
        $sql = "UPDATE productos SET nombre = :nombre, precio = :precio, foto = :foto, categoria_id = :categoria_id, descripcion = :descripcion, fecha = :fecha WHERE id = :id";
        $sentencia = $conn->prepare($sql);
        $sentencia->bindParam(':nombre', $nombre);
        $sentencia->bindParam(':precio', $precio);
        $sentencia->bindParam(':foto', $foto);
        $sentencia->bindParam(':categoria_id', $categoria_id);
        $sentencia->bindParam(':descripcion', $descripcion);
        $sentencia->bindParam(':fecha', $fecha);
        $sentencia->bindParam(':id', $txtID);

        $sentencia->execute();

        header('Location:editar.php');

        echo "Registro actualizado exitosamente";
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $conn = null;
}

?>

<?php include("../../templates/header.php"); ?>

<div class="card">
    <div class="card-header">Datos para registrar productos</div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="txtID" value="<?php echo isset($producto['id']) ? $producto['id'] : ''; ?>" />
            <div class="mb-3">
                <label for="Nombre" class="form-label">Nombre del producto</label>
                <input type="text" class="form-control" name="Nombre" id="Nombre" aria-describedby="helpId" placeholder="Nombre" value="<?php echo isset($producto['nombre']) ? $producto['nombre'] : ''; ?>" />
            </div>
            <div class="mb-3">
                <label for="Foto" class="form-label">Foto del producto</label>
                <input type="file" class="form-control" name="Foto" id="Foto" aria-describedby="helpId" placeholder="Foto" />
                <?php if(isset($producto['foto'])): ?>
                    <img src="../../uploads/<?php echo $producto['foto']; ?>" width="100" alt="">
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="Precio" class="form-label">Precio</label>
                <input type="text" class="form-control" name="Precio" id="Precio" aria-describedby="helpId" placeholder="Precio" value="<?php echo isset($producto['precio']) ? $producto['precio'] : ''; ?>" />
            </div>
            <div class="mb-3">
                <label for="Categoria" class="form-label">Categoria</label>
                <select class="form-select form-select-sm" name="Categoria" id="Categoria">
                    <option value="">Seleccione una categoría</option>
                    <?php
                    // Conexión a la base de datos usando PDO
                    try {
                      

                        // Consulta para obtener las categorías
                        $sentencia = $conn->prepare("SELECT id, nombre FROM categorias");
                        $sentencia->execute();
                        $listar_tbl_pan = $sentencia->fetchAll(PDO::FETCH_ASSOC);

                        
                        foreach ($listar_tbl_pan as $categoria) {
                            $selected = ($categoria['id'] == $producto['categoria_id']) ? "selected" : "";
                            echo "<option value='" . $categoria['id'] . "' $selected>" . $categoria['nombre'] . "</option>";
                        }
                    } catch(PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="Descripcion" class="form-label">Descripcion</label>
                <input type="text" class="form-control" name="Descripcion" id="Descripcion" aria-describedby="helpId" placeholder="Descripcion" value="<?php echo isset($producto['descripcion']) ? $producto['descripcion'] : ''; ?>" />
            </div>
            <div class="mb-3">
                <label for="Fecha" class="form-label">Fecha</label>
                <input type="date" class="form-control" name="Fecha" id="Fecha" aria-describedby="emailHelpId" placeholder="Fecha" value="<?php echo isset($producto['fecha']) ? $producto['fecha'] : ''; ?>" />
            </div>
            <button type="submit" class="btn btn-success">Actualizar</button>
            <a class="btn btn-primary" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
</div>

<?php include("../../templates/footer.php"); ?>
