<?php

namespace Core;

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
    
    /**
     * Initialize the database connection
     *
     * @return void
     */
    public function __construct()
    {
        $this->db = new Database;
    }
    
    /**
     * Get single record from database
     *
     * @param  mixed $id
     * @return object
     */
    public function get($id)
    {
        $this->db->query("SELECT * FROM $this->table WHERE id = :id");
        $this->db->bind(':id', $id);
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
