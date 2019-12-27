<?php   

session_start();

if (isset($_SESSION['usuario'])) {
    header('Location: index.php');
}
    require_once 'conexion.php';

    if (isset($_POST['registrar'])) {
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $pass = $_POST['pass'];
        $confirmacion = $_POST['confirmacion'];
        $tipo = 2;

        
        //CONSULTA EN TABLA USUARIOS PARA VALIDAR DUPLICADOS
        $consulta = 'SELECT * FROM usuarios WHERE correo = ?';

        $ejecucion = $conexion->prepare($consulta);
        $ejecucion->bindParam(1, $correo);
        $res = $ejecucion->execute();

        //CONFIRMACION DE CONTRASEÑA
        if($pass != $confirmacion){
            $mensaje = 'Las Contraseñas no coinciden';
            $color_mensaje = 'danger';
        }
        //VALIDACION DE DUPLICADOS
        else if($res){
            $resultado = $ejecucion->fetch();
            //print_r($resultado);
            if(isset($resultado['id'])){
                $mensaje = 'Usuario Ya Registrado';
                $color_mensaje = 'danger';
            }else{
                $pass_encrypt = password_hash($pass, PASSWORD_BCRYPT);
                $consulta_insertar = 'INSERT INTO usuarios(nombre,correo,password,idTipo) VALUES(?,?,?,?)';

                $ejecucion_insertar = $conexion->prepare($consulta_insertar);
                $ejecucion_insertar->bindParam(1, $nombre);
                $ejecucion_insertar->bindParam(2, $correo);
                $ejecucion_insertar->bindParam(3, $pass_encrypt);
                $ejecucion_insertar->bindParam(4, $tipo);
                $res = $ejecucion_insertar->execute();

                $ejecucion_insertar = null;
                $conexion = null;

                if($res){
                    $mensaje = 'Usuario Creado Correctamente';
                    $color_mensaje = 'success';

                }else{
                    $mensaje = 'Error al crear usuario';
                    $color_mensaje = 'danger';
                }
            }
        }
        
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CINECUC - Registro</title>

    <?php require_once 'partials/enlaces.php'; ?>

</head>

<body>
    <?php require_once 'partials/navbar.php'; ?>
    <div class="container">

        <form class="validacion " novalidate action="registrarse.php" method="POST">

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h2 class="text-uppercase mb-3">Registro</h2>

                    <?php if (!empty($mensaje)) : ?>
                        <div class="alert alert-dismissible alert-<?= $color_mensaje ?>">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <?= $mensaje ?>
                        </div>
                    <?php endif; ?><div class="col-lg-12 mb-3">
                        <label for="nombre">Nombre: </label>
                        <input type="text" class="form-control" name="nombre" id="nombre" required>
                        <div class="invalid-feedback">
                            Digite su Nombre.
                        </div>
                    </div>

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
                        <label for="confirmacion">Confirmar Contraseña: </label>
                        <input type="password" class="form-control" name="confirmacion" id="confirmacion" required>
                        <div class="invalid-feedback">
                            Digite su Confirmacion de Contraseña.
                        </div>
                    </div>

                    <div class="col-lg-12 mb-3">
                        <button class="btn btn-primary " name="registrar" type="submit">Registrar</button>
                        <p class="float-right py-3">¿Ya Registrado? <a href="login.php">Inicia Sesion</a></p>
                    </div>

                </div>
            </div>
        </form>
    </div>
    <?php require_once 'partials/validacion.php';?>

</body>

</html>
<?php
$ejecucion = null;
$conexion = null;
?>