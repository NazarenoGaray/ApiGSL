<?php
require 'flight/Flight.php';


Flight::register('db', 'PDO', array('mysql:host=localhost;dbname=gslogistic','root',''));


// Función para obtener todos los usuarios
function getUsuarios(){
  $sentencia = Flight::db()->prepare("SELECT * FROM usuarios");
  $sentencia -> execute();
  $usuarios = $sentencia->fetchALL(PDO::FETCH_ASSOC);
  return $usuarios;
}

// Función para obtener un usuario por su ID
function getUsuarioPorId($idUsuario){
  $sentencia = Flight::db()->prepare("SELECT * FROM usuarios WHERE idUsuario = :idUsuario");
  $sentencia->bindParam(':idUsuario', $idUsuario);
  $sentencia->execute();
  $usuario = $sentencia->fetch(PDO::FETCH_ASSOC);
  return $usuario;
}

function getDetallesUsuarioPorId($idUsuario){
  $sentencia = Flight::db()->prepare(
    "SELECT u.*,
    r.rol rol,
    e.estado AS estado
    FROM usuarios AS u
    INNER JOIN estadoUsuarios AS e ON u.idEstadoUsuario = e.idEstadoUsuario
    INNER JOIN roles AS r ON u.idRol = r.idRol
    WHERE u.idUsuario = :idUsuario");
  $sentencia->bindParam(':idUsuario', $idUsuario);
  $sentencia->execute();
  $usuario = $sentencia->fetch(PDO::FETCH_ASSOC);
  return $usuario;
}

// Función para crear un nuevo usuario
function crearUsuario($datosUsuario){
  //Flight::db()->beginTransaction();

  $nombre= $datosUsuario['nombre'];
  $apellido= $datosUsuario['apellido'];
  $telefono= $datosUsuario['telefono'];
  $correo= $datosUsuario['correo'];
  $usuario= $datosUsuario['usuario'];
  $contrasena= $datosUsuario['contrasena'];
  $idRol= $datosUsuario['idRol'];
  $idEstadoUsuario= $datosUsuario['idEstadoUsuario'];

  $sentencia = Flight::db()->prepare
  ("INSERT INTO usuarios (nombre, apellido, telefono, correo, usuario, contrasena, idRol, idEstadoUsuario) 
  VALUES (
    :nombre,
    :apellido,
    :telefono,
    :correo,
    :usuario,
    :contrasena,
    :idRol,
    :idEstadoUsuario)");
    
    $sentencia->bindParam(':nombre', $nombre);
    $sentencia->bindParam(':apellido', $apellido);
    $sentencia->bindParam(':telefono', $telefono);
    $sentencia->bindParam(':correo', $correo);
    $sentencia->bindParam(':usuario', $usuario);
    $sentencia->bindParam(':contrasena', $contrasena);
    $sentencia->bindParam(':idRol',$idRol);
    $sentencia->bindParam(':idEstadoUsuario',$idEstadoUsuario);
    $result = $sentencia->execute();
    // verificar si se ejecutó la consulta correctamente
    //Flight::db()->commit();

    if (!$result) {
      error_log('Error al insertar usuario: ' . print_r($sentencia->errorInfo(), true));
      return false;
    }
    $resp =Flight::db()->lastInsertId();
    echo $resp;
}

function actualizarUsuario($datosUsuario) {

  $nombre= $datosUsuario['nombre'];
  $apellido= $datosUsuario['apellido'];
  $telefono= $datosUsuario['telefono'];
  $correo= $datosUsuario['correo'];
  $usuario= $datosUsuario['usuario'];
  $contrasena= $datosUsuario['contrasena'];
  $idRol= $datosUsuario['idRol'];
  $idEstadoUsuario= $datosUsuario['idEstadoUsuario'];
  $idUsuario= $datosUsuario['idUsuario'];
  //Actualizar el usuario en la base de datos
  $sentencia = Flight::db()->prepare(
    "UPDATE usuarios
    SET nombre = :nombre,
    apellido = :apellido,
    telefono = :telefono,
    correo = :correo,
    usuario = :usuario,
    contrasena = :contrasena,
    idRol = :idRol,
    idEstadoUsuario = :idEstadoUsuario
    WHERE idUsuario = :idUsuario");

  $sentencia->bindParam(':nombre', $nombre);
  $sentencia->bindParam(':apellido', $apellido);
  $sentencia->bindParam(':telefono', $telefono);
  $sentencia->bindParam(':correo', $correo);
  $sentencia->bindParam(':usuario', $usuario);
  $sentencia->bindParam(':contrasena', $contrasena);
  $sentencia->bindParam(':idRol',$idRol);
  $sentencia->bindParam(':idEstadoUsuario',$idEstadoUsuario);
  $sentencia->bindParam(':idUsuario', $idUsuario);
  $sentencia->execute();
  
  //Flight::jsonp(["usuario actualizado"]);
  //Flight::jsonp($datosUsuario['nombre']);
}




// function bajaUsuario($idUsuario)
// {
//   //$sentencia = Flight::db()->prepare("DELETE FROM usuarios WHERE idUsuario = ?");
//   //$sentencia->execute([$idUsuario]);
// }

// Obtiene la acción que se va a realizar (get, post, put o delete)
//$accion = $_SERVER['REQUEST_METHOD'];

// Obtiene el ID del usuario (si es necesario)
//$idUsuario = isset($_GET['idUsuario']) ? $_GET['idUsuario'] : null;

// Realiza la acción correspondiente

// switch ($accion) {
//   case 'GET':
//     global ;
    
//     if(isset($_GET['idUsuarioDetalle'])){
//       $idUsuarioDetalles = isset($_GET['idUsuarioDetalle']);
//       // Obtiene un usuario por su ID
//       $usuario = getDetallesUsuarioPorId($conexion, $idUsuarioDetalles);
//       echo json_encode($usuario);
//       exit;
//     }elseif(isset($_GET['idUsuario'])) {
//       $idUsuario = $_GET['idUsuario'];
//       $usuario = getUsuarioPorId($conexion, $idUsuario);
//     echo json_encode($usuario);
//     }else {
      
//       // Obtiene todos los usuarios
//       $usuarios = getUsuarios($conexion);
//       echo json_encode($usuarios);
//     }
//     break;
//   case 'POST':
//     global $conexion;
//     // Crea un nuevo usuario
//     $datosUsuario = json_decode(file_get_contents('php://input'), true);
//     $idUsuario_creado = crearUsuario($conexion, $datosUsuario);
//     echo json_encode(['idUsuario' => $idUsuario_creado]);
//     break;
//     case 'PUT':
//       global $conexion;
//       // Obtener los datos enviados por el cliente
//       $datosUsuario = json_decode(file_get_contents('php://input'), true);
      
//       // Obtener los campos del usuario a actualizar
//       $idUsuario = $datosUsuario['idUsuario'];
//       actualizarUsuario($conexion, $idUsuario, $datosUsuario);
//       break;
  
//   case 'DELETE':
//     global $conexion;
//     // Elimina un usuario por su ID
//     eliminarUsuario($conexion, $idUsuario);
//     break;
//   default:
//     // Si se intenta realizar una acción no válida, devuelve un error 400
//     http_response_code(400);
//     echo json_encode(['error' => 'Acción no válida']);
//     break;
//}
