<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
}

require_once 'conexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    //CONSULTA TABLA FUNCIONES
    $consulta_funcion = 'SELECT f.id,p.id as idpelicula, p.nombre,f.fechaInicio,f.fechaFin,f.lugar,f.direccion,f.cupos FROM funciones f INNER JOIN peliculas p ON f.idNombrePelicula = p.id WHERE f.id=?';
    $editar = $conexion->prepare($consulta_funcion);
    $editar->bindParam(1, $id);
    $res = $editar->execute();

    if ($res) {
        $resultado_funcion = $editar->fetch();
    }

    //CONSULTA EN TABLA BOLETAS
    $consulta_boletas = 'SELECT sum(nboletas) as nboletas FROM boletas WHERE idFuncion = ?';

    $boletas = $conexion->prepare($consulta_boletas);
    $boletas->bindParam(1, $id);
    $resul = $boletas->execute();
    $resboletas = $boletas->fetch();
}

if (isset($_POST['boleta'])) {


    $nboletas = $_POST['nboletas'];
    $idFuncion = $_POST['idfuncion'];
    $cuposactuales = $_POST['cuposactuales'];
    $idUsuario = $_SESSION['usuario'];



    if (($cuposactuales - $nboletas) < 0) {
        $mensaje = 'El Numero de Boletas Requeridas Supera el Limite de Cupos';
        $color_mensaje = 'danger';
    } else {


        $consulta_insertar = 'INSERT INTO boletas(idUsuario,idFuncion,nboletas) VALUES(?,?,?)';

        $ejecucion_insertar = $conexion->prepare($consulta_insertar);
        $ejecucion_insertar->bindParam(1, $idUsuario);
        $ejecucion_insertar->bindParam(2, $idFuncion);
        $ejecucion_insertar->bindParam(3, $nboletas);
        $res = $ejecucion_insertar->execute();

        $ejecucion_insertar = null;
        $conexion = null;

        header('Location: index.php');
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CINECUC - Login</title>

    <?php require_once 'partials/enlaces.php'; ?>

</head>

<body>
    <?php require_once 'partials/navbar.php'; ?>
    <div class="container">
        <form class="validacion " novalidate action="boletas.php" method="POST">

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <?php if (!empty($mensaje)) : ?>
                        <div class="alert alert-dismissible alert-<?= $color_mensaje ?>">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <?= $mensaje ?>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($resultado_funcion['id'])) : ?>
                    <h2 class="text-uppercase mb-3">Reservar Boletas</h2>
                    
                        <div class="pb-3">
                            <h6>Informacion de Funcion: </h6>
                            <p class="font-weight-bold">Nombre de Pelicula: <span class="font-weight-normal"><?= $resultado_funcion['nombre'] ?></span></p>
                            <p class="font-weight-bold">Fecha de Inicio: <span class="font-weight-normal"><?= $resultado_funcion['fechaInicio'] ?></span></p>
                            <p class="font-weight-bold">Fecha de Fin: <span class="font-weight-normal"><?= $resultado_funcion['fechaFin'] ?></span></p>
                            <p class="font-weight-bold">Lugar: <span class="font-weight-normal"><?= $resultado_funcion['lugar'] ?></span></p>
                            <p class="font-weight-bold">Direccion: <span class="font-weight-normal"><?= $resultado_funcion['direccion'] ?></span></p>
                            <p class="font-weight-bold">Cupos: <span class="font-weight-normal"><?= $resultado_funcion['cupos'] - $resboletas['nboletas'] ?></span></p>
                        </div>


                        <input type="hidden" name="cuposactuales" value="<?= $resultado_funcion['cupos'] - $resboletas['nboletas'] ?>">
                        <input type="hidden" name="idfuncion" value="<?= $resultado_funcion['id'] ?>">
                        <div class="col-lg-12 mb-3">
                            <label for="nboletas">Numero de Boletas: </label>
                            <input type="number" class="form-control" name="nboletas" id="nboletas" required>
                            <div class="invalid-feedback">
                                Digite el Numero de Boletas .
                            </div>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <button class="btn btn-primary " name="boleta" type="submit">Reservar</button>
                        </div>
                    <?php endif ?>

                </div>
            </div>
        </form>
    </div>
    <?php require_once 'partials/validacion.php'; ?>

</body>

</html>
<?php
$editar = null;
$boletas = null;
$conexion = null;
?>