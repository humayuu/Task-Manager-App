<?php

/**
 * Class for Database Operation
 */

class Database extends Auth
{

    /**
     * Function for Store Data
     */
    public function store($table, $params = [], $redirect = null)
    {
        try {

            if (!$this->tableExists($table)) return false;

            $column = implode(', ', array_keys($params));
            $placeHolder = implode(', ', array_keys($params));

            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare('INSERT INTO ');
        } catch (Exception $e) {
            $this->errors[] = 'Error in store data ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Function for Fetch Data
     */
    public function all($table, $rows = '*', $join = null, $where = null, $order = null, $limit = null, $offset = null) {}

    /**
     * Function for Fetch single Data
     */
    public function find($table, $rows = '*', $join = null, $where = null, $order = null, $limit = null, $offset = null) {}

    /**
     * Function for Fetch Update Data
     */
    public function update($table, $params = [], $where = null, $redirect = null) {}


    /**
     * Function for Fetch Delete Data
     */
    public function delete($table, $where = null, $redirect = null) {}


    /**
     * Function for image upload
     */
    public function file($file, $uploadDir) {}


    /**
     * Function for pagination
     */
    public function paginator($table, $pageNo, $limit) {}
}