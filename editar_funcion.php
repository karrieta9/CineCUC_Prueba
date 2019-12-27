<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['tipousuario'] == 2 ) {
    header('Location: index.php');
}

require_once 'conexion.php';

//CONSULTA EN TABLA PELICULAS
$consulta = 'SELECT * FROM peliculas';

$ejecucion = $conexion->prepare($consulta);
$res = $ejecucion->execute();
$resultado_peliculas = $ejecucion->fetchAll();

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $consulta_funcion = 'SELECT f.id,p.id as idpelicula, p.nombre,f.fechaInicio,f.fechaFin,f.lugar,f.direccion,f.cupos FROM funciones f INNER JOIN peliculas p ON f.idNombrePelicula = p.id WHERE f.id=?';
    $editar = $conexion->prepare($consulta_funcion);
    $editar->bindParam(1, $id);
    $res = $editar->execute();

    if($res){
        $resultado_funcion = $editar->fetch();
    }
}


if (isset($_POST['editar'])) {
    $id = $_POST['idFun'];
    $nombre = $_POST['nombre'];
    $fechaInicio = $_POST['fechaInicio'];
    $fechaFin = $_POST['fechaFin'];
    $lugar = $_POST['lugar'];
    $direccion = $_POST['direccion'];
    $cupos = $_POST['cupos'];


    $consulta_editar = 'UPDATE funciones SET idNombrePelicula=?, fechaInicio=?, fechaFin=?, lugar=?, direccion=?,cupos=? WHERE id=?';

    $ejecucion = $conexion->prepare($consulta_editar);
    $ejecucion->bindParam(1, $nombre);
    $ejecucion->bindParam(2, $fechaInicio);
    $ejecucion->bindParam(3, $fechaFin);
    $ejecucion->bindParam(4, $lugar);
    $ejecucion->bindParam(5, $direccion);
    $ejecucion->bindParam(6, $cupos);
    $ejecucion->bindParam(7, $id);
    $res = $ejecucion->execute();

    //print_r($res);

    $ejecucion = null;
    $conexion = null;

    if ($res) {
        //$mensaje = 'Pelicula Creada Correctamente';
        //$color_mensaje = 'success';
        header('Location: index.php');
    } else {
        $mensaje = 'Error al editar Funcion';
        $color_mensaje = 'danger';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CINE CUC</title>

    <?php require_once 'partials/enlaces.php'; ?>
</head>

<body>
    <?php require_once 'partials/navbar.php'; ?>

    <div class="container">
        <h2 class="text-uppercase mb-3">Editar Funcion</h2>
        <form class="validacion " novalidate action="editar_funcion.php" method="POST">
            <div class="form-row">
                <input type="hidden" name="idFun" value="<?= $resultado_funcion['id']; ?>">
                <div class="col-lg-6 mb-3">
                    <label for="validationCustom02">Nombre Pelicula: </label>
                    <select class="form-control" name="nombre" id="nombre" required>
                        <option selected="true" disabled="disabled" value="<?= $resultado_funcion['idpelicula']; ?>">PELICULA</option>
                        <?php foreach ($resultado_peliculas as $peliculas) : ?>
                            <option value="<?= $peliculas['id']; ?>"><?= $peliculas['nombre']; ?></option>
                        <?php endforeach ?>
                    </select>
                    <div class="invalid-feedback">
                        Seleccione un Nombre Pelicula.
                    </div>
                </div>

                <div class="col-lg-6 mb-3">
                    <label for="validationCustom02">Fecha Inicio: </label>
                    <input type="datetime-local" class="form-control" name="fechaInicio" value="<?= str_replace(" ", "T", $resultado_funcion['fechaInicio']);  ?>" required>
                    <div class="invalid-feedback">
                        Digite una Fecha Inicio.
                    </div>
                </div>

                <div class="col-lg-6 mb-3">
                    <label for="validationCustom02">Fecha Fin: </label>
                    <input type="datetime-local" class="form-control" name="fechaFin" value="<?= str_replace(" ", "T", $resultado_funcion['fechaFin']);  ?>" required>
                    <div class="invalid-feedback">
                        Digite una Fecha Fin.
                    </div>
                </div>

                <div class="col-lg-6 mb-3">
                    <label for="validationCustom02">Lugar: </label>
                    <input type="text" class="form-control" name="lugar" value="<?= $resultado_funcion['lugar']; ?>" required>
                    <div class="invalid-feedback">
                        Digite un Lugar.
                    </div>
                </div>

                <div class="col-lg-6 mb-3">
                    <label for="validationCustom02">Direccion: </label>
                    <input type="text" class="form-control" name="direccion" value="<?= $resultado_funcion['direccion']; ?>"
                    required>
                    <div class="invalid-feedback">
                        Digite una Direccion.
                    </div>
                </div>

                <div class="col-lg-6 mb-3">
                    <label for="validationCustom02">Cupos: </label>
                    <input type="number" class="form-control" name="cupos" value="<?= $resultado_funcion['cupos']; ?>"
                    required>
                    <div class="invalid-feedback">
                        Digite los Cupos.
                    </div>
                </div>
            </div>
            <button class="btn btn-primary" name="editar" type="submit">Editar Funcion</button>
        </form>

        <?php require_once 'partials/validacion.php'; ?>
    </div>
</body>

</html>
<?php
$ejecucion = null;
$conexion = null;
?>