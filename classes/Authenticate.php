<?php

include_once "Database.php";

class Authenticate
{
    public $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function generatePassword($password)
    {
        return md5(md5(substr(strrev($password), 0, 3)) . md5(substr(strrev($password), 4)));
    }


    public function login($username, $password)
    {
        $sql = "SELECT * FROM Users WHERE UserID = :userid";
        $query = $this->conn->prepare($sql);
        $query->bindParam(":userid", $username, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        if ($query->rowCount() > 0) {
            foreach ($results as $result) {
                $dbPassword = $result->Password;
                if (self::generatePassword($password) == $dbPassword) {
                    $_SESSION['id'] = $result->ID;
                    $_SESSION['username'] = $result->UserID;
                    $_SESSION['firstname'] = $result->Firstname;
                    $_SESSION['surname'] = $result->Surname;
                    $_SESSION['department'] = $result->Department;
                    $_SESSION['surname'] =$result->Surname;
                    $_SESSION['email'] = $result->Email;
                    $_SESSION['last_acted_on'] = time();
                    return true;
                } else {
                    return json_encode("Error, Incorrect Details");
                }
            }
        } else {
            return json_encode("User Doesn't Exist!!!");
        }
    }
}
