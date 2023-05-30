<?php

/**
 * CRUD para manejar las sessiones.
 * Requiere de una session, pero no la inicio aqui para evitar problemas.
 */
class sessionController
{
    /**
     * @param Array $x Recibe un objeto con las sessiones que quieren crear o actualizar
     * @return Bool Siempre retorna true 
     */
    public static function createSession(array $x): Bool
    {
        foreach ($x as $key => $value) {
            $_SESSION[$key] = $value;
        }

        return true;
    }

    /**
     * @return Array Devuelve todas las sessiones activas
     */
    public static function readSession(): array
    {
        return array_merge(
            array_map("self::utf8", $_SESSION),
            ["count" => count($_SESSION)]
        );
    }

    /**
     * @return Mixed uft8_encode valido con arreglos
     */
    public static function utf8(mixed $utf8): mixed
    {
        return !is_array($utf8)
            ? AutomaticForm::iso8859_1_to_utf8($utf8)
            : array_map("self::utf8", $utf8);
    }

    /**
     * @return String Devuelve una de las sessiones activas
     */
    public static function getSession($key): String
    {
        return $_SESSION[$key] ? $_SESSION[$key] : "";
    }

    /**
     * @param Array $x Recibe un objeto con las sessiones que quieren crear o actualizar
     * @return Bool Siempre retorna true 
     */
    public static function updateSession(array $x): Bool
    {
        return sessionController::createSession($x);
    }

    /**
     * @param Array|String $x Recibe un arreglo con los nombres de las sessiones a borrar
     */
    public static function deleteSession(array|String $x): Bool
    {
        if (is_array($x)) {
            foreach ($x as $key => $value) {
                unset($_SESSION[$value]);
            }
        } else {
            unset($_SESSION[$x]);
        }
        return true;
    }
}
