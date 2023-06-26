<?php


//use vendor\firebase\JWT\JWT;
use Firebase\JWT\JWT;

/*
Funcion para generar el token
*/

// Función para validar las credenciales del usuario
function validarCredenciales($usuario, $contrasena) {
  // Preparamos la consulta SQL para buscar el usuario y la contraseña en la tabla de usuarios
  $statement = Flight::db()->prepare("SELECT u.idUsuario , u.idRol, r.rol
  FROM usuarios AS u
  INNER JOIN roles AS r ON u.idRol = r.idRol
  WHERE usuario = :usuario AND contrasena = :contrasena");
  $statement->bindParam(':usuario', $usuario);
  $statement->bindParam(':contrasena', $contrasena);
  $statement -> execute();

  // Verificamos si hubo un error en la consulta
  if (!$statement) {
    http_response_code(403); // Internal Server Error
    echo "Error en la consulta a la base de datos: " . Flight::db()->error;
    return false;
  }
  // Obtenemos el primer resultado de la consulta
  $datos = $statement->fetch(PDO::FETCH_ASSOC);
  //echo Flight::json($datos);

  if ($datos != Null) {
    $token = generarToken($usuario,$datos['idUsuario'],$datos['idRol'],$datos['rol']);
    echo json_encode(array("token" => $token));
  }else{
  http_response_code(401); // Unauthorized
  echo "Nombre de usuario o contraseña incorrectos";
  }
  return $token;
}

function generarToken($usuario,$idUsuario,$idRol,$rol){
  $time = time();

  $token = array(
    "iat"=> $time, //tiempo presente
    "exp" => $time *(60*30),// tiempo de vida del token (segundos*minutos*horas*dias)
    "data"=> [
      "usuario"=> $usuario,
      "id" => $idUsuario,
      "idRol"=>$idRol,
      "rol"=>$rol
    ]
  );
  $jwt = JWT::encode($token,"65s4fdga45vdg654","HS256");
  //echo '<pre>'; print_r($jwt); echo '</pre>';
  return $jwt;
}


