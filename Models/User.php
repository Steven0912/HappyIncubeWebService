<?php

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../DatabaseConnection/Database.php';

class User
{
    function __construct()
    {
    }

    public static function getUsers()
    {
        $query = "SELECT * FROM users";
        try {
            // Preparar sentencia
            $command = Database::getInstance()->getDb()->prepare($query);
            // Ejecutar sentencia preparada
            $command->execute();

            return $command->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getUser($id)
    {
        // Consulta de un usuario en especifico
        $query = "SELECT * FROM users
                             WHERE id = ?";

        try {
            // Preparar sentencia
            $command = Database::getInstance()->getDb()->prepare($query);
            // Ejecutar sentencia preparada
            $command->execute(array($id));
            // Capturar primera fila del resultado
            $row = $command->fetch(PDO::FETCH_ASSOC);
            return $row;

        } catch (PDOException $e) {
            // Aquí puedes clasificar el error dependiendo de la excepción
            // para presentarlo en la respuesta Json
            return -1;
        }
    }

    public static function getLastUser()
    {
        $query = "SELECT * FROM users ORDER BY id DESC LIMIT 1";

        try {
            // Preparar sentencia
            $command = Database::getInstance()->getDb()->prepare($query);
            // Capturar primera fila del resultado
            $row = $command->fetch(PDO::FETCH_ASSOC);
            return $row;

        } catch (PDOException $e) {
            // Aquí puedes clasificar el error dependiendo de la excepción
            // para presentarlo en la respuesta Json
            return -1;
        }
    }

    public static function getUserToken($id)
    {
        // Consulta de el token de un usuario en especifico
        $query = "SELECT token FROM users WHERE id = ?";

        try {
            // Preparar la sentencia
            $command = Database::getInstance()->getDb()->prepare($query);
            // Ejecutar la sentencia preparada
            $command->execute(array($id));
            // Capturar primera fila del resultado
            $row = $command->fetch(PDO::FETCH_ASSOC);
            return $row;
        } catch (PDOException $e) {
            return -1;
        }
    }

    public static function create(
        $identification,
        $firstName,
        $lastName,
        $nickName,
        $email,
        $mobile,
        $password,
        $state = 1,
        $rol,
        $token
    )
    {
        // Sentencia INSERT
        $query = "INSERT INTO users ( " .
            " identification," .
            " firstName," .
            " lastName," .
            " nickName," .
            " email," .
            " mobile," .
            " password," .
            " States_id," .
            " Roles_id," .
            " token)" .
            " VALUES( ?,?,?,?,?,?,?,?,?,? )";

        try {
            // Preparar la sentencia
            $command = Database::getInstance()->getDb()->prepare($query);
            $command->execute(
                array(
                    $identification,
                    $firstName,
                    $lastName,
                    $nickName,
                    $email,
                    $mobile,
                    $password,
                    $state,
                    $rol,
                    $token
                )
            );
            return $command;

        } catch (PDOException $e) {
            return -1;
        }
    }

    public static function update(
        $identification,
        $firstName,
        $lastName,
        $nickName,
        $email,
        $mobile,
        $password,
        $state,
        $rol,
        $id
    )
    {
        // Creando query UPDATE
        $query = "UPDATE users" .
            " SET identification=?, firstName=?, lastName=?, nickName=?, email=?, mobile=?, password=?, States_id=?, Roles_id=? " .
            "WHERE id=?";

        try {
            // Preparar la sentencia
            $command = Database::getInstance()->getDb()->prepare($query);

            // Relacionar y ejecutar la sentencia
            $command->execute(array($identification, $firstName, $lastName, $nickName, $email, $mobile, $password, $state, $rol, $id));

            return $command;

        } catch (PDOException $e) {
            return -1;
        }
    }

    public static function setUserToken($id, $token)
    {
        // Creando query UPDATE
        $query = "UPDATE users" .
            " SET token=? " .
            "WHERE id=?";

        try {
            // Preparar la sentencia
            $command = Database::getInstance()->getDb()->prepare($query);

            // Relacionar y ejecutar la sentencia
            $command->execute(array($token, $id));

            return $command;

        } catch (PDOException $e) {
            return -1;
        }
    }

    public static function delete($id)
    {
        // Sentencia DELETE
        $query = "DELETE FROM users WHERE id=?";

        try {
            // Preparar la sentencia
            $command = Database::getInstance()->getDb()->prepare($query);

            $command->execute(array($id));

            return $command;

        } catch (PDOException $e) {
            return -1;
        }
    }

    public static function checkLogin($nickName, $password)
    {
        // Consulta de la meta
        $query = "SELECT * FROM users
                             WHERE nickName = ? and password = ?";

        try {
            $command = Database::getInstance()->getDb()->prepare($query);

            $command->execute(array($nickName, $password));

            $row = $command->fetch(PDO::FETCH_ASSOC);

            return $row;

        } catch (PDOException $e) {
            return -1;
        }
    }
}

?>