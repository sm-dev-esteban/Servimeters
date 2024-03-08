<?php

namespace Config;

use Exception;

class AutoComplete extends CRUD
{
    private $LDAP;

    public function __construct()
    {
        # Initialize LDAP
        parent::__construct();
        $this->LDAP = new LDAP;
    }

    # Establish LDAP connection and set options
    private function bindLDAP(int $limit): LDAP
    {
        $user = $_SESSION["user"] ?? null;
        $pass = $_SESSION["pass"] ?? null;

        # Bind to LDAP with specified limit
        return $this->LDAP
            ->bind($user, $pass)
            ->setOption(["LDAP_OPT_SIZELIMIT" => $limit]);
    }

    /**
     * Perform LDAP search based on the provided filter and column
     * @param string $filter - The filter string
     * @param string $column - The LDAP column to search
     * @param int $limit - The limit of results (default is 1)
     * @return mixed - LDAP search results
     * @throws Exception - Throws an exception if LDAP search fails
     */
    public function ldap(?string $filter, string $column, int $limit = 1)
    {
        try {
            # Perform LDAP search
            return self::bindLDAP($limit)->search("({$column}={$filter}*)", [$column]);
        } catch (Exception $th) {
            # Throw an exception with a descriptive error message
            throw new Exception("Error in LDAP search: " . $th->getMessage(), $th->getCode(), $th);
        }
    }

    /**
     * Perform SQL query based on the provided table, filter, column, and limit
     * @param string $table - The SQL table to query
     * @param string $filter - The filter string
     * @param string $column - The SQL column to search
     * @param int $limit - The limit of results (default is 1)
     * @return array - SQL query results
     * @throws Exception - Throws an exception if the SQL query fails
     */
    public function sql(string $table, ?string $filter = null, ?string $column = null, ?int $limit = 1): array
    {
        try {
            $filter = $filter ?: $this->fullRequest["search"];
            $column = $column ?: $this->fullRequest["column"];
            $limit = $limit ?: $this->fullRequest["limit"];

            # Format the SQL query and execute it
            $query = self::formatQuery($table, $column, $limit);
            $prepare = [":FILTER" => "{$filter}%"];

            return $this->conn->executeQuery($query, $prepare);
        } catch (Exception $th) {
            # Throw an exception with a descriptive error message
            throw new Exception("Error in SQL query: " . $th->getMessage(), $th->getCode(), $th);
        }
    }

    /**
     * Check if the specified table and column exist in the database
     * @param string $table - The SQL table to check
     * @param string $column - The SQL column to check
     * @throws Exception - Throws an exception if the table or column does not exist
     */
    private function checkTableAndColumn(string $table, string $column): void
    {
        # Check if the table exists
        if (!$this->tableManager->checkTableExists($table)) throw new Exception("Error: Table does not exist: {$table}");

        # Check if the column exists in the table
        if (!$this->tableManager->checkColumnExists($table, $column)) throw new Exception("Error: Column does not exist: {$column}");
    }

    /**
     * Format and return the SQL query based on the database management system
     * @param string $table - The SQL table to query
     * @param string $column - The SQL column to search
     * @param int $limit - The limit of results
     * @return string - The formatted SQL query
     * @throws Exception - Throws an exception if the table or column does not exist
     */
    private function formatQuery(string $table, string $column, int $limit): string
    {
        # Check if the table and column exist
        self::checkTableAndColumn($table, $column);

        # Return the formatted SQL query based on the database management system
        return [
            "MYSQL" => "SELECT {$column} FROM {$table} WHERE {$column} LIKE :FILTER LIMIT {$limit}",
            "SQLITE" => "SELECT {$column} FROM {$table} WHERE {$column} LIKE :FILTER LIMIT {$limit}",
            "SQLSRV" => "SELECT TOP ({$limit}) {$column} FROM {$table} WHERE {$column} LIKE :FILTER",
        ][$this->conn->getGestor()] ?? "";
    }
}
