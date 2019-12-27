<?php
    $host = 'mysql:host=localhost;dbname=cinecuc';
    $usuario = 'root';
    $pass = '';


    try {
        $conexion = new PDO($host, $usuario, $pass);
        //echo 'BD CONECTADA';
       // $conexion = null;
    } catch (PDOException $e) {
        print "¡Error!: " . $e->getMessage() . "<br/>";
        die();
    }