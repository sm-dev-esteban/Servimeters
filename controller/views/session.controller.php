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
    static function createSession(array $x): Bool
    {
        foreach ($x as $key => $value) {
            $_SESSION[$key] = $value;
        }

        return true;
    }

    /**
     * @return Array Devuelve todas las sessiones activas
     */
    static function readSession(): array
    {
        return array_merge(
            self::utf8encode($_SESSION),
            ["count" => count($_SESSION)]
        );
    }

    /**
     * @return Mixed uft8_encode valido con arreglos
     */
    static function utf8encode(mixed $utf8): mixed
    {
        return !is_array($utf8)
            ? AutomaticForm::iso8859_1_to_utf8($utf8)
            : array_map("self::utf8encode", $utf8);
    }

    /**
     * @return Mixed uft8_decode valido con arreglos
     */
    static function utf8decode(mixed $utf8): mixed
    {
        return !is_array($utf8)
            ? AutomaticForm::utf8_to_iso8859_1($utf8)
            : array_map("self::utf8decode", $utf8);
    }

    /**
     * @return Mixed Devuelve una de las sessiones activas
     */
    static function getSession($key): Mixed
    {
        return $_SESSION[$key] ? $_SESSION[$key] : "";
    }

    /**
     * @param Array $x Recibe un objeto con las sessiones que quieren crear o actualizar
     * @return Bool Siempre retorna true 
     */
    static function updateSession(array $x): Bool
    {
        return sessionController::createSession($x);
    }

    /**
     * @param Array|String $x Recibe un arreglo con los nombres de las sessiones a borrar
     */
    static function deleteSession(array|String $x): Bool
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
