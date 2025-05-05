<?php
session_start();

// Redirige al index si ya está logueado
if (isset($_SESSION["usuario"])) {
    header("Location: index.php"); // o dashboard.php
    exit();
}

// Control de caché para evitar volver atrás con sesión cerrada
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

include("db.php"); // Asegúrate que $conn sea un objeto PDO

$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $clave = $_POST["clave"];

    // Consulta preparada con PDO
    $sql = "SELECT * FROM usuario WHERE usuario = :usuario AND clave = :clave";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->bindParam(':clave', $clave);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $_SESSION['usuario'] = $usuario;
        header("Location: index.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Panadería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
            <h4 class="text-center mb-4">Iniciar Sesión</h4>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="post" action="">
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario</label>
                    <input type="text" class="form-control" id="usuario" name="usuario" required>
                </div>
                <div class="mb-3 position-relative">
    <label for="clave" class="form-label">Contraseña</label>
    <div class="input-group">
        <input type="password" class="form-control" id="clave" name="clave" required>
        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
            <i class="bi bi-eye-slash" id="iconoOjo"></i>
        </button>
    </div>
</div>
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>
        </div>
    </div>
</body>
<script>
document.getElementById("togglePassword").addEventListener("click", function () {
    const claveInput = document.getElementById("clave");
    const icono = document.getElementById("iconoOjo");

    if (claveInput.type === "password") {
        claveInput.type = "text";
        icono.classList.remove("bi-eye-slash");
        icono.classList.add("bi-eye");
    } else {
        claveInput.type = "password";
        icono.classList.remove("bi-eye");
        icono.classList.add("bi-eye-slash");
    }
});
</script>

</html>
