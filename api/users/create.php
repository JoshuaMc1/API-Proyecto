<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

try{
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        require "../../database.php";
        require "../../users.php";
        $database = new Database();
        $connection = $database->getConnection();
        $users = new Users($connection);
        $data = json_decode(file_get_contents("php://input"));

        if (isset($data)) {
            if(isset($data->correo, $data->clave, $data->dni, $data->nombres, $data->apellidos, $data->genero)){
                if($data->correo !="" || $data->clave !="" || $data->dni !="" || $data->nombres !="" || $data->apellidos !="" || $data->genero !=""){
                    $users->email = $data->correo;
                    $users->password = $data->clave;
                    $users->dni = $data->dni;
                    $users->names = $data->nombres;
                    $users->surnames = $data->apellidos;
                    $users->genre = $data->genero;

                    $users->user = strlen($data->usuario) > 0 ? $data->usuario : null;
                    $users->phone = strlen($data->telefono) > 0 ? $data->telefono : null;
                    $users->address = strlen($data->direccion) > 0 ? $data->direccion : null;
                    $users->profile = strlen($data->perfil) > 0 ? $data->perfil : null;

                    if($users->create()){
                        $database->closeConnection();
                        echo json_encode(array(
                            'message' => 'Se a registrado correctamente.'
                        ));
                    } else {
                        $database->closeConnection();
                        echo json_encode(array(
                            'message' => 'A ocurrido un error al registrarse.'
                        ));
                    }
                } else {
                    $database->closeConnection();
                    echo json_encode(array(
                        'message' => 'Campos requeridos estan vacios.'
                    ));
                }
            } else {
                $database->closeConnection();
                echo json_encode(array(
                    'message' => 'Campos requeridos no existen, por favor revisar el contenido enviado corresponda con el requerido.'
                ));
            }
        } else { /* Comprobando si los datos están en formato JSON. */
            $database->closeConnection();
            echo json_encode(array('message' => 'Debe enviar los datos en formato JSON.'));
        }
    } else echo json_encode(array('message' => 'Metodo de solicitud no valido.'));
}catch(Exception $e) {
    echo json_encode(array("message: " => $e->getMessage()));
}