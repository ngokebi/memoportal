<?php

include_once "Database.php";

class Logs
{

    public $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function logActivity($username, $activity)
    {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
        $sql = "INSERT INTO Logs (UserID, LogDate, Activity, ipAddress) VALUES (:userid, CURRENT_TIMESTAMP, :activity, :ipaddress)";
        $query = $this->conn->prepare($sql);
        $query->bindParam(":userid", $username, PDO::PARAM_STR);
        $query->bindParam(":activity", $activity, PDO::PARAM_STR);
        $query->bindParam("ipaddress", $ipaddress, PDO::PARAM_STR);
        $rs = $query->execute();
        if ($rs) {
            return true;
        } else {
            return false;
        }
    }
}
