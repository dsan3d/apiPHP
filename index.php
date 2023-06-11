<?php

require 'flight/Flight.php';
/* Esta línea es para configurar el acceso a la base de datos mysql que estemos usando
 los parámetros dentro de array son la cadena de conexión típica(direcciónIP,nombre de base de datos, usuario y contraseña) */
Flight::register('db', 'PDO', array('mysql:host=localhost;dbname=biblioteca','root',''));


// leer Datos y los muestra a quien los solicite
Flight::route('/personas', function () {
    $sentencia = Flight::db()->prepare("Select * from personas");
    $sentencia->execute();
    $datos = $sentencia->fetchAll();
    Flight::json($datos);    
    
});

// leer UN REGISTRO CONCRETO y los muestra a quien los solicite
Flight::route('GET /personas/@identificador', function ($identificador) {
    
    $sentencia = Flight::db()->prepare("Select * from personas wHERE id=?");
    $sentencia->bindParam(1,$identificador);
    $sentencia->execute();
    $datos = $sentencia->fetchAll();
    Flight::json($datos);    
    
});

// Hacer un INSERT en la base de datos por el método HTTP POST
Flight::route('POST /personas', function () {
    $nombrenuevo =(Flight::request()->data->name);
    $paisnuevo =(Flight::request()->data->country);
    $sql ="insert into personas (name, country) values(?,?)";
    $sentencia =Flight::db()->prepare($sql);
    $sentencia->bindParam(1,$nombrenuevo);
    $sentencia->bindParam(2,$paisnuevo);
    $sentencia->execute();
    Flight::jsonp(['Persona agregada']);
    
});

// Hacer un DELETE en la base de datos por el método HTTP DELETE
Flight::route('DELETE /personas', function () {
    $identificador =(Flight::request()->data->id);
    
    $sql ="delete from personas where id=?";
    $sentencia =Flight::db()->prepare($sql);
    $sentencia->bindParam(1,$identificador);    
    $sentencia->execute();
    Flight::jsonp(['Persona borrada']);
    
});

// Hacer un UPDATE en la base de datos por el método HTTP PUT
Flight::route('PUT /personas', function () {
    $identificador =(Flight::request()->data->id);
    $nombrenuevo =(Flight::request()->data->name);
    $paisnuevo =(Flight::request()->data->country);
    $sql ="UPDATE personas SET name=?, country=? WHERE id=?";
    $sentencia =Flight::db()->prepare($sql);
    $sentencia->bindParam(1,$nombrenuevo);
    $sentencia->bindParam(2,$paisnuevo);
    $sentencia->bindParam(3,$identificador);
    $sentencia->execute();
    Flight::jsonp(['Persona modificada']);
    
});

Flight::start();
