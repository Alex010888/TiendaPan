<?php 
include("../../db.php");

// Obtener lista de puestos de la base de datos
$sentencia = $conn->prepare("SELECT id, nombre FROM puestos");
$sentencia->execute();
$puestos = $sentencia->fetchAll(PDO::FETCH_ASSOC);

if ($_POST) {
    $nombre = isset($_POST["Nombre"]) ? $_POST["Nombre"] : '';
    $apellido = isset($_POST["Apellido"]) ? $_POST["Apellido"] : '';
    $puesto = isset($_POST["Puesto"]) ? $_POST["Puesto"] : '';
    $fecha = isset($_POST["Fecha"]) ? $_POST["Fecha"] : '';
    $pass = isset($_POST["Pass"]) ? $_POST["Pass"] : '';

    try {
        $sql = "INSERT INTO usuario (nombre, apellido, puesto, fecha, pass)
                VALUES (:nombre, :apellido, :puesto, :fecha, :pass)";
        $sentencia = $conn->prepare($sql);
        $sentencia->bindParam(':nombre', $nombre);
        $sentencia->bindParam(':apellido', $apellido);
        $sentencia->bindParam(':puesto', $puesto);
        $sentencia->bindParam(':fecha', $fecha);
        $sentencia->bindParam(':pass', $pass);
        
        $sentencia->execute();
        header("Location: crear.php?status=success");
        exit();
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $conn = null;
}
?>

<?php include("../../templates/header.php"); ?>

<div class="card">
    <div class="card-header">Datos</div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="Nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="Nombre" id="Nombre" placeholder="Nombre" required />
            </div>
            <div class="mb-3">
                <label for="Apellido" class="form-label">Apellido</label>
                <input type="text" class="form-control" name="Apellido" id="Apellido" placeholder="Apellido" required />
            </div>
            <div class="mb-3">
                <label for="Puesto" class="form-label">Puesto</label>
                <select class="form-select form-select-sm" name="Puesto" id="Puesto" required>
                    <option value="" selected disabled>Select one</option>
                    <?php foreach ($puestos as $puesto) { ?>
                        <option value="<?php echo $puesto['id']; ?>"><?php echo $puesto['nombre']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="Fecha" class="form-label">Fecha</label>
                <input type="date" class="form-control" name="Fecha" id="Fecha" placeholder="Fecha" required />
            </div>
            <div class="mb-3">
                <label for="Pass" class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="Pass" id="Pass" placeholder="Contraseña" required />
            </div>
            <button type="submit" class="btn btn-success">Agregar</button>
            <a class="btn btn-primary" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
    <div class="card-footer text-muted">Footer</div>
</div>

<?php include("../../templates/footer.php"); ?>
