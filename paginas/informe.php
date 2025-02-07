<?php
session_start();

require_once '../servidor/seguridadAdmin.php';
include '../servidor/config.php';
$db = conectar();


$informe = $_GET["informe"];
$tipo = $_GET["tipo"];

if ($tipo == 1 || $tipo == 2) {//Usuarios
    include '../gestores/GestorUsuarios.php';
    $gestor = new GestorUsuarios($db);
    $activo = $tipo == 1 ? 1 : 0;
    $resultado = $gestor->obtener_usuarios_estado($activo);
} else if ($tipo == 3 || $tipo == 4) {
    include '../gestores/GestorProductos.php';
    $gestor = new GestorProductos($db);
    $activo = $tipo == 3 ? 1 : 0;
    $resultado = $gestor->obtenerProductosEstado($activo);
} else {
    include '../gestores/GestorPedidos.php';
    $gestor = new GestorPedidos($db);
    $estado = $_GET["estado"] ?? null;
    $orden = $_GET["orden"] ?? null;
    if ($tipo == 5) {
        $resultado = $gestor->listarPedidosEstado($estado);
    } else {
        $resultado = $gestor->obtenerPedidosFecha($orden);

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php include_once '../plantillas/header.php' ?>
    <?php include '../plantillas/menuAdmin.php'; ?>


    <div class="container-fluid mt-4 flex-grow-1">
        <div class="row ">
            <div class="col-md-2">
                <?php include '../plantillas/menu.php'; ?>
            </div>

            <div class="col-md-8 text-center">
                <h1 class="mb-4"><?= $informe ?></h1>

                <div class="row">

                    <?php if ($tipo == 1 || $tipo == 2) { ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Dni</th>
                                    <th>Nombre</th>
                                    <th>Apellidos</th>
                                    <th>Telefono</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($resultado as $item) { ?>
                                    <tr>
                                        <td><?= $item["dni"] ?></td>
                                        <td><?= $item["nombre"] ?></td>
                                        <td><?= $item["apellidos"] ?></td>
                                        <td><?= $item["telefono"] ?></td>
                                        <td><?= $item["email"] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } else if ($tipo == 3 || $tipo == 4) { ?>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Categoría</th>
                                        <th>Precio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($resultado as $item) { ?>
                                        <tr>
                                            <td><?= $item["codigo"] ?></td>
                                            <td><?= $item["nombre"] ?></td>
                                            <td><?= $item["categoria"] ?></td>
                                            <td><?= $item["precio"] ?>€</td>
                                        </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                    <?php } else { ?>
                            <div class="row">
                            <?php if ($tipo == 5) { ?>
                                    <div class="col">
                                        <a href="?informe=Estados de pedidos&tipo=5&estado=Proceso">Proceso</a>
                                    </div>
                                    <div class="col">
                                        <a href="?informe=Estados de pedidos&tipo=5&estado=Preparando">Preparando</a>
                                    </div>
                                    <div class="col">
                                        <a href="?informe=Estados de pedidos&tipo=5&estado=Enviado">Enviado</a>
                                    </div>
                                    <div class="col">
                                        <a href="?informe=Estados de pedidos&tipo=5&estado=Entregado">Entregado</a>
                                    </div>
                                    <div class="col">
                                        <a href="?informe=Estados de pedidos&tipo=5&estado=Cancelado">Cancelado</a>
                                    </div>
                            <?php } else { ?>
                                    <div class="col">
                                        <a href="?informe=Estados de pedidos&tipo=6&orden=ASC">Más viejos</a>

                                    </div>
                                    <div class="col">
                                        <a href="?informe=Estados de pedidos&tipo=6&orden=DESC">Más nuevos</a>

                                    </div>
                            <?php } ?>

                            </div>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Id pedido</th>
                                        <th>Fecha</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($resultado as $item) { ?>
                                        <tr>
                                            <td><?= $item["idPedido"] ?></td>
                                            <td><?= $item["fecha"] ?></td>
                                            <td><?= $item["total"] ?>€</td>
                                            <td><?= $item["estado"] ?></td>
                                        </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                    <?php } ?>

                </div>


            </div>
        </div>
    </div>
    <?php include_once '../plantillas/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>