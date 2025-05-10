<?php
// Ejecuta el comando ipconfig y almacena la salida en un array
$output = [];
exec("ipconfig", $output);

// Filtrar las líneas que contienen "IPv4"
foreach ($output as $line) {
    if (strpos($line, "IPv4") !== false) {
        // Extraer la IP de la línea
        $parts = explode(":", $line);
        if (isset($parts[1])) {
            $ip = trim($parts[1]);
            echo "IP local del servidor: " . $ip . "<br>";
        }
    }
}
?>
