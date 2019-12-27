<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['tipousuario'] == 2 ) {
    header('Location: login.php');
}

require_once 'conexion.php';

if (isset($_GET['id'])) {

    $id = $_GET['id'];
    $consulta_eliminar = "DELETE FROM peliculas WHERE id = ?";
    $eliminar = $conexion->prepare($consulta_eliminar);
    $eliminar->bindParam(1, $id);
    $res = $eliminar->execute();


    $eliminar = null;
    $conexion = null;
    
    if ($res) {
        $mensaje = 'Pelicula Eliminada Correctamente';
        $color_mensaje = 'success';
        //header('Location: peliculas.php');
    } else {
        $mensaje = 'Error al eliminar Pelicula';
        $color_mensaje = 'danger';
    }
    header('Location: peliculas.php');
}
