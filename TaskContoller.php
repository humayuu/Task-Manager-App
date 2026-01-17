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
        if (!$this->tableExists($table)) return false;

        try {

            $columns = implode(', ', array_keys($params));
            $placeHolders = ':' . implode(', :', array_keys($params));

            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("INSERT INTO $table ($columns) VALUES ($placeHolders)");
            $result = $stmt->execute([$params]);

            if ($result) {
                $this->conn->commit();
                header('Location: ' . $redirect);
                exit;
            }
        } catch (Exception $e) {
            $this->conn->rolBack();
            $this->errors[] = "Error in insert data " . $e->getMessage();
            return false;
        }
    }

    /**
     * Function for Fetch Data
     */
    public function all($table, $rows = "*", $join = null, $where = null, $order = null, $limit = null, $offset = null)
    {
        if (!$this->tableExists($table)) return false;

        try {

            $sql = "SELECT $rows FROM $table";
            if ($join !== null) $sql .= " $join";
            if ($where !== null) $sql .= " WHERE $where";
            if ($order !== null) $sql .= " ORDER BY $order";
            if ($limit !== null || $offset !== null) $sql .= " LIMIT $limit, $offset";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $this->result = $stmt->fetchAll();
            return $this->result;
        } catch (Exception $e) {
            $this->errors[] = "Error in fetch data " . $e->getMessage();
            return false;
        }
    }

    /**
     * Function for Fetch single Data
     */
    public function find($table, $rows = "*", $join = null, $where = null, $order = null, $limit = null, $offset = null)
    {
        if (!$this->tableExists($table)) return false;

        try {

            $sql = "SELECT $rows FROM $table";
            if ($join !== null) $sql .= " $join";
            if ($where !== null) $sql .= " WHERE $where";
            if ($order !== null) $sql .= " ORDER BY $order";
            if ($limit !== null || $offset !== null) $sql .= " LIMIT $limit, $offset";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $this->result = $stmt->fetch(); // Single row
            return $this->result;
        } catch (Exception $e) {
            $this->errors[] = "Error in fetch data " . $e->getMessage();
            return false;
        }
    }

    /**
     * Function for Fetch Update Data
     */
    public function update($table, $params = [], $where = null, $redirect = null)
    {
        if (!$this->tableExists($table)) return false;

        try {

            $setClause = [];

            foreach (array_keys($params) as $columns) {
                $setClause[] = "$columns = :$columns";
            }

            $toString = implode(', ', $setClause);

            $sql = "UPDATE $table SET $toString";
            if ($where !== null) $sql .= " WHERE $where";

            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([$params]);

            if ($result) {
                $this->conn->commit();
                header('Location: ' . $redirect);
                exit;
            }
        } catch (Exception $e) {
            $this->errors[] = "Error in update data " . $e->getMessage();
            return false;
        }
    }


    /**
     * Function for Fetch Delete Data
     */
    public function delete($table, $where = null, $redirect = null)
    {

        if (!$this->tableExists($table)) return false;

        try {
            $this->conn->beginTransaction();

            $sql = "DELETE FROM $table";
            if ($where !== null) $sql .= " WHERE $where";

            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute();
            if ($result) {
                $this->conn->commit();
                header('Location: ' . $redirect);
                exit;
            }
        } catch (Exception $e) {
            $this->errors[] = "Error in delete data " . $e->getMessage();
            return false;
        }
    }


    /**
     * Function for image upload
     */
    public function file($file, $uploadDir)
    {
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $allowedExtension = ['png', 'jpg', 'jpeg'];
        $maxFileSize = 2 * 1024 * 1024; //2MB

        try {

            if (isset($_FILES[$file]) || $_FILES[$file]['error'] === UPLOAD_ERR_OK) {
                $extension = strtolower(pathinfo($_FILES[$file]['name'], PATHINFO_EXTENSION));
                $size = $_FILES[$file]['size'];
                $tmpName = $_FILES[$file]['tmp_name'];

                if ($size > $maxFileSize) {
                    $this->errors[] = 'File size is too large max file size is 2MB';
                    return false;
                }

                if (!in_array($extension, $allowedExtension)) {
                    $this->errors[] = 'Extension not allowed';
                    return false;
                }

                $filename = uniqid($file . '_') . time() . $extension;

                if (!move_uploaded_file($tmpName, $uploadDir . $filename)) {
                    $this->errors[] = 'Error in upload file';
                    return false;
                }
            }
        } catch (Exception $e) {
            $this->errors[] = "Error in upload file " . $e->getMessage();
            return false;
        }
    }

    /**
     * Function for Validations 
     */
    public function validate($fields = [])
    {
        if (empty($fields)) {
            $this->errors[] = 'All fields are required';
            return false;
        }

        foreach ($fields as $field) {
            if (empty($field)) {
                $this->errors[] = 'All fields are required';
                return false;
            }
        }

        return true;
    }


    /**
     * Function for pagination
     */
    public function paginator($table, $pageNo, $limit)
    {
        if (!$this->tableExists($table)) return false;

        if (empty($pageNo)) {
            $this->errors[] = "Page no is required";
            return false;
        }

        try {

            $stmt = $this->conn->prepare("SELECT COUNT(*) AS total FROM $table");
            $stmt->execute();
            $totalRows = $stmt->fetch()['total'];
            $totalPages = ceil($totalRows / $limit);

            if ($totalRows > 5) {

                echo '<nav aria-label="Page navigation example">
        <ul class="pagination justify-content-end">';

                if ($pageNo > 1) {
                    echo ' <li class="page-item">
                <a href="?page=' . ($pageNo - 1) . '" class="page-link">Previous</a>
            </li>';
                } else {
                    echo '<li class="page-item disabled">
                <span class="page-link">Previous</span>
            </li>';
                }

                for ($i = 1; $i <= $totalPages; $i++) {
                    $activeClass = ($i == $pageNo) ? 'active' : '';
                    echo "<li class='page-item'><a class='page-link " . $activeClass . "' href='?page=" . $i . "'>$i</a></li>";
                }


                if ($pageNo < $totalPages) {
                    echo ' <li class="page-item">
                <a href="?page=' . ($pageNo + 1) . '" class="page-link">Next</a>
            </li>';
                } else {
                    echo '<li class="page-item disabled">
                <span class="page-link">Next</span>
            </li>';
                }

                echo '</ul>
          </nav>';
            }
        } catch (Exception $e) {
            $this->errors[] = "Error in pagination " . $e->getMessage();
            return false;
        }
    }
}
