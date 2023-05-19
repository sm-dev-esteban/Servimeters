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
        return $_SESSION;
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
