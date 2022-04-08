<?php

namespace Core;

use Exception;

/**
 * Model Class
 * - Initialize the database connection
 * - Get data from database
 * - Add new records to database
 * - Update records in database
 * - Delete records from database
 *
 * @author 		Mohammed-Aymen Benadra
 * @package 	Core
 */
abstract class Model
{
    protected $db;
    protected $table;
    protected $schema;

    /**
     * Initialize the database connection and set schema of the table
     *
     * @return void
     */
    public function __construct($schema)
    {
        $this->db = new Database;
        $this->schema = $schema;
    }

    /**
     * Return schema of the table
     * 
     * @param ?array $fields
     * @return array
     * @throws Exception
     */
    public function getSchema(...$fields)
    {
        if ($fields) {
            $schema = [];
            foreach ($fields as $field) {
                if (array_key_exists($field, $this->schema)) {
                    $schema[$field] = $this->schema[$field];
                } else {
                    throw new Exception("Field $field does not exist in schema");
                }
            }
            return $schema;
        }
        return $this->schema;
    }

    /**
     * Return schema of required fields
     * 
     * @return array
     */
    public function getRequiredSchema()
    {
        return array_filter($this->schema, function ($value) {
            return strpos($value, 'required') !== false;
        });
    }

    /**
     * Get single record from database
     *
     * @param  int $id
     * @return object
     */
    public function get($id)
    {
        $this->db->query("SELECT * FROM $this->table WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * get record by field
     * 
     * @param  string $field
     * @param  mixed $value
     * @return object
     */
    public function getBy($field, $value)
    {
        $sql = "SELECT * FROM {$this->table} WHERE $field = :$field";
        $this->db->query($sql);
        $this->db->bind(":$field", $value);

        return $this->db->single();
    }

    /**
     * Get all records from database
     *
     * @return array
     */
    public function getAll()
    {
        $this->db->query("SELECT * FROM $this->table");
        return $this->db->resultSet();
    }

    /**
     * Get all records by field
     *
     * @param  string $field
     * @param  string $value
     * @return array
     */
    public function getAllBy($field, $value)
    {
        $sql = "SELECT * FROM {$this->table} WHERE $field = :$field";
        $this->db->query($sql);
        $this->db->bind(":$field", $value);

        return $this->db->resultSet();
    }

    /**
     * Add new record to database
     *
     * @param  mixed $data
     * @return bool
     */
    public function add($data)
    {
        $params = array_keys($data);

        $this->db->query("INSERT INTO $this->table (" . implode(",", $params) . ") VALUES (" . implode(",", array_map(
            function ($param) {
                return ":" . $param;
            },
            $params
        )) . ")");

        foreach ($data as $key => $value) {
            $this->db->bind(':' . $key, $value);
        }

        return $this->db->execute();
    }

    /**
     * Update record in database
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return bool
     */
    public function update($id, $data)
    {
        $params = array_keys($data);

        $this->db->query("UPDATE $this->table SET " . implode(",", array_map(
            function ($param) {
                return $param . " = :" . $param;
            },
            $params
        )) . " WHERE id = :id");

        foreach ($data as $key => $value) {
            $this->db->bind(':' . $key, $value);
        }

        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    /**
     * Delete record from database
     *
     * @param  mixed $id
     * @return bool
     */
    public function delete($id)
    {
        $this->db->query("DELETE FROM $this->table WHERE id = :id");
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    /**
     * Get last inserted id
     *
     * @return int
     */
    public function getLastInsertedId()
    {
        return $this->db->lastInsertedId();
    }
}
