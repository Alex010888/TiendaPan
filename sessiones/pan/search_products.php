<?php
include("../../db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $searchText = $_POST['searchText'];

    try {
        $sql = "SELECT *, (SELECT nombre FROM categorias WHERE categorias.id = productos.categoria_id LIMIT 1) AS categoria 
                FROM productos 
                WHERE LOWER(nombre) LIKE :searchText" ;
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':searchText', '%' . strtolower($searchText) . '%', PDO::PARAM_STR);
        $stmt->execute();
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($productos as $producto) {
            echo '<tr>
                    <td scope="row">' . $producto['id'] . '</td>
                    <td>' . $producto['nombre'] . '</td>
                    <td>' . $producto['precio'] . '</td>
                    <td>' . $producto['categoria'] . '</td>
                    <td>
                        <img width="100" src="../../uploads/' . $producto['foto'] . '" alt="Foto del producto">
                    </td>
                    <td>' . $producto['fecha'] . '</td>
                    <td>
                        <a class="btn btn-info" href="editar.php?txtID=' . $producto['id'] . '">Editar</a>
                        <a class="btn btn-danger delete-product" href="#" data-product-id="' . $producto['id'] . '">Eliminar</a>
                    </td>
                </tr>';
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
