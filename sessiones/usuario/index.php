<?php
include("../../db.php");

if(isset($_GET['txtID'])) {
   $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";

   $sentencia = $conn->prepare("DELETE FROM usuario WHERE id=:id");
   $sentencia->bindParam(":id", $txtID);
   $sentencia->execute();
   $mensaje = "Registro eliminado";
   header('Location:index.php?mensaje='.$mensaje);
}

try {
    // Consulta para obtener los usuarios junto con el nombre del puesto
    $sql = "SELECT usuario.id, usuario.nombre, usuario.apellido, puestos.nombre AS puesto, usuario.fecha, usuario.pass 
            FROM usuario 
            JOIN puestos ON usuario.puesto = puestos.id";
    $sentencia = $conn->prepare($sql);
    $sentencia->execute();
    $usuarios = $sentencia->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<?php include("../../templates/header.php"); ?>
<?php 
if(isset($_GET['mensaje'])){
?>
<script>
    Swal.fire({icon:"success",title:"<?php echo $_GET['mensaje']; ?>"});
</script>
<?php } ?>
<br/>
<div class="card">
    <div class="card-header"><h4>Usuarios</h4></div>
    <a
        name=""
        id=""
        class="btn btn-primary"
        href="crear.php"
        role="button"
        >Agregar registro</a
    >
    <div class="card-body">
        <div class="table-responsive">
            <table class="table" id = "tabla_id">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Apellido</th>
                        <th scope="col">Puesto</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Contraseña</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($usuarios as $registro) { ?>
                    <tr class="">
                        <td scope="row"><?php echo $registro['id']; ?></td>
                        <td><?php echo $registro['nombre']; ?></td>
                        <td><?php echo $registro['apellido']; ?></td>
                        <td><?php echo $registro['puesto']; ?></td>
                        <td><?php echo $registro['fecha']; ?></td>
                        <td><?php echo $registro['pass']; ?></td>
                       <td>
                        <a
                            name=""
                            id=""
                            class="btn btn-danger"
                            href="javascript:borrar(<?php echo $registro['id']; ?>);"
                            role="button"
                            >Eliminar</a
                        >
                        <a
                            name=""
                            id=""
                            class="btn btn-info"
                            href="editar.php?txtID=<?php echo $registro['id']; ?>"
                            role="button"
                            >Editar</a
                        >
                       </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
      function borrar(id){
       
        Swal.fire({
           title: "Deseas borrar el registro?",

           showCancelButton: true,
           confirmButtonText: "Si, borrar",

         }).then((result) => {
 
            if (result.isConfirmed) {
            window.location="index.php?txtID="+id;
        }
    })
}
    </script>
</div>

<?php include("../../templates/footer.php"); ?>
