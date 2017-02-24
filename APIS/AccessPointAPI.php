<?php

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../Models/AccessPoint.php';

class AccessPointAPI
{
    public function API()
    {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET':
                $this->getAccessPoints();
                break;
            case 'POST':
                //por definir
                break;
            case 'PUT':
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

    private function getAccessPoints()
    {
        if ($_GET['action'] == 'accesspoints' && isset($_GET['id'])) {
            $response = AccessPoint::getAccessPoints($_GET['id']);
            echo json_encode($response, JSON_PRETTY_PRINT);
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