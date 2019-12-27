<?php
session_start();

if (isset($_SESSION['usuario'])) {
    header('Location: index.php');
}

require_once 'conexion.php';

if (isset($_POST['ingresar'])) {
    $correo = $_POST['correo'];
    $pass = $_POST['pass'];


    //CONSULTA EN TABLA USUARIOS
    $consulta = 'SELECT * FROM usuarios WHERE correo = ?';

    $ejecucion = $conexion->prepare($consulta);
    $ejecucion->bindParam(1, $correo);
    $res =$ejecucion->execute();

    //echo password_hash($_POST['pass'], PASSWORD_BCRYPT);

    if($res){
        $resultado = $ejecucion->fetch();
    
        if (isset($resultado['id']) && password_verify($pass, $resultado['password'])) {
            $_SESSION['usuario'] = $resultado['id'];
            $_SESSION['tipousuario'] = $resultado['idTipo'];
            //$mensaje = 'inicio exitoso';
            //$color_mensaje = 'success';
    
            header("Location: index.php");
        } else {
            $mensaje = 'Credenciales incorrectas';
            $color_mensaje = 'danger';
        }

    }



    //print_r($_POST);

    //echo 'RESILTADOS <br>';
    //print_r($resultado);
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

        <form class="validacion " novalidate action="login.php" method="POST">

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h2 class="text-uppercase mb-3">Iniciar Sesion</h2>

                    <?php if (!empty($mensaje)) : ?>
                        <div class="alert alert-dismissible alert-<?= $color_mensaje ?>">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <?= $mensaje ?>
                        </div>
                    <?php endif; ?>
                    <div class="col-lg-12 mb-3">
                        <label for="correo">Correo: </label>
                        <input type="email" class="form-control" name="correo" id="correo" required>
                        <div class="invalid-feedback">
                            Digite su Correo.
                        </div>
                    </div>

                    <div class="col-lg-12 mb-3">
                        <label for="pass">Contraseña: </label>
                        <input type="password" class="form-control" name="pass" id="pass" required>
                        <div class="invalid-feedback">
                            Digite su Contraseña.
                        </div>
                    </div>

                    <div class="col-lg-12 mb-3">
                        <button class="btn btn-primary " name="ingresar" type="submit">Ingresar</button>
                        <p class="float-right py-3">¿Nuevo aqui? <a href="registrarse.php">Registrate</a></p>
                    </div>

                </div>
            </div>
        </form>
    </div>
    <?php require_once 'partials/validacion.php';?>

</body>

</html>
<?php
 $eliminar = null;
 $conexion = null;
?>