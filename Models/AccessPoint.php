<?php

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../DatabaseConnection/Database.php';


class AccessPoint
{

    var $collection;

    function __construct()
    {
        $this->collection = array();
    }

    public static function getAccessPoints($User_id)
    {
        $ids = self::getIdsAccessPoints($User_id);

        $query = "SELECT name, url FROM accesspoints WHERE id=?";
        try {
            // Preparar sentencia
            $command = Database::getInstance()->getDb()->prepare($query);

            /*$datas = array();
            for ($i = 0; $i < count($ids); $i++) {
                // Ejecutar sentencia preparada
                $command->execute(array($ids[$i]['AccessPoints_id']));
                $datas = $command->fetch(PDO::FETCH_ASSOC);
            }*/

            //return $ids;
            $datas = "";
            foreach ($ids as $clave => $valor) {
                //return $valor;
                foreach ($valor as $k => $v) {
                    if ($k == "AccessPoints_id") {
                        //$datas[] = $k;
                        $command->execute(array($v));
                        $datas[] = $command->fetch(PDO::FETCH_ASSOC);
                    }
                }
            }

            return $datas;

        } catch (PDOException $e) {
            return -1;
        }
    }

    private function getIdsAccessPoints($User_id)
    {
        $query = "SELECT * FROM assignedpoints WHERE Users_id = ?";
        try {
            // Preparar sentencia
            $command = Database::getInstance()->getDb()->prepare($query);
            // Ejecutar sentencia preparada
            $command->execute(array($User_id));

            return $command->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }
}