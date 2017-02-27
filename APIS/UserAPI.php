<?php

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../Models/User.php';

class UserAPI
{
    public function API()
    {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET':
                $this->getUsers();
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
                $this->response(405);
                break;
        }
    }

    private function getUsers()
    {
        if ($_GET['action'] == 'users') {
            if (isset($_GET['id'])) {//muestra 1 solo registro si es que existiera ID
                $response = User::getUser($_GET['id']);
                echo json_encode($response, JSON_PRETTY_PRINT);
            } else { //muestra todos los registros
                $response = User::getUsers();
                echo json_encode($response, JSON_PRETTY_PRINT);
            }
        } else {
            //$this->response(400);
        }
    }

    private function createUser()
    {
        if ($_GET['action'] == 'users') {
            //Decodifica un string de JSON
            $obj = json_decode(file_get_contents('php://input'), true);

            if (empty((array)$obj)) {
                $this->response(422, "error", "Nada para añadir, revisa los datos");
            } else if (
                isset($obj['identification']) &&
                isset($obj['firstName']) &&
                isset($obj['lastName']) &&
                isset($obj['nickName']) &&
                isset($obj['email']) &&
                isset($obj['mobile']) &&
                isset($obj['password']) &&
                isset($obj['state']) &&
                isset($obj['rol'])
            ) {
                $response = User::create(
                    $obj['identification'],
                    $obj['firstName'],
                    $obj['lastName'],
                    $obj['nickName'],
                    $obj['email'],
                    $obj['mobile'],
                    $obj['password'],
                    $obj['state'],
                    $obj['rol']
                );
                $this->response(200, "success", $response);
            } else {
                $this->response(422, "error", "Alguna propiedad no esta definida o es incorrecta");
            }
        } else if ($_GET['action'] == 'checkLogin') {
            //Decodifica un string de JSON
            $obj = json_decode(file_get_contents('php://input'), true);

            if (empty((array)$obj)) {
                $this->response(422, "error", "Nada para añadir, revisa los datos");
            } else if (
                isset($obj['nickName']) &&
                isset($obj['password'])
            ) {
                $response = User::checkLogin($obj['nickName'], $obj['password']);
                if ($response) {
                    $user["state"] = 1;
                    $user["user"] = $response;
                    echo json_encode($user, JSON_PRETTY_PRINT);
                } else {
                    echo json_encode(array(
                        'state' => '2',
                        'message' => 'Usuario o Contraseña incorrectos'
                    ), JSON_PRETTY_PRINT);
                }
            } else {
                $this->response(422, "error", "Alguna propiedad no esta definida o es incorrecta");
            }
        } else {
            $this->response(400);
        }
    }

    function updateUser()
    {
        if ($_GET['action'] == 'users' && isset($_GET['id'])) {
            if ($_GET['action'] == 'users') {
                $obj = json_decode(file_get_contents('php://input'), true);

                if (empty((array)$obj)) {
                    $this->response(422, "error", "Nada para añadir, revisa los datos");
                } else if (
                    isset($obj['identification']) &&
                    isset($obj['firstName']) &&
                    isset($obj['lastName']) &&
                    isset($obj['nickName']) &&
                    isset($obj['email']) &&
                    isset($obj['mobile']) &&
                    isset($obj['password']) &&
                    isset($obj['state']) &&
                    isset($obj['rol'])
                ) {
                    User::update(
                        $obj['identification'],
                        $obj['firstName'],
                        $obj['lastName'],
                        $obj['nickName'],
                        $obj['email'],
                        $obj['mobile'],
                        $obj['password'],
                        $obj['state'],
                        $obj['rol'],
                        $_GET['id']
                    );
                    $this->response(200, "success", "Usuario actualizado");
                } else {
                    $this->response(422, "error", "Alguna propiedad no esta definida o es incorrecta");
                }
                exit;
            }
        }
        $this->response(400);
    }

    function deleteUser()
    {
        if (isset($_GET['action']) && isset($_GET['id'])) {
            if ($_GET['action'] == 'users') {
                $response = User::delete($_GET['id']);
                if ((int)$response == -1) {
                    $this->response(422, "error", "Error al eliminar el Usuario");
                    exit;
                }
                $this->response(204);
                exit;
            }
        }
        $this->response(400);
    }

    private function response($code = 200, $status = "", $message = "")
    {
        http_response_code($code);
        if (!empty($status) && !empty($message)) {
            $response = array("status" => $status, "message" => $message);
            echo json_encode($response, JSON_PRETTY_PRINT);
        }
    }
}