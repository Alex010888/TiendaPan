<?php 
include '../../db.php';

// Obtener lista de puestos de la base de datos
$sentencia = $conn->prepare("SELECT id, nombre FROM puestos");
$sentencia->execute();
$puestos = $sentencia->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['txtID']) && !empty($_GET['txtID'])) {
    $txtID = $_GET['txtID'];

    // Obtener los datos del usuario antes de editarlos
    $sentencia = $conn->prepare("SELECT * FROM usuario WHERE id = :id");
    $sentencia->bindParam(':id', $txtID);
    $sentencia->execute();
    $registro_recuperado = $sentencia->fetch(PDO::FETCH_LAZY);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar el formulario de edición
    $nombre = isset($_POST["Nombre"]) ? $_POST["Nombre"] : '';
    $apellido = isset($_POST["Apellido"]) ? $_POST["Apellido"] : '';
    $puesto = isset($_POST["Puesto"]) ? $_POST["Puesto"] : '';
    $fecha = isset($_POST["Fecha"]) ? $_POST["Fecha"] : '';
    $pass = isset($_POST["Pass"]) ? $_POST["Pass"] : '';

    $sentencia = $conn->prepare("UPDATE usuario SET nombre = :nombre,
    apellido = :apellido,
    puesto = :puesto,
    fecha = :fecha,
    pass = :pass
    WHERE id = :id");

    $sentencia->bindParam(':nombre', $nombre);
    $sentencia->bindParam(':apellido', $apellido);
    $sentencia->bindParam(':puesto', $puesto);
    $sentencia->bindParam(':fecha', $fecha);
    $sentencia->bindParam(':pass', $pass);
    $sentencia->bindParam(':id', $txtID);
    $sentencia->execute();

    header("Location: index.php");
    exit;
}
?>

<?php 
include("../../templates/header.php");
?>

<div class="card">
    <div class="card-header">Datos</div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="txtID" class="form-label">ID</label>
                <input type="text" class="form-control" readonly name="txtID" id="txtID" value="<?php echo $registro_recuperado['id']; ?>" />
            </div>
            <div class="mb-3">
                <label for="Nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="Nombre" id="Nombre" value="<?php echo $registro_recuperado['nombre']; ?>" placeholder="Nombre" />
            </div>
            <div class="mb-3">
                <label for="Apellido" class="form-label">Apellido</label>
                <input type="text" class="form-control" name="Apellido" id="Apellido" value="<?php echo $registro_recuperado['apellido']; ?>" placeholder="Apellido" />
            </div>
            <div class="mb-3">
                <label for="Puesto" class="form-label">Puesto</label>
                <select class="form-select form-select-sm" name="Puesto" id="Puesto">
                    <option selected>Select one</option>
                    <?php foreach ($puestos as $puesto) { ?>
                        <option value="<?php echo $puesto['id']; ?>" <?php if ($puesto['id'] == $registro_recuperado['puesto']) echo 'selected'; ?>>
                            <?php echo $puesto['nombre']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="Fecha" class="form-label">Fecha</label>
                <input type="date" class="form-control" name="Fecha" id="Fecha" value="<?php echo $registro_recuperado['fecha']; ?>" placeholder="Fecha" />
            </div>
            <div class="mb-3">
                <label for="Pass" class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="Pass" id="Pass" value="<?php echo $registro_recuperado['pass']; ?>" placeholder="Contraseña" />
            </div>
            <button type="submit" class="btn btn-success">Guardar Cambios</button>
            <a class="btn btn-primary" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
    <div class="card-footer text-muted">Footer</div>
</div>

<?php
include("../../templates/footer.php");
?>
