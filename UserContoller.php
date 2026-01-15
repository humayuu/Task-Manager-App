<?php

/**
 * Class for user Authentication & Database connection
 */

class Auth
{
    public $conn;
    public $errors = [];
    public $result = [];

    /**
     * Function for Database Connection
     */
    public function __construct($host, $dbName, $username, $password)
    {
        try {
            $this->conn = new PDO(
                "mysql:host=$host;dbname=$dbName;charset=utf8mb4",
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]

            );
        } catch (PDOException $e) {
            throw new PDOException("Database connection error " . $e->getMessage());
        }
    }

    /**
     * Function for Check if the table is exists or not
     */
    protected function tableExists($table)
    {
        try {
            $stmt = $this->conn->prepare("SHOW TABLES LIKE ?");
            $tableInDB = $stmt->execute($table);

            if ($tableInDB->rowCount() > 0) {
                return true;
            } else {
                $this->errors[] = "$table is not exists in this database";
                return false;
            }
        } catch (Exception $e) {
            $this->errors[] = 'Error in Finding table in database ' . $e->getMessage();
            return false;
        }
    }


    /**
     * Function for user LoggedIn
     */
    public function attempt($table, $email, $password, $redirect)
    {
        try {

            if (!$this->tableExists($table)) return false;

            $stmt = $this->conn->prepare("SELECT * FROM $table WHERE user_email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch();

            if (!$user) {
                $this->errors[] = 'Invalid User Email';
                return false;
            }
            if (!password_verify($password, $user['user_password'])) {
                $this->errors[] = 'Invalid User Password';
                return false;
            }

            // Store user data into the session variable
            $_SESSION['loggedIn'] = true;
            $_SESSION['userId']   = $user['id'];
            $_SESSION['Fullname'] = $user['user_fullname'];
            $_SESSION['Email'] = $user['user_email'];

            // Redirected to inserted location
            header('Location: ' . $redirect);
            exit;
        } catch (Exception $e) {
            $this->errors[] = "Error in attempt to user login " . $e->getMessage();
            return false;
        }
    }

    /**
     * Function for user logout
     */
    public function logout($redirect)
    {
        try {

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            session_unset();

            if (session_destroy()) {
                header('Location: ' . $redirect);
                exit;
            }
        } catch (Exception $e) {
            $this->errors[] = "Error in user logout " . $e->getMessage();
            return false;
        }
    }

    /**
     * Function for check user is LoggedIn
     */
    public function checkUser($redirect)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
            header('Location: ' . $redirect);
            exit;
        }
    }


    /**
     * Function for check user is Already loggedIn
     */
    public function loggedIn($redirect)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if ($_SESSION['loggedIn'] === true) {
            header('Location: ' . $redirect);
            exit;
        }
    }

    /**
     * Function for get errors
     */
    public function getErrors()
    {
        return $this->errors;
    }


    /**
     * Function for get result
     */
    public function getResult()
    {
        return $this->result;
    }


    /**
     * Function for close connection 
     */
    public function __destruct()
    {
        $this->conn = null;
    }
}