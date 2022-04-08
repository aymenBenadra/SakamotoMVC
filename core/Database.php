<?php

namespace Core;

use PDO, PDOException;

/**
 * PDO Database Class
 * - Connect to database
 * - Create prepared statements
 * - Bind values
 * - Return rows and results
 *
 * @author 		Mohammed-Aymen Benadra
 * @uses 		PDO, PDOException
 * @package 	Core
 */
class Database
{
    private $dbh;
    private $stmt;
    private $error;

    /**
     * Create database connection
     *
     * @return void
     */
    public function __construct()
    {
        $dsn = "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};";
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        );

        try {
            $this->dbh = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    /**
     * Prepare statement with query
     *
     * @param  mixed $sql
     * @return void
     */
    public function query($sql)
    {
        $this->stmt = $this->dbh->prepare($sql);
    }

    /**
     * Bind Params with Values
     *
     * @param  mixed $param
     * @param  mixed $value
     * @param  mixed $type
     * @return void
     */
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
                    break;
            }
        }

        $this->stmt->bindValue($param, $value, $type);
    }

    /**
     * Executes the prepared statement
     *
     * @return bool
     */
    public function execute(): bool
    {
        try {
            return $this->stmt->execute();
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
            return false;
        }
    }

    /**
     * Get all records as array of objects
     *
     * @return void
     */
    public function resultSet()
    {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    /**
     * Get single record as object
     *
     * @return void
     */
    public function single()
    {
        $this->execute();
        return $this->stmt->fetch();
    }

    /**
     * Get number of records
     *
     * @return void
     */
    public function rowCount()
    {
        $this->execute();
        return $this->stmt->rowCount();
    }

    /**
     * Get last inserted id
     *
     * @return void
     */
    public function lastInsertedId()
    {
        return $this->dbh->lastInsertId();
    }

    /**
     * Get error
     *
     * @return void
     */
    public function getError()
    {
        return $this->error;
    }
}
