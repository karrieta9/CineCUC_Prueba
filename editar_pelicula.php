<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['tipousuario'] == 2 ) {
    header('Location: login.php');
}

require_once 'conexion.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $consulta_editar = "SELECT * FROM peliculas WHERE id = ?";
    $editar = $conexion->prepare($consulta_editar);
    $editar->bindParam(1, $id);
    $res = $editar->execute();

    if($res){
        $resultado_pelicula = $editar->fetch();
        // $nompelicula = $resultado_pelicula['nombre'];
        // $director = $resultado_pelicula['nombre'];
        // $genero = $_POST['genero'];
        // $descripcion = $_POST['descripcion'];
    }
}


if (isset($_POST['editar'])) {
    $id = $_POST['idPel'];
    $nompelicula = $_POST['nombrepel'];
    $director = $_POST['director'];
    $genero = $_POST['genero'];
    $descripcion = $_POST['descripcion'];

    $consulta_editar = 'UPDATE peliculas SET nombre=?, descripcion=?, director=?, genero=? WHERE id=?';

    $ejecucion = $conexion->prepare($consulta_editar);
    $ejecucion->bindParam(1, $nompelicula);
    $ejecucion->bindParam(2, $descripcion);
    $ejecucion->bindParam(3, $director);
    $ejecucion->bindParam(4, $genero);
    $ejecucion->bindParam(5, $id);
    $res = $ejecucion->execute();

    //print_r($res);

    $ejecucion = null;
    $conexion = null;

    if ($res) {
        //$mensaje = 'Pelicula Creada Correctamente';
        //$color_mensaje = 'success';
        header('Location: peliculas.php');
    } else {
        $mensaje = 'Error al editar Pelicula';
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

        <form class="validacion " novalidate action="editar_pelicula.php" method="POST">

            <div class="row">
                <div class="col-lg-5">
                    <h2 class="text-uppercase mb-3">Editar Pelicula</h2>

                    <?php if (!empty($mensaje)) : ?>
                        <div class="alert alert-dismissible alert-<?= $color_mensaje ?>">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <?= $mensaje ?>
                        </div>
                    <?php endif; ?>
                    <input type="hidden" name="idPel" value="<?= $resultado_pelicula['id']; ?>">
                    <div class="col-lg-12 mb-3">
                        <label for="nombrepel">Nombre Pelicula: </label>
                        <input type="text" class="form-control" name="nombrepel" id="nombrepel" value="<?= $resultado_pelicula['nombre']; ?>" required>
                        <div class="invalid-feedback">
                            Digite el Nombre Pelicula.
                        </div>
                    </div>

                    <div class="col-lg-12 mb-3">
                        <label for="director">Director: </label>
                        <input type="text" class="form-control" name="director" id="director" value="<?= $resultado_pelicula['director']; ?>" required>
                        <div class="invalid-feedback">
                            Digite el Director.
                        </div>
                    </div>

                    <div class="col-lg-12 mb-3">
                        <label for="genero">Genero: </label>
                        <input type="text" class="form-control" name="genero" id="genero" value="<?= $resultado_pelicula['genero']; ?>" required>
                        <div class="invalid-feedback">
                            Digite el Genero.
                        </div>
                    </div>

                    <div class="col-lg-12 mb-3">
                        <label for="descripcion">Descripcion: </label>
                        <textarea class="form-control" name="descripcion" id="descripcion" rows="2"  required><?= $resultado_pelicula['descripcion']; ?></textarea>
                        <div class="invalid-feedback">
                            Digite el Descripcion.
                        </div>
                    </div>

                    <div class="col-lg-12 mb-3">
                        <button class="btn btn-primary " name="editar" type="submit">Editar</button>
                    </div>

                </div>
            </div>
        </form>
    </div>
    <?php require_once 'partials/validacion.php'; ?>

</body>

</html>
<?php
$editar = null;
$conexion = null;
?>