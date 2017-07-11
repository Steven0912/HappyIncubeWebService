<?php

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../Models/User.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../Models/AccessPoint.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../APIS/Security.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../Utils/Exceptions.php';
error_reporting(0);

class UserAPI
{
    public function API()
    {


        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];

        $obj = new Security();
        if ($obj->autorizar() == 10) {
            switch ($method) {
                case 'GET':
                    if ($_GET['action'] == 'users') {
                        $this->getUsers();
                    }
                    break;
                case 'POST':
                    $this->createUser();

                    break;
                case 'PUT':
                    $this->updateUser();
                    break;
                case 'DELETE':
                    $this->deleteUser();
                    break;
                default:
                    new Exceptions(405);
                    //$this->response(405);

                    break;
            }
        }
    }

    private
    function getUsers()
    {
        if ($_GET['action'] == 'users') {

            if (isset($_GET['id'])) {//muestra 1 solo registro si es que existiera ID
                $response = User::getUser($_GET['id']);
                echo json_encode($response, JSON_PRETTY_PRINT);
            } else { //muestra todos los registros
                $response = User::getUsers();
                if ($response) {
                    echo json_encode($response, JSON_PRETTY_PRINT);
                } else {
                    echo json_encode(array(
                        'state' => '2',
                        'message' => 'No hay usuarios en la bd'
                    ), JSON_PRETTY_PRINT);
                }
            }
        } else {
            new Exceptions(400);
            //$this->response(400);
        }
    }

    private
    function createUser()
    {
        if ($_GET['action'] == 'users') {
            //Decodifica un string de JSON
            $obj = json_decode(file_get_contents('php://input'), true);

            if (empty((array)$obj)) {
                new Exceptions(422, "error", "Nada para añadir, revisa los datos");
                //$this->response(422, "error", "Nada para añadir, revisa los datos");
            } else if (
                isset($obj['id_doc']) &&
                isset($obj['id_termino']) &&
                isset($obj['id_estado']) &&
                isset($obj['id_roles']) &&
                isset($obj['nombre_completo']) &&
                isset($obj['nombre']) &&
                isset($obj['apellido']) &&
                isset($obj['genero']) &&
                isset($obj['telefono']) &&
                isset($obj['correo']) &&
                isset($obj['password']) &&
                isset($obj['numero_doc']) &&
                isset($obj['foto_perfil']) &&
                isset($obj['direccion']) &&
                isset($obj['latitud']) &&
                isset($obj['longitud']) &&
                isset($obj['imagen_pin']) &&
                isset($obj['pin']) &&
                isset($obj['token'])
            ) {
                $response = User::create(
                    $obj['id_doc'],
                    $obj['id_termino'],
                    $obj['id_estado'],
                    $obj['id_roles'],
                    $obj['nombre_completo'],
                    $obj['nombre'],
                    $obj['apellido'],
                    $obj['genero'],
                    $obj['telefono'],
                    $obj['correo'],
                    md5($obj['password']),
                    $obj['numero_doc'],
                    ($obj['foto_perfil'] === "") ? null : $obj['foto_perfil'],
                    ($obj['direccion'] === "") ? null : $obj['direccion'],
                    ($obj['latitud'] === "") ? null : $obj['latitud'],
                    ($obj['longitud'] === "") ? null : $obj['longitud'],
                    ($obj['imagen_pin'] === "") ? null : $obj['imagen_pin'],
                    ($obj['pin'] === "") ? null : $obj['pin'],
                    $obj['token']
                );
                if ($response == -1) {
                    echo json_encode(array(
                        'state' => '2',
                        'message' => 'Este usuario ya existe, ve al menú de ingresar!'
                    ), JSON_PRETTY_PRINT);
                } else {
                    $userData = User::getLastUser();

                    AccessPoint::insertAccessPointsToUserDefault($userData["id"], 1);
                    AccessPoint::insertAccessPointsToUserDefault($userData["id"], 2);
                    $user["state"] = 1;
                    $user["user"] = $userData;
                    echo json_encode($user, JSON_PRETTY_PRINT);
                }
            } else {
                new Exceptions(422, "error", "Alguna propiedad no esta definida o es incorrecta");
                //$this->response(422, "error", "Alguna propiedad no esta definida o es incorrecta");
            }
        } else if ($_GET['action'] == 'checkLogin') {
            //Decodifica un string de JSON
            $obj = json_decode(file_get_contents('php://input'), true);

            if (empty((array)$obj)) {
                new Exceptions(422, "error", "Nada para añadir, revisa los datos");
                //$this->response(422, "error", "Nada para añadir, revisa los datos");
            } else if (
                isset($obj['mail']) &&
                isset($obj['password']) &&
                isset($obj['token'])
            ) {
                $response = User::validateEmail($obj['mail']);
                if ($response) {
                    // Correo correcto
                    $response = User::checkLogin($obj['mail'], md5($obj['password']));
                    if ($response) {
                        User::setUserToken($response["id"], $obj['token']);
                        $response = User::getUser($response["id"]);

                        $user["state"] = 1;


                        $user["user"] = $response;
                        echo json_encode($user, JSON_PRETTY_PRINT);
                    } else {
                        echo json_encode(array(
                            'state' => '2',
                            'message' => 'Contraseña incorrecta'
                        ), JSON_PRETTY_PRINT);
                    }
                } else {
                    // Correo incorrecto
                    echo json_encode(array(
                        'state' => '2',
                        'message' => 'Correo incorrecto'
                    ), JSON_PRETTY_PRINT);
                }
            } else {
                new Exceptions(422, "error", "Alguna propiedad no esta definida o es incorrecta");
                //$this->response(422, "error", "Alguna propiedad no esta definida o es incorrecta");
            }
        } else if ($_GET['action'] == 'validateUser') {
            //Decodifica un string de JSON
            $obj = json_decode(file_get_contents('php://input'), true);

            if (empty((array)$obj)) {
                new Exceptions(422, "error", "Nada para añadir, revisa los datos");
                //$this->response(422, "error", "Nada para añadir, revisa los datos");
            } else if (isset($obj['email'])) {
                $response = User::validateEmail($obj['email']);
                if ($response) {
                    $user["state"] = 1;
                    $user["user"] = $response;
                    echo json_encode($user, JSON_PRETTY_PRINT);
                } else {
                    echo json_encode(array(
                        'state' => '2',
                        'message' => 'Este Usuario aún no existe, registrese por favor!'
                    ), JSON_PRETTY_PRINT);
                }
            } else if (isset($obj['phone'])) {
                $response = User::validatePhone($obj['phone']);
                if ($response) {
                    $user["state"] = 1;
                    $user["user"] = $response;
                    echo json_encode($user, JSON_PRETTY_PRINT);
                } else {
                    echo json_encode(array(
                        'state' => '2',
                        'message' => 'Este Usuario aún no existe, registrese por favor!'
                    ), JSON_PRETTY_PRINT);
                }
            } else {
                new Exceptions(422, "error", "Alguna propiedad no esta definida o es incorrecta");
                //$this->response(422, "error", "Alguna propiedad no esta definida o es incorrecta");
            }
        } else {
            new Exceptions(400);
            //$this->response(400);
        }
    }

    function updateUser()
    {
        if ($_GET['action'] == 'users' && isset($_GET['id'])) {
            if ($_GET['action'] == 'users') {
                $obj = json_decode(file_get_contents('php://input'), true);

                if (empty((array)$obj)) {
                    new Exceptions(422, "error", "Nada para añadir, revisa los datos");
                    //$this->response(422, "error", "Nada para añadir, revisa los datos");
                } else if (
                    isset($obj['id_doc']) &&
                    isset($obj['id_termino']) &&
                    isset($obj['id_estado']) &&
                    isset($obj['id_roles']) &&
                    isset($obj['nombre_completo']) &&
                    isset($obj['nombre']) &&
                    isset($obj['apellido']) &&
                    isset($obj['genero']) &&
                    isset($obj['telefono']) &&
                    isset($obj['correo']) &&
                    isset($obj['password']) &&
                    isset($obj['numero_doc']) &&
                    isset($obj['foto_perfil']) &&
                    isset($obj['direccion']) &&
                    isset($obj['latitud']) &&
                    isset($obj['longitud']) &&
                    isset($obj['imagen_pin']) &&
                    isset($obj['pin'])
                ) {
                    User::update(
                        $obj['identification'],
                        $obj['firstName'],
                        $obj['lastName'],
                        $obj['name'],
                        $obj['nickName'],
                        $obj['email'],
                        $obj['mobile'],
                        $obj['password'],
                        $obj['gender'],
                        $obj['state'],
                        $obj['rol'],
                        $_GET['id']
                    );
                    new Exceptions(200, "success", "Usuario actualizado");
                    //$this->response(200, "success", "Usuario actualizado");
                } else {
                    new Exceptions(422, "error", "Alguna propiedad no esta definida o es incorrecta");
                    //$this->response(422, "error", "Alguna propiedad no esta definida o es incorrecta");
                }
                exit;
            }
        }
        new Exceptions(400);
        //$this->response(400);
    }

    function deleteUser()
    {
        if (isset($_GET['action']) && isset($_GET['id'])) {
            if ($_GET['action'] == 'users') {
                $response = User::delete($_GET['id']);
                if ((int)$response == -1) {
                    new Exceptions(422, "error", "Error al eliminar el Usuario");
                    //$this->response(422, "error", "Error al eliminar el Usuario");
                    exit;
                }
                new Exceptions(204);
                //$this->response(204);
                exit;
            }
        }
        new Exceptions(400);
        //$this->response(400);
    }
}