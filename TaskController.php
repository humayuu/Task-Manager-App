<?php

require './AuthController.php';

/**
 * Class for all Database operations
 */

class TaskController extends Auth
{

    /**
     * Function for fetch all task
     */
    public function all($table, $rows = '*', $join = null, $where = null, $order = null, $limit = null,  $offset = null)
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
            return $this->result; // All Rows
        } catch (Exception $e) {
            $this->errors[] = "Error in fetch all data " . $e->getMessage();
            return false;
        }
    }

    /**
     * Function for store Task
     */
    public function store($table, $params, $redirect)
    {
        if (!$this->tableExists($table)) return false;

        $this->conn->beginTransaction();
        try {
            $columns = implode(', ', array_keys($params));
            $placeHolders = implode(', ', array_keys($params));
            $values = implode(', ', $params);

            $stmt = $this->conn->prepare("INSERT INTO $table ($columns) VALUES ($placeHolders)");
            $result =  $stmt->execute([$values]);

            if ($result) {
                $this->conn->commit();

                // Redirect to desire location
                header('Location: ' . $redirect);
                exit;
            }
            return false;
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->errors[] = "Error in store data " . $e->getMessage();
            return false;
        }
    }

    /**
     * Function for show single Task
     */
    public function find($table, $rows = '*', $join = null, $where = null, $order = null, $limit = null,  $offset = null)
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
            $this->result = $stmt->fetch();
            return $this->result; // single rows
        } catch (Exception $e) {
            $this->errors[] = "Error in fetch all data " . $e->getMessage();
            return false;
        }
    }

    /**
     * Function for update task
     */
    public function update($table, $params, $where, $redirect)
    {
        if (!$this->tableExists($table)) return false;

        $this->conn->beginTransaction();
        try {
            $setClause = [];

            foreach (array_keys($params) as $columns) {
                $setClause[] = "$columns = :$columns";
            }

            $toString = implode(', ', $setClause);

            $sql = "UPDATE $table SET " . $toString;
            if ($where !== null) $sql .= " WHERE $where";

            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute();

            if ($result) {
                $this->conn->commit();

                // Redirect to desire location
                header('Location: ' . $redirect);
                exit;
            }
            return false;
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->errors[] = "Error in update data " . $e->getMessage();
            return false;
        }
    }

    /**
     * Function for delete task 
     */
    public function delete($table, $where, $redirect)
    {
        if (!$this->tableExists($table)) return false;

        $this->conn->beginTransaction();
        try {

            $sql = "DELETE FROM $table";
            if ($where !== null) $sql .= " WHERE $where";

            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute();

            if ($result) {
                $this->conn->commit();

                // Redirect to desire location
                header('Location: ' . $redirect);
                exit;
            }
            return false;
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->errors[] = "Error in delete data " . $e->getMessage();
            return false;
        }
    }

    /**
     * Function for image upload
     */
    public function file($table, $name, $uploadDir)
    {
        if (!$this->tableExists($table)) return false;

        try {

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $maxFilesize = 2 * 2 * 1024; //2MB
            $uploadDirectory = __DIR__ . "$uploadDir";
            if (!is_dir($uploadDirectory)) mkdir($uploadDirectory, 0755, true);

            if (isset($_FILES["$name"]) && $_FILES["$name"]['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES["$name"]['name'], PATHINFO_EXTENSION));
                $tmpFile = $_FILES["$name"]['tmp_name'];
                $size = $_FILES["$name"]['size'];

                if ($size > $maxFilesize) {
                    $this->errors[] = "Max file size is 2MB";
                    return false;
                } elseif (!in_array($ext, $allowedExtensions)) {
                    $this->errors[] = "Extension not Allowed";
                    return false;
                }

                $filename = uniqid('image_') . time() . '.' . $ext;

                if (!move_uploaded_file($tmpFile, $uploadDirectory . $filename)) {
                    $this->errors[] = "Error in upload image";
                    return false;
                } else {
                    return true;
                }
            }
        } catch (Exception $e) {
            $this->errors[] = "Error in upload file " . $e->getMessage();
            return false;
        }
    }


    /**
     * Function for Calculate offset for pagination
     */
    public function CalculateOffset($limit)
    {
        if ($_GET['page']) {
            $pageNo = $_GET['page'];

            $offset = ($pageNo - 1) * $limit;
            return $offset;
        } else {
            $pageNo = 1;
        }
    }

    /**
     * Function for pagination
     */
    public function paginate($table, $pageNo, $limit)
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
                <ul class="pagination">';

                if ($totalPages > 5) {
                    echo '<li class="page-item"><a class="page-link" href="?page=' . ($pageNo - 1) . '">Previous</a></li>';
                } else {
                    echo '   <li class="page-item"><a class="page-link" disabled>Previous</a></li>';
                }

                for ($i = 1; $i <= $totalPages; $i++) {
                    $activeClass = ($i == $pageNo) ? 'active' : '';
                    echo "<li class='page-item'><a class='page-link " . $activeClass . "' href='?page=" . $i . "'>$i</a></li>";
                }

                if ($totalRows === $totalPages) {
                    echo '<li class="page-item"><a class="page-link" href="?page=' . ($pageNo + 1) . '">Next</a></li>';
                } else {
                    echo '   <li class="page-item"><a class="page-link" disabled>Previous</a></li>';
                }

                echo '  </ul>
                </nav>';
            }
        } catch (Exception $e) {
            $this->error('Error in show Pagination ', $e);
        }
    }
}
