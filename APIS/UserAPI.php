<?php

require_once '../Models/AccessPoint.php';

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
            case 'PUT'://actualiza
                echo 'PUT';
                break;
            case 'DELETE'://elimina
                echo 'DELETE';
                break;
            default://metodo NO soportado
                echo 'METODO NO SOPORTADO';
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
        } else if ($_GET['action'] == 'checkLogin') {
            $response = User::checkLogin($_GET['name'], $_GET['password']);
            echo json_encode($response, JSON_PRETTY_PRINT);
        } else {
            $this->response(400);
        }
    }

    private function createUser()
    {
        if ($_GET['action'] == 'peoples') {
            //Decodifica un string de JSON
            $obj = json_decode(file_get_contents('php://input'));

            if (empty((array)$obj)) {
                $this->response(422, "error", "Nada para añadir, revisa los datos");
            } else if ($obj) {
                User::create(
                    $obj['identification'],
                    $obj['firstName'],
                    $obj['lastName'],
                    $obj['email'],
                    $obj['mobile'],
                    $obj['password'],
                    $obj['state'],
                    $obj['rol']
                );
                $this->response(200, "success", "Nuevo usuario añadido");
            } else {
                $this->response(422, "error", "Esta propiedad no esta definida");
            }
        } else {
            $this->response(400);
        }
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