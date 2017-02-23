<?php

require '../DatabaseConnection/Database.php';

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
        // Consulta de la meta
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

    public static function checkLogin($firstName, $password)
    {
        // Consulta de la meta
        $query = "SELECT * FROM users
                             WHERE fisrtName = ? and password = ?";

        try {
            $command = Database::getInstance()->getDb()->prepare($query);

            $command->execute(array($firstName, $password));

            $row = $command->fetch(PDO::FETCH_ASSOC);

            return $row;

        } catch (PDOException $e) {
            return -1;
        }
    }

    public static function update(
        $idUser,
        $titulo,
        $descripcion,
        $fechaLim,
        $categoria,
        $prioridad
    )
    {
        // Creando consulta UPDATE
        $consulta = "UPDATE meta" .
            " SET titulo=?, descripcion=?, fechaLim=?, categoria=?, prioridad=? " .
            "WHERE idMeta=?";

        // Preparar la sentencia
        $cmd = Database::getInstance()->getDb()->prepare($consulta);

        // Relacionar y ejecutar la sentencia
        $cmd->execute(array($titulo, $descripcion, $fechaLim, $categoria, $prioridad, $idMeta));

        return $cmd;
    }


    public static function create(
        $identification,
        $firstName,
        $lastName,
        $email,
        $mobile,
        $password,
        $state = 1,
        $rol
    )
    {
        // Sentencia INSERT
        $comando = "INSERT INTO users ( " .
            " identification," .
            " firstName," .
            " lastName," .
            " email," .
            " mobile," .
            " password," .
            " States_id," .
            " Roles_id)" .
            " VALUES( ?,?,?,?,?,?,?,? )";

        // Preparar la sentencia
        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        return $sentencia->execute(
            array(
                $identification,
                $firstName,
                $lastName,
                $email,
                $mobile,
                $password,
                $state,
                $rol
            )
        );

    }

    /**
     * Eliminar el registro con el identificador especificado
     *
     * @param $idMeta identificador de la meta
     * @return bool Respuesta de la eliminación
     */
    public static function delete($idMeta)
    {
        // Sentencia DELETE
        $comando = "DELETE FROM meta WHERE idMeta=?";

        // Preparar la sentencia
        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        return $sentencia->execute(array($idMeta));
    }
}

?>