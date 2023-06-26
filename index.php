<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
    exit;
}

require 'controller/usuarios.php';
require 'controller/sesion.php';
require_once "flight/autoload.php";
require_once "vendor/autoload.php";

//use vendor\firebase\JWT\JWT;
use Firebase\JWT\JWT;

Flight::route('/SALUDAR', function () {
    echo 'hello world!';
});
//todos los ususarios
Flight::route('GET /usuarios', function () {
    $usuarios = getUsuarios();
    Flight::json($usuarios);
});




//iniciar sesion
Flight::route('POST /iniciar-sesion', function () {
    $usuario = (Flight::request()->data->usuario);
    $contrasena = (Flight::request()->data->contrasena);
    $token = validarCredenciales($usuario, $contrasena);
    //echo json_encode($token);
    //Flight::jsonp(["login",$usuario]);
});

//usuario por id + detlles
Flight::route('POST /usuario-detalles', function () {
    $idUsuario = Flight::request()->data->idUsuario;
    $usuario = getDetallesUsuarioPorId($idUsuario);
    Flight::json($usuario);
});

//crear usuario
Flight::route('POST /alta-usuario', function () {
    $datosNuevos = (Flight::request()->data);
    //Flight::jsonp($datosNuevos);
    $usuarioactualizado = crearUsuario($datosNuevos);
});

//usuario por id(para antes del actualizar)
Flight::route('POST /usuario-id', function () {
    $idUsuario = Flight::request()->data->idUsuario;
    $usuario = getUsuarioPorId($idUsuario);
    Flight::json($usuario);
});

  //actualizar usuario
Flight::route('PUT /usuario-id-actualizar', function () {
    $datosNuevos = (Flight::request()->data);
    //Flight::jsonp($datosNuevos);
    $usuarioactualizado = actualizarUsuario($datosNuevos);
    
});

// Flight::route('PUT /alta-usuario', function () {
//     $usuario = (Flight::request()->data->usuario);
//     $idUsuario = (Flight::request()->data->idUsuario);
//     print_r($usuario);
//     print_r($idUsuario);
//     Flight::jsonp(["usuario actualizado"]);
// });

Flight::start();
