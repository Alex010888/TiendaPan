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
<div class="card shadow-lg border-0 rounded-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0">🛒 Registrar nuevo producto</h5>
    </div>
    <div class="card-body bg-white">
        <form action="" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="Nombre" class="form-label">Nombre del producto</label>
                <input type="text" class="form-control rounded-pill" name="Nombre" id="Nombre" placeholder="Ej. Pan integral" required>
            </div>

           <div class="mb-3">
    <label for="Foto" class="form-label">Foto del producto</label>
    <input type="file" class="form-control rounded-pill" name="Foto" id="Foto" accept="image/*" required>
    <div class="mt-2 text-center">
        <!-- Vista previa de la imagen -->
        <img id="preview" src="#" alt="Vista previa" class="img-fluid rounded shadow" 
             style="max-height: 200px; display: none; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#imageModal">
    </div>
</div>

<!-- Modal para ver la imagen en grande -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Vista previa en grande</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body text-center">
                <img id="preview-large" src="#" alt="Vista previa en grande" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</div>

  <div class="mb-3">
    <button type="button" class="btn btn-secondary rounded-pill" onclick="activarCamara();">
        <i class="bi bi-camera-fill"></i> Activar Cámara
    </button>
</div>

<div class="camera-container" style="display: none;">
    <video id="video" autoplay class="w-100"></video>
    <canvas id="canvas" style="display: none;"></canvas>
    <div class="btn-container d-flex justify-content-between mt-3">
        <button type="button" class="btn btn-success rounded-pill" name="Foto" id="Foto" onclick="capturarFoto();">
            <i class="bi bi-camera-fill"></i> Capturar Foto
        </button>
        <button type="button" class="btn btn-danger rounded-pill" onclick="apagarCamara();">
            <i class="bi bi-camera-video-off"></i> Apagar Cámara
        </button>
    </div>
</div>

<script>
function activarCamara() {
    const video = document.getElementById('video');
    document.querySelector('.camera-container').style.display = 'block';

    // Solicitar acceso a la cámara del dispositivo
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(stream) {
                video.srcObject = stream;
            })
            .catch(function(error) {
                console.log("Error al acceder a la cámara: ", error);
                alert("No se pudo acceder a la cámara.");
            });
    } else {
        alert("La cámara no está disponible en este navegador.");
    }
}

function capturarFoto() {
    const canvas = document.getElementById('canvas');
    const video = document.getElementById('video');
    const context = canvas.getContext('2d');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    context.drawImage(video, 0, 0, canvas.width, canvas.height);
    const dataUrl = canvas.toDataURL('image/png');
    
    // Mostrar la imagen capturada en la vista previa
    const preview = document.getElementById('preview');
    preview.src = dataUrl;
    preview.style.display = 'block';

    apagarCamara();  // Apagar la cámara después de tomar la foto
}

function apagarCamara() {
    const video = document.getElementById('video');
    const stream = video.srcObject;
    if (stream) {
        const tracks = stream.getTracks();
        tracks.forEach(track => track.stop());  // Detener todas las pistas de video
        video.srcObject = null;
    }
    document.querySelector('.camera-container').style.display = 'none';  // Ocultar el contenedor de la cámara
}
</script>


<script>
document.getElementById('Foto').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('preview');
    const previewLarge = document.getElementById('preview-large');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewLarge.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
});
</script>



            <div class="mb-3">
                <label for="Precio" class="form-label">Precio</label>
                <input type="text" class="form-control rounded-pill" name="Precio" id="Precio" placeholder="Ej. 1.50" required>
            </div>

            <div class="mb-3">
                <label for="Categoria" class="form-label">Categoría</label>
                <select class="form-select rounded-pill" name="Categoria" id="Categoria" required>
                    <option value="">Seleccione una categoría</option>
                    <?php
                    try {
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
                <textarea class="form-control rounded-3" name="Descripcion" id="Descripcion" rows="2" placeholder="Breve descripción del producto..."></textarea>
            </div>

            <div class="mb-3">
                <label for="Fecha" class="form-label">Fecha</label>
                <input type="date" class="form-control rounded-pill" name="Fecha" id="Fecha">
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success rounded-pill">
                    <i class="bi bi-check-circle"></i> Agregar
                </button>
                <a class="btn btn-outline-primary rounded-pill" href="index.php">
                    <i class="bi bi-arrow-left-circle"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>


<?php include("../../templates/footer.php"); ?>
