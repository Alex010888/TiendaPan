<?php
session_start();

// Configuración de la base de datos
include("db.php");



// Obtener datos del formulario
$usuario = $_POST['usuario'] ?? '';
$clave = $_POST['clave'] ?? '';

if ($usuario && $clave) {
    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($clave, $user['clave'])) {
        $_SESSION['usuario'] = $user['usuario'];
        $_SESSION['usuario_id'] = $user['id'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
} else {
    $error = "Completa todos los campos.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Error de Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <div class="alert alert-danger text-center">
      <?= $error ?>
    </div>
    <div class="text-center">
      <a href="index.html" class="btn btn-secondary">Volver al Login</a>
    </div>
  </div>
</body>
</html>
