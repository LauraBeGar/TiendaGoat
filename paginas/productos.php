<?php
session_start();

require_once '../servidor/config.php';
require_once('../gestores/Producto.php');
include_once '../servidor/mensajes.php';
require_once('../gestores/GestorProductos.php');
require_once('../gestores/GestorCategoria.php');

$catProds = isset($_GET['catProds']) ? $_GET['catProds'] : null;

$db = conectar();
$gestor = new GestorProductos($db);
if (isset($_GET['buscar'])) {
    $productosCat = $gestor->buscarProductoEnCategoria($_GET['buscar'], $catProds);
    $totalProductos = count($productosCat);
} else {
    $orden = $_GET["orden"] ?? "";
    $productosCat = $gestor->getProductosPorCategoria($catProds, $orden);
}


$gestorCategoria = new GestorCategorias($db);

$categoriaNombre = $gestorCategoria->getCategoriaNombre($catProds);
// Número de artículos por página
$productosPorPagina = 3;

// Obtener número de página actual desde URL
$pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
if ($pagina < 1)
    $pagina = 1; // Evitar valores negativos

// Calcular el índice de inicio para la consulta
$inicio = ($pagina - 1) * $productosPorPagina;
if (!isset($_GET['buscar'])) {
    // Obtener artículos paginados
    $productos = $gestor->getProductosPagActivos($inicio, $productosPorPagina, $orden);

    // Obtener total de artículos
    $totalProductos = count($gestor->getProductosPorCategoria($catProds, $orden));
    $totalPaginas = ceil($totalProductos / $productosPorPagina);

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda GOAT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="../estilos/style1.css">
</head>

<body>
    <?php include '../plantillas/header.php'; ?>
    <div class="container-fluid mt-4 min-height">
        <div class="row">

            <div class="col-md-2">
                <?php include '../plantillas/menu.php'; ?>
            </div>
            <?php mostrarMensaje() ?>
            <div class="col-md-8">
                <div class="row g-4">
                    <h3>
                        <?=
                            $categoriaNombre['nombre'];
                        ?>
                    </h3>
                </div>

                <div class="row justify-content-start mt-4 mb-5">
                    <div class="col-md-3">
                        <a href="?orden=ASC&catProds=<?= $catProds ?>" class="btn btn-outline-warning text-dark">Filtrar
                            por precio menor</a>
                    </div>
                    <div class="col-md-3">
                        <a href="?orden=DESC&catProds=<?= $catProds ?>"
                            class="btn btn-outline-warning text-dark">Filtrar por precio mayor</a>

                    </div>
                </div>
                <div class="row g-4">

                    <?php
                    if ($totalProductos > 0) {
                        foreach ($productosCat as $producto): ?>
                            <div class="col-md-4 d-flex justify-content-center">
                                <div class="card" style="width: 17rem;">
                                    <!-- Contenido de la tarjeta del producto -->
                                    <?php if (!empty($producto["imagen"])): ?>
                                        <img src="/img/<?= $producto["imagen"] ?>" class="card-img-top"
                                            alt="<?= $producto["nombre"] ?>"
                                            style="width: 50%; height: 150px; object-fit: cover; display: block; margin: auto;">
                                    <?php else: ?>
                                        <div class="p-3 text-center">No hay imagen disponible</div>
                                    <?php endif; ?>
                                    <div class="card-body text-center">
                                        <h5 class="card-title"><?= $producto["nombre"] ?></h5>
                                        <p class="card-text"><?= $producto["descripcion"] ?> </p>
                                        <p class="card-text fw-bold"><?= $producto["precio"] ?> €</p>
                                        <a href="detalle_producto.php?codigo=<?= $producto["codigo"] ?>"
                                            class="btn btn-outline-warning text-dark mb-4">Ver Detalles</a>
                                        <a href="/servidor/c_carrito.php?codigo=<?= $producto["codigo"] ?>&nombre=<?= $producto["nombre"] ?>&imagen=<?= $producto["imagen"] ?>&precio=<?= $producto["precio"] ?>&categoria=<?= $producto["categoria"] ?>"
                                            class="btn btn-outline-warning text-dark">Añadir al carrito</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach;
                    } else { ?>
                        <p>Producto no disponible en esta categoria</p>
                    <?php } ?>
                </div>
            </div>

            <?php include '../plantillas/menuUsuario.php'; ?>

        </div>
    </div>
    <?php if (!isset($_GET['buscar'])) { ?>
        <nav>
            <ul class="pagination justify-content-center mt-4">
                <?php if ($pagina > 1): ?>
                    <li class="page-item">
                        <a class="page-link border border-warning text-dark"
                            href="?pagina=<?= $pagina - 1 ?>&catProds=<?= $catProds ?>">Anterior</a>
                    </li>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <li class="page-item">
                        <a class="page-link border border-warning text-dark <?= $pagina == $i ? 'active bg-warning text-dark' : '' ?>"
                            href="?pagina=<?= $i ?>&catProds=<?= $catProds ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <?php if ($pagina < $totalPaginas): ?>
                    <li class="page-item">
                        <a class="page-link border border-warning text-dark"
                            href="?pagina=<?= $pagina + 1 ?>&catProds=<?= $catProds ?>">Siguiente</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php } ?>
    <?php include '../plantillas/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>


</html>