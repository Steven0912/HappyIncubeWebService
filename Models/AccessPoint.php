<?php

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../DatabaseConnection/Database.php';


class AccessPoint
{

    function __construct()
    {
    }

    public static function getAccessPoints($User_id)
    {
        $ids = self::getIdsAccessPoints($User_id);

        $query = "SELECT name, url FROM accesspoints WHERE id=?";
        try {
            // Preparar sentencia
            $command = Database::getInstance()->getDb()->prepare($query);

            $datas = "";
            foreach ($ids as $clave => $valor) {
                foreach ($valor as $k => $v) {
                    if ($k == "AccessPoints_id") {
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