<?php

namespace App\Repositories;

use PDO;
use PDOException;
use PDOStatement;

class Connection
{

    private PDO $dbh; // Database Handler
    private \PDOStatement|false $stmt;  //Statement
    private string $error;


    function __construct()  {
        $DATABASE_URL = $_ENV["DATABASE_URL"];
        $this->dbh = new PDO ($DATABASE_URL);
        $this->dbh->exec('set names utf8');
    }

    /**
     * Set query
     * @param String $sql
     */
    public function query(string $sql): void
    {
        $this->stmt = $this->dbh->prepare($sql);
    }

    /**
     * Show the full query
     */
    public function showQuery(){
        if ($this->stmt instanceof PDOStatement){
            return  $this->stmt->fullQuery;
        }
        return false;
    }

    public function bind($param, $value, $type=null): void
    {

        if (is_null($type)) {
            $type = match (true) {
                is_int($value) => PDO::PARAM_INT,
                is_bool($value) => PDO::PARAM_BOOL,
                is_null($value) => PDO::PARAM_NULL,
                default => PDO::PARAM_STR,
            };
        }

        $this->stmt->bindValue($param, $value , $type);

    }

    /**
     * Execute statement
     */
    public function execute(): void
    {
        $this->stmt->execute();
    }

    /**
     * Return the id of the last inserted row
     * @return false|string
     */
    public function lastId(): bool|string
    {
        $this->execute();
        return  $this->dbh->lastInsertId();
    }

    /**
     * Return the multiples rows
     * @return object[]
     */
    public function fetchAll(): array
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Return One row
     * @return Object
     */
    public function fetchOne(): object
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Return number of rows
     * @return Number
     */
    public function count(){
        $this->execute();
        return $this->stmt->rowCount();
    }
}