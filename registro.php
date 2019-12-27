<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['tipousuario'] == 2 ) {
    header('Location: login.php');
}

require_once 'conexion.php';

if(isset($_POST['crear'])){

    $nombre = $_POST['nombre'];
    $fechaInicio = $_POST['fechaInicio'];
    $fechaFin = $_POST['fechaFin'];
    $lugar = $_POST['lugar'];
    $direccion = $_POST['direccion'];
    $cupos = $_POST['cupos'];

    $consulta_insertar = 'INSERT INTO funciones(idNombrePelicula,fechaInicio,fechaFin,lugar,direccion,cupos) VALUES(?,?,?,?,?,?)';

    $ejecucion_insertar = $conexion->prepare($consulta_insertar);
    $ejecucion_insertar->bindParam(1, $nombre);
    $ejecucion_insertar->bindParam(2, $fechaInicio);
    $ejecucion_insertar->bindParam(3, $fechaFin);
    $ejecucion_insertar->bindParam(4, $lugar);
    $ejecucion_insertar->bindParam(5, $direccion);
    $ejecucion_insertar->bindParam(6, $cupos);
    $res = $ejecucion_insertar->execute();

    $ejecucion_insertar = null;
    $conexion = null;

    header('Location: index.php');




    
}