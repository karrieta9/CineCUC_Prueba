<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['tipousuario'] == 2 ) {
    header('Location: login.php');
}

require_once 'conexion.php';

//CONSULTA EN TABLA PELICULAS
$consulta = 'SELECT * FROM peliculas';

$ejecucion = $conexion->prepare($consulta);
$res = $ejecucion->execute();
$resultado_peliculas = $ejecucion->fetchAll();

if (isset($_POST['guardar'])) {
    $nompelicula = $_POST['nombrepel'];
    $director = $_POST['director'];
    $genero = $_POST['genero'];
    $descripcion = $_POST['descripcion'];

    $consulta_peliculas = 'INSERT INTO peliculas(nombre,descripcion,director,genero) VALUES(?,?,?,?)';

    $ejecucion_peliculas = $conexion->prepare($consulta_peliculas);
    $ejecucion_peliculas->bindParam(1, $nompelicula);
    $ejecucion_peliculas->bindParam(2, $descripcion);
    $ejecucion_peliculas->bindParam(3, $director);
    $ejecucion_peliculas->bindParam(4, $genero);
    $res = $ejecucion_peliculas->execute();


    $ejecucion_peliculas = null;
    $conexion = null;

    if ($res) {
        //$mensaje = 'Pelicula Creada Correctamente';
        //$color_mensaje = 'success';
        header('Location: peliculas.php');
    } else {
        $mensaje = 'Error al crear Pelicula';
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
    <title>CINECUC - Login</title>

    <?php require_once 'partials/enlaces.php'; ?>

</head>

<body>
    <?php require_once 'partials/navbar.php'; ?>
    <div class="container">

        <form class="validacion " novalidate action="peliculas.php" method="POST">

            <div class="row">
                <div class="col-lg-5">
                    <h2 class="text-uppercase mb-3">Agregar Pelicula</h2>

                    <?php if (!empty($mensaje)) : ?>
                        <div class="alert alert-dismissible alert-<?= $color_mensaje ?>">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <?= $mensaje ?>
                        </div>
                    <?php endif; ?>
                    <div class="col-lg-12 mb-3">
                        <label for="nombrepel">Nombre Pelicula: </label>
                        <input type="text" class="form-control" name="nombrepel" id="nombrepel" required>
                        <div class="invalid-feedback">
                            Digite el Nombre Pelicula.
                        </div>
                    </div>

                    <div class="col-lg-12 mb-3">
                        <label for="director">Director: </label>
                        <input type="text" class="form-control" name="director" id="director" required>
                        <div class="invalid-feedback">
                            Digite el Director.
                        </div>
                    </div>

                    <div class="col-lg-12 mb-3">
                        <label for="genero">Genero: </label>
                        <input type="text" class="form-control" name="genero" id="genero" required>
                        <div class="invalid-feedback">
                            Digite el Genero.
                        </div>
                    </div>

                    <div class="col-lg-12 mb-3">
                        <label for="descripcion">Descripcion: </label>
                        <textarea class="form-control" name="descripcion" id="descripcion" rows="2" required></textarea>
                        <div class="invalid-feedback">
                            Digite el Descripcion.
                        </div>
                    </div>

                    <div class="col-lg-12 mb-3">
                        <button class="btn btn-primary " name="guardar" type="submit">Guardar</button>
                    </div>

                </div>
                <div class="col-lg-7">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Director</th>
                                    <th scope="col">Genero</th>
                                    <th scope="col">Descripcion</th>
                                    <th scope="col" colspan="2" class="text-center">Operaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($resultado_peliculas == null) : ?>
                                    <td colspan="6" class="text-center">No hay Peliculas en la Base de Datos</td>
                                <?php else : ?>
                                    <?php foreach ($resultado_peliculas as $pelicula) : ?>
                                        <tr>
                                            <th scope="row"><?= $pelicula['nombre'] ?></th>
                                            <td class="px-0"><?= $pelicula['director'] ?></td>
                                            <td class="px-0"><?= $pelicula['genero'] ?></td>
                                            <td class="px-0"><?= $pelicula['descripcion'] ?></td>
                                            <td class="px-0 pr-2"><a class="btn btn-info" href="editar_pelicula.php?id=<?=$pelicula['id']; ?>">Editar</a></td>
                                            <td class="px-0 pr-2"><a class="btn btn-danger" href="eliminar_pelicula.php?id=<?=$pelicula['id']; ?>">Eliminar</a></td>
                                        </tr>
                                    <?php endforeach ?>

                                <?php endif ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php require_once 'partials/validacion.php'; ?>

</body>

</html>
<?php
 $ejecucion = null;
 $conexion = null;
?>