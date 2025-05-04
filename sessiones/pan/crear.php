<?php
include("../../db.php"); // Incluye el archivo de conexión a la base de datos

// Incluimos el header
include("../../templates/header.php");

// Procesar el formulario al ser enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['Nombre'];
    $precio = $_POST['Precio'];
    $categoria_id = $_POST['Categoria'];
    $descripcion = isset($_POST['Descripcion']) ? $_POST['Descripcion'] : '';
    $fecha = isset($_POST['Fecha']) ? $_POST['Fecha'] : '';

    // Procesar la subida de la foto
    $foto = ''; // Variable para almacenar el nombre de la foto
    if (isset($_FILES['Foto']) && $_FILES['Foto']['error'] == 0) {
        // Generar un nombre único para la foto
        $nombreArchivo_foto = time() . "_" . $_FILES['Foto']['name'];
        // Ruta de destino para la foto
        $rutaDestino = "../../uploads/" . $nombreArchivo_foto;
        // Mover la foto del directorio temporal al directorio de destino
        if (move_uploaded_file($_FILES['Foto']['tmp_name'], $rutaDestino)) {
            $foto = $nombreArchivo_foto; // Asignar el nombre de la foto a la variable $foto
        } else {
            echo "Error al subir la foto.";
            exit;
        }
    } else {
        echo "Error al subir la foto.";
        exit;
    }

    // Conexión a la base de datos usando PDO
    try {
        // Sentencia SQL para insertar el producto
        $sql = "INSERT INTO productos (nombre, precio, foto, categoria_id, descripcion, fecha) 
                VALUES (:nombre, :precio, :foto, :categoria_id, :descripcion, :fecha)";
        $sentencia = $conn->prepare($sql);
        $sentencia->bindParam(':nombre', $nombre);
        $sentencia->bindParam(':precio', $precio);
        $sentencia->bindParam(':foto', $foto);
        $sentencia->bindParam(':categoria_id', $categoria_id);
        $sentencia->bindParam(':descripcion', $descripcion);
        $sentencia->bindParam(':fecha', $fecha);

        $sentencia->execute();
        echo "Nuevo registro creado exitosamente";
        header("Location: crear.php?status=success");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Cerramos la conexión
    $conn = null;
}
?>

<br>
<div class="card">
    <div class="card-header">Datos para registrar productos</div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="Nombre" class="form-label">Nombre del producto</label>
                <input type="text" class="form-control" name="Nombre" id="Nombre" placeholder="Nombre" required />
            </div>
            <div class="mb-3">
                <label for="Foto" class="form-label">Foto del producto</label>
                <input type="file" class="form-control" name="Foto" id="Foto" required />
            </div>
            <div class="mb-3">
                <label for="Precio" class="form-label">Precio</label>
                <input type="text" class="form-control" name="Precio" id="Precio" placeholder="Precio" required />
            </div>
            <div class="mb-3">
                <label for="Categoria" class="form-label">Categoría</label>
                <select class="form-select form-select-sm" name="Categoria" id="Categoria" required>
                    <option value="">Seleccione una categoría</option>
                    <?php
                    // Conexión a la base de datos usando PDO
                    try {
                        // Consulta para obtener las categorías
                        $sentencia = $conn->prepare("SELECT id, nombre FROM categorias");
                        $sentencia->execute();
                        $categorias = $sentencia->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($categorias as $categoria) {
                            echo "<option value='" . $categoria['id'] . "'>" . $categoria['nombre'] . "</option>";
                        }
                    } catch(PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="Descripcion" class="form-label">Descripción</label>
                <input type="text" class="form-control" name="Descripcion" id="Descripcion" placeholder="Descripción" />
            </div>
            <div class="mb-3">
                <label for="Fecha" class="form-label">Fecha</label>
                <input type="date" class="form-control" name="Fecha" id="Fecha" />
            </div>
            <button type="submit" class="btn btn-success">Agregar</button>
            <a class="btn btn-primary" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
</div>

<?php include("../../templates/footer.php"); ?>
