<?php
session_start();

require_once '../servidor/config.php';
include_once '../gestores/GestorProductos.php';
include_once '../gestores/Producto.php';
require_once '../servidor/seguridad.php';

$codigo = htmlspecialchars(trim($_GET['codigo']));

$db = conectar();

$gestor = new GestorProductos($db);

$producto = $gestor->getProductoPorCodigo($codigo);

if (!$producto) {
    header("Location: gestion_productos.php?mensaje=Producto no encontrado");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($gestor->bajaProducto($codigo)) {
        header("Location: gestion_productos.php?mensaje=Producto dado de baja con éxito");
    } else {
        header("Location: gestion_productos.php?mensaje=Error al dar de baja el producto");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Baja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="../estilos/style1.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <?php include '../plantillas/header.php' ?>
    <?php
        include '../plantillas/menuAdmin.php';
    
        include '../plantillas/menuEditor.php';
    ?>
    <div class="container-fluid mt-4 flex-grow-1">
        <h2 class="text-center">Confirmar Baja</h2>
        <p class="text-center">¿Estás seguro de que deseas dar de baja el producto <strong><?php echo htmlspecialchars($producto['codigo']); ?></strong>?</p>

        <form method="POST" class="text-center">
            <button type="submit" class="btn btn-danger">Sí, dar de baja</button>
            <a href="gestion_productos.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <?php include '../plantillas/footer.php' ?>
</body>

</html>