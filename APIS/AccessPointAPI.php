<?php

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../Models/AccessPoint.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../APIS/Security.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../Utils/Exceptions.php';

class AccessPointAPI
{
    public function API()
    {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];

        $obj = new Security();
        if ($obj->autorizar() == 10) {

            switch ($method) {
                case 'GET':
                    if ($_GET['action'] == 'accesspoints' && isset($_GET['id'])) {
                        $this->getAccessPoints();
                    }
                    break;
                case 'POST':
                    echo "POST";
                    //por definir
                    break;
                case 'PUT':
                    print "PUT";
                    //por definir
                    break;
                case 'DELETE':
                    //por definir
                    break;
                default:
                    //por definir
                    break;
            }
        }
    }

    private function getAccessPoints()
    {
        if ($_GET['action'] == 'accesspoints' && isset($_GET['id'])) {
            $response = AccessPoint::getAccessPoints($_GET['id']);
            if ($response) {
                $accesspoints["state"] = 1;
                $accesspoints["accesspoints"] = $response;
                echo json_encode($accesspoints, JSON_PRETTY_PRINT);
            } else {
                echo json_encode(array(
                    'state' => '2',
                    'message' => 'No tienes puntos de acceso asignados'
                ), JSON_PRETTY_PRINT);
            }
        } else {
            new Exceptions(400);
            //$this->response(400);
        }
    }
}