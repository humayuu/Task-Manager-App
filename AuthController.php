<?php

/**
 * Class for Database connection & Authentication
 */

class Auth
{
    public $errors = [];
    public $result = [];
    public $conn;

    /**
     * Function For Database Connection
     */
    public function __construct($host, $dbname, $username, $password)
    {
        try {
            $this->conn = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4;",
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ],
            );
        } catch (PDOException $e) {
            throw new PDOException('Database connection failed ' . $e->getMessage());
        }
    }

    /**
     * Function for check if the table is exists or not in database
     */
    protected function tableExists($table)
    {
        try {
            $stmt = $this->conn->prepare("SHOW TABLES LIKE ?");
            $stmt->execute([$table]);
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                $this->errors[] = "Table " . $table . " does not exists in this database";
                return false;
            }
        } catch (Exception $e) {
            $this->errors[] = 'Error in finding table is this databsae ' .  $e->getMessage();
            return false;
        }
    }


    /**
     * Function for return errors
     */
    public function attempt($table, $email, $password, $redirect)
    {
        if (!$this->tableExists($table)) return false;

        try {

            $stmt = $this->conn->prepare("SELECT * FROM $table WHERE user_email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch();

            if (!$user) {
                $this->errors[] = "Invalid user email";
                return false;
            }

            if (!password_verify($password, $user['user_password'])) {
                $this->errors[] = 'Invalid user Password';
                return false;
            }

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // store user data into session variable
            $_SESSION['status']   = true;
            $_SESSION['userId']   = $user['id'];
            $_SESSION['fullname'] = $user['user_fullname'];
            $_SESSION['email']    = $user['user_email'];

            // Redirect to inserted location
            header('Location: ' . $redirect);
            exit;
        } catch (Exception $e) {
            $this->errors[] = 'Error in attempt to login ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Function for User Logout
     */
    public function logout($redirect)
    {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            session_unset();

            if (session_destroy()) {
                // Redirect to inserted location
                header('Location: ' . $redirect);
                exit;
            }
        } catch (Exception $e) {
            $this->errors[] = 'Error in attempt to login ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Function for check user is login or not
     */
    public function checkUser($redirect)
    {
        if ($_SESSION['status'] === false) {
            $this->errors[] = 'Please login your account first';
            header('Location: ' . $redirect);
            exit;
        }
    }

    /**
     * Function for Check loggedIn user
     */
    public function LoggedIn($redirect)
    {
        if ($_SESSION['status'] === true) {
            header('Location: ' . $redirect);
            exit;
        }
    }


    /**
     * Function for return errors
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Function for Show Error & stop the execution for code
     */
    public function error($message,  $e)
    {
        $this->errors[] = "$message " . $e->getMessage();
        return false;
    }

    /**
     * Function for fields validation
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
     * Function for return result
     */
    public function getResult()
    {
        return $this->result;
    }


    /**
     * Function for close connection to Database
     */
    public function __destruct()
    {
        $this->conn = null;
    }
} // class ends here