<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
}

$idFuncion = '';
echo $idFuncion;

require_once 'conexion.php';

//CONSULTA EN TABLA PELICULAS
$consulta = 'SELECT * FROM peliculas';

$ejecucion = $conexion->prepare($consulta);
$res = $ejecucion->execute();
$resultado_peliculas = $ejecucion->fetchAll();

//CONSULTA EN TABLA FUNCIONES
$consulta_funciones = 'SELECT f.id, p.nombre,f.fechaInicio,f.fechaFin,f.lugar,f.direccion,f.cupos FROM funciones f INNER JOIN peliculas p ON f.idNombrePelicula = p.id ';

$ejecucion_funciones = $conexion->prepare($consulta_funciones);
$res2 = $ejecucion_funciones->execute();
$resultado_funciones = $ejecucion_funciones->fetchAll();


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

    <div class="container-fluid mb-5">
        <div class="table-responsive ">
            <h2 class="text-uppercase mb-3">Funciones</h2>
            <table class="table">
                <thead class="bg-primary text-white">
                    <tr>
                        <th scope="col">Nombre Pelicula</th>
                        <th scope="col">Fecha Inicio</th>
                        <th scope="col">Fecha Fin</th>
                        <th scope="col">Lugar</th>
                        <th scope="col">Direccion</th>
                        <th scope="col">Cupos</th>
                        <?php if ($_SESSION['tipousuario'] == 1) : ?>
                        <th scope="col">N°Boletas Vendidas</th>
                            <th scope="col" colspan="3" class="text-center">Operaciones</th>
                        <?php else : ?>
                            <th scope="col" class="text-center">Operaciones</th>
                        <?php endif ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultado_funciones == null) : ?>
                        <td colspan="7" class="text-center">No hay Funciones en la Base de Datos</td>
                    <?php else : ?>
                        <?php foreach ($resultado_funciones as $funcion) : ?>
                            <tr>
                                <?php
                                //CONSULTA EN TABLA BOLETAS
                                $consulta_boletas = 'SELECT sum(nboletas) as nboletas FROM boletas WHERE idFuncion = ?';

                                $boletas = $conexion->prepare($consulta_boletas);
                                $boletas->bindParam(1, $funcion['id']);
                                $resul = $boletas->execute();
                                $resboletas = $boletas->fetch();
                                ?>
                                <th scope="row"><?= $funcion['nombre'] ?></th>
                                <td><?= $funcion['fechaInicio'] ?></td>
                                <td><?= $funcion['fechaFin'] ?></td>
                                <td><?= $funcion['lugar'] ?></td>
                                <td><?= $funcion['direccion'] ?></td>
                                <td class="font-weight-bold"><?= $funcion['cupos'] - $resboletas['nboletas']; ?></td>
                                <?php if ($_SESSION['tipousuario'] == 1) : ?>
                                <td class="font-weight-bold"><?php if($resboletas['nboletas'] == ''){echo '0';}else echo $resboletas['nboletas']; ?></td>
                                <?php endif ?>
                                <td><a class="btn btn-warning" <?php if( ($funcion['cupos'] - $resboletas['nboletas']) == 0){echo 'disabled';} ?>  href="boletas.php?id=<?= $funcion['id']; ?>">Boleta</a></td>
                                <?php if ($_SESSION['tipousuario'] == 1) : ?>
                                    <td><a class="btn btn-info" href="editar_funcion.php?id=<?= $funcion['id']; ?>">Editar</a></td>
                                    <td><a class="btn btn-danger" href="eliminar_funcion.php?id=<?= $funcion['id']; ?>">Cancelar</a></td>
                                <?php endif ?>
                            </tr>
                        <?php endforeach ?>

                    <?php endif ?>
                </tbody>
            </table>
        </div>

        <?php if ($_SESSION['tipousuario'] == 1) : ?>
            <h2 class="text-uppercase mb-3 mt-5">Crear Funcion</h2>
            <form class="validacion " novalidate action="registro.php" method="POST">
                <div class="form-row">
                    <div class="col-lg-6 mb-3">
                        <label for="validationCustom02">Nombre Pelicula: </label>
                        <select class="form-control" name="nombre" id="nombre" required>
                            <option selected="true" disabled="disabled">PELICULA</option>
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
                        <input type="datetime-local" class="form-control" name="fechaInicio" required>
                        <div class="invalid-feedback">
                            Digite una Fecha Inicio.
                        </div>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label for="validationCustom02">Fecha Fin: </label>
                        <input type="datetime-local" class="form-control" name="fechaFin" required>
                        <div class="invalid-feedback">
                            Digite una Fecha Fin.
                        </div>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label for="validationCustom02">Lugar: </label>
                        <input type="text" class="form-control" name="lugar" required>
                        <div class="invalid-feedback">
                            Digite un Lugar.
                        </div>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label for="validationCustom02">Direccion: </label>
                        <input type="text" class="form-control" name="direccion" required>
                        <div class="invalid-feedback">
                            Digite una Direccion.
                        </div>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label for="validationCustom02">Cupos: </label>
                        <input type="number" class="form-control" name="cupos" min="1" required>
                        <div class="invalid-feedback">
                            Digite los Cupos.
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary" name="crear" type="submit">Crear Funcion</button>
            </form>

        <?php endif ?>
    </div>


    <?php require_once 'partials/validacion.php'; ?>

</body>

</html>
<?php
 $ejecucion = null;
 $ejecucion_funciones = null;
 $boletas = null;
 $conexion = null;
?>