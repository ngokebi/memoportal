<?php

include_once "Database.php";

class Memo
{

    public $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function get_memo_format()
    {

        $sql = "SELECT * FROM MemoFormat";
        $query = $this->conn->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($result);
    }


    public function get_concurring_details($id)
    {
        $sql = "SELECT * FROM MemoFormat WHERE ID = :id";
        $query = $this->conn->prepare($sql);
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($result);
    }

    public function get_concurring_staff($username)
    {
        $sql = "SELECT * from Users WHERE Title IS NOT NULL AND UserID != :userid";
        $query = $this->conn->prepare($sql);
        $query->bindParam(":userid", $username, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($result);
    }

    public function get_concurring_staff1()
    {
        $sql = "SELECT * from Users WHERE Title IS NOT NULL";
        $query = $this->conn->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($result);
    }

    public function get_concurring_staff_title($userid)
    {
        $sql = "SELECT * from Users WHERE UserID = :userid";
        $query = $this->conn->prepare($sql);
        $query->bindParam(":userid", $userid, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($result);
    }

    public function get_serial_no($department)
    {
        $sql = "SELECT SerialNumber FROM Memo WHERE Month(MemoDate) = Month(now()) and Department = :department ORDER BY SerialNumber DESC LIMIT 1";
        $query = $this->conn->prepare($sql);
        $query->bindParam(":department", $department, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return json_encode($result);
    }

    public function get_dept_code($department)
    {
        $sql = "SELECT DepartmentCode FROM Department WHERE DepartmentName = :department";
        $query = $this->conn->prepare($sql);
        $query->bindParam(":department", $department, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return json_encode($result);
    }

    public function insert_memo($data)
    {

        $status = 0;

        for ($i = 0; $i <= count($data) - 1; $i++) {
            $sql = "SELECT * FROM Memo WHERE Owner = :owner AND MemoCode = :memocode AND Subject = :subject";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":owner", $data[$i]['owner'], PDO::PARAM_STR);
            $query->bindParam(":memocode", $data[$i]['memo_code'], PDO::PARAM_STR);
            $query->bindParam(":subject", $data[$i]['subject'], PDO::PARAM_STR);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_OBJ);
            if ($query->rowCount() > 0) {
                return  json_encode("Memo Already Exist");
            } else {
                $sql_insert = "INSERT INTO Memo (Department, MemoCode, FormatID, DepartmentCode, MemoDate, SerialNumber, Owner, Position, Through, Recipient, Subject, Content, Status, DateCreated) 
                VALUES (:Department, :MemoCode, :FormatID, :DepartmentCode, :MemoDate, :SerialNumber, :Owner, :Position, :Through, :Recipient, :Subject, :Content, :Status, now())";
                $query = $this->conn->prepare($sql_insert);
                $query->bindParam(":Department", $data[$i]['department'], PDO::PARAM_STR);
                $query->bindParam(":MemoCode", $data[$i]['memo_code'], PDO::PARAM_STR);
                $query->bindParam(":FormatID", $data[$i]['format_id'], PDO::PARAM_INT);
                $query->bindParam(":DepartmentCode", $data[$i]['dept_code'], PDO::PARAM_STR);
                $query->bindParam(":MemoDate", $data[$i]['date'], PDO::PARAM_STR);
                $query->bindParam(":SerialNumber", $data[$i]['serial_no'], PDO::PARAM_STR);
                $query->bindParam(":Owner", $data[$i]['owner'], PDO::PARAM_STR);
                $query->bindParam(":Position", $data[$i]['position'], PDO::PARAM_STR);
                $query->bindParam(":Through", $data[$i]['through'], PDO::PARAM_STR);
                $query->bindParam(":Recipient", $data[$i]['recipient'], PDO::PARAM_STR);
                $query->bindParam(":Subject", $data[$i]['subject'], PDO::PARAM_STR);
                $query->bindParam(":Content", $data[$i]['content'], PDO::PARAM_STR);
                $query->bindParam(":Status", $status, PDO::PARAM_STR);
                $query->execute();
            }
        }
        return true;
    }

    public function update_memo($data)
    {
        for ($i = 0; $i <= count($data) - 1; $i++) {

            $sql_update = "UPDATE Memo SET MemoDate = :MemoDate, Through = :Through, Recipient = :Recipient, Subject = :Subject, Content = :Content, DateUpdated = now() WHERE ID = :id";
            $query = $this->conn->prepare($sql_update);
            $query->bindParam(":MemoDate", $data[$i]['date'], PDO::PARAM_STR);
            $query->bindParam(":Through", $data[$i]['through'], PDO::PARAM_STR);
            $query->bindParam(":Recipient", $data[$i]['recipient'], PDO::PARAM_STR);
            $query->bindParam(":Subject", $data[$i]['subject'], PDO::PARAM_STR);
            $query->bindParam(":Content", $data[$i]['content'], PDO::PARAM_STR);
            $query->bindParam(":id", $data[$i]['edit_id'], PDO::PARAM_STR);
            $query->execute();
        }
        return true;
    }

    public function search_memo($start_date, $end_date, $department, $userid)
    {
        $sql = "SELECT *, a.ID as 'MemoID',
        (SELECT ((SELECT COUNT(Status) FROM Approvals WHERE Status = 1 AND MemoID=a.ID) / (COUNT(Status)) * 100) from Approvals where MemoID = a.ID group by MemoID) as 'Percentage'
        FROM Memo a WHERE Owner = :userid AND Department = :department AND date(a.DateCreated) >= :start_date AND date(a.DateCreated) <= :end_date";
        $query = $this->conn->prepare($sql);
        $query->bindParam(":start_date", $start_date, PDO::PARAM_STR);
        $query->bindParam(":end_date", $end_date, PDO::PARAM_STR);
        $query->bindParam(":department", $department, PDO::PARAM_STR);
        $query->bindParam(":userid", $userid, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($result);
    }

    function update_memo_status($id)
    {
        $status = 2;
        $sql_update = "UPDATE Memo SET Status = :Status, DateUpdated = now() WHERE ID = :id";
        $query = $this->conn->prepare($sql_update);
        $query->bindValue(':Status', $status, PDO::PARAM_STR);
        $query->bindValue(':id', $id, PDO::PARAM_STR);
        $query->execute();
        return true;
    }

    public function search_memo_approval($start_date, $end_date, $userid)
    {
        $sql = "SELECT *,a.ID as 'MemoID',a.Status as 'memo_status', (SELECT CONCAT(Firstname, ' ', Surname) FROM Users WHERE UserID = a.Owner) as 'full_name',
        (SELECT ((SELECT COUNT(Status) FROM Approvals WHERE Status = 1 AND MemoID=a.ID) / (COUNT(Status)) * 100) from Approvals where MemoID = a.ID group by MemoID) as 'Percentage', DATE_FORMAT(a.DateCreated, '%M %d, %Y') as DateCreated 
        FROM Memo a INNER JOIN Approvals b ON a.ID=b.MemoID WHERE b.UserID = :userid AND date(a.DateCreated) >= :start_date AND date(a.DateCreated) <= :end_date";
        $query = $this->conn->prepare($sql);
        $query->bindParam(":start_date", $start_date, PDO::PARAM_STR);
        $query->bindParam(":end_date", $end_date, PDO::PARAM_STR);
        $query->bindParam(":userid", $userid, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($result);
    }

    public function check_approval($userid, $memo_id)
    {
        $sql = "SELECT *, (SELECT CONCAT(Surname, ' ', Firstname) FROM Users WHERE UserID = a.UserID) as 'full_name' FROM Approvals a WHERE LevelApproval = ((SELECT LevelApproval FROM Approvals WHERE UserID = :userid and MemoID = a.MemoID) - 1) and MemoID = :memo_id";
        $query = $this->conn->prepare($sql);
        $query->bindParam(":userid", $userid, PDO::PARAM_STR);
        $query->bindParam(":memo_id", $memo_id, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($result);
    }

    public function check_approval_submit($userid, $memo_id)
    {
        $sql = "SELECT *, (SELECT COUNT(*) FROM Approvals WHERE Status = 2) as 'check_decline' FROM Approvals a WHERE UserID = :userid and MemoID = :memo_id";
        $query = $this->conn->prepare($sql);
        $query->bindParam(":userid", $userid, PDO::PARAM_STR);
        $query->bindParam(":memo_id", $memo_id, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($result);
    }


    public function get_memo_details($id)
    {
        $sql = "SELECT *, (SELECT CONCAT(Firstname, ' ', Surname) FROM Users WHERE UserID = Owner) as 'owner_name',
        (SELECT Format FROM MemoFormat WHERE ID = FormatID) as 'Format',
        (SELECT ((SELECT COUNT(Status) FROM Approvals WHERE Status = 1 AND MemoID=a.ID) / (COUNT(Status)) * 100) from Approvals where MemoID = a.ID group by MemoID) as 'Percentage',
        (SELECT CONCAT(Firstname, ' ', Surname) FROM Users WHERE UserID = Recipient) as 'recipient_name',
        (SELECT CONCAT(Firstname, ' ', Surname) FROM Users WHERE UserID = Through) as 'through_name'
        FROM Memo a WHERE ID = :id";
        $query = $this->conn->prepare($sql);
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($result);
    }

    public function get_memo_details_edit($id)
    {
        $sql = "SELECT *, (SELECT CONCAT(Firstname, ' ', Surname) FROM Users WHERE UserID = Owner) as 'owner_name',
        (SELECT Format FROM MemoFormat WHERE ID = FormatID) as 'Format',
        (SELECT COUNT(UserID) from Approvals WHERE MemoID = a.ID) as 'count_approval',
        (SELECT CONCAT(Firstname, ' ', Surname) FROM Users WHERE UserID = Recipient) as 'recipient_name',
        (SELECT CONCAT(Firstname, ' ', Surname) FROM Users WHERE UserID = Through) as 'through_name'
        FROM Memo a WHERE ID = :id";
        $query = $this->conn->prepare($sql);
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($result);
    }

    public function insert_approval($data)
    {

        $status = 0;
        $level = 1;
        $sql_memo = "SELECT * FROM Memo order by ID DESC LIMIT 1";
        $query = $this->conn->prepare($sql_memo);
        $query->execute();
        $result_memo = $query->fetchAll(PDO::FETCH_OBJ);
        if ($query->rowCount() > 0) {
            foreach ($result_memo as $result) {

                $memo_id = $result->ID;

                $sql_approval = "SELECT * FROM Approvals WHERE MemoID = :memoid";
                $query = $this->conn->prepare($sql_approval);
                $query->bindValue(':memoid', $memo_id, PDO::PARAM_STR);
                $query->execute();
                $result = $query->fetchAll(PDO::FETCH_OBJ);
                if ($query->rowCount() > 0) {
                    return json_encode('Approval User Already Exists');
                } else {
                    foreach ($data as $value) {
                        $sql = "INSERT INTO Approvals (MemoID, UserID, LevelApproval, Status, DateCreated) VALUES (:MemoID, :UserID, :LevelApproval, :status, now())";
                        $query = $this->conn->prepare($sql);
                        $query->bindValue(':MemoID', $memo_id, PDO::PARAM_STR);
                        $query->bindValue(':UserID', $value, PDO::PARAM_STR);
                        $query->bindValue(':LevelApproval', $level++, PDO::PARAM_STR);
                        $query->bindValue(':status', $status, PDO::PARAM_STR);
                        $query->execute();
                    }
                    return true;
                }
            }
        }
    }

    public function add_approval($memo_id, $username, $level)
    {
        $status = 0;

        $sql_approval = "SELECT * FROM Approvals WHERE UserID = :userid AND MemoID = :memo_id";
        $query = $this->conn->prepare($sql_approval);
        $query->bindValue(':userid', $username, PDO::PARAM_STR);
        $query->bindValue(':memo_id', $memo_id, PDO::PARAM_STR);
        $query->execute();
        if ($query->rowCount() > 0) {
            return json_encode('Approval User Already Exists');
        } else {
            $sql = "INSERT INTO Approvals (MemoID, UserID, LevelApproval, Status, DateCreated) VALUES (:MemoID, :UserID, :LevelApproval, :status, now())";
            $query = $this->conn->prepare($sql);
            $query->bindValue(':MemoID', $memo_id, PDO::PARAM_STR);
            $query->bindValue(':UserID', $username, PDO::PARAM_STR);
            $query->bindValue(':LevelApproval', $level, PDO::PARAM_STR);
            $query->bindValue(':status', $status, PDO::PARAM_STR);
            $query->execute();
            return true;
        }
    }

    function submit_approval($memo_id, $userid, $status, $comment)
    {
        $sql_update = "UPDATE Approvals SET Status = :Status, Comments = :comment, DateUpdated = now() WHERE UserID = :userid AND MemoID = :memo_id";
        $query = $this->conn->prepare($sql_update);
        $query->bindValue(':Status', $status, PDO::PARAM_STR);
        $query->bindValue(':comment', $comment, PDO::PARAM_STR);
        $query->bindValue(':userid', $userid, PDO::PARAM_STR);
        $query->bindValue(':memo_id', $memo_id, PDO::PARAM_STR);
        $query->execute();
        return true;
    }

    function update_approval($approval_id, $memo_id, $userid, $level)
    {
        $sql_update = "UPDATE Approvals SET LevelApproval = :LevelApproval, UserID = :userid WHERE ID = :id AND MemoID = :memo_id";
        $query = $this->conn->prepare($sql_update);
        $query->bindValue(':LevelApproval', $level, PDO::PARAM_STR);
        $query->bindValue(':id', $approval_id, PDO::PARAM_STR);
        $query->bindValue(':userid', $userid, PDO::PARAM_STR);
        $query->bindValue(':memo_id', $memo_id, PDO::PARAM_STR);
        $query->execute();
        return true;
    }

    public function delete_approval($id)
    {
        $status = 0;
        $sql = "DELETE FROM Approvals WHERE ID = :id AND Status = :status";
        $query = $this->conn->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_STR);
        $query->bindValue(':status', $status, PDO::PARAM_INT);
        $query->execute();
        return true;
    }

    public function get_approval_details($id)
    {
        $sql = "SELECT *, (SELECT CONCAT(Firstname, ' ', Surname) FROM Users WHERE UserID = a.UserID) as 'full_name', (SELECT Owner FROM Memo WHERE ID = a.MemoID) AS 'Owner',
        (SELECT IFNULL(Title,(SELECT Position FROM Memo where Owner = a.UserID)) FROM Users WHERE UserID = a.UserID) as 'title', DATE_FORMAT(DateUpdated, '%M %d, %Y') as DateUpdated 
        FROM Approvals a WHERE MemoID = :id ORDER BY LevelApproval ASC";
        $query = $this->conn->prepare($sql);
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($result);
    }

    public function get_approval_user_detail($userid)
    {
        $sql = "SELECT *, (SELECT CONCAT(Firstname, ' ', Surname) FROM Users WHERE UserID = a.UserID) as 'full_name',
        (SELECT IFNULL(Title,(SELECT Position FROM Memo where Owner=a.UserID)) FROM Users WHERE UserID = a.UserID) as 'title'
        FROM Approvals a WHERE UserID = :userid";
        $query = $this->conn->prepare($sql);
        $query->bindParam(":userid", $userid, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($result);
    }

    public function insert_invoice($file)
    {

        $status = 0;

        $sql_memo = "SELECT * FROM Memo order by ID DESC LIMIT 1";
        $query = $this->conn->prepare($sql_memo);
        $query->execute();
        $result_memo = $query->fetchAll(PDO::FETCH_OBJ);
        if ($query->rowCount() > 0) {
            foreach ($result_memo as $result) {

                $memo_id = $result->ID;

                $sql_invoice = "SELECT * FROM Invoice WHERE MemoID = :memoid AND File = :file";
                $query1 = $this->conn->prepare($sql_invoice);
                $query1->bindValue(':memoid', $memo_id, PDO::PARAM_STR);
                $query1->bindValue(':file', $file, PDO::PARAM_STR);
                $query1->execute();
                if ($query1->rowCount() > 0) {
                    return json_encode('Invoice Already Exists');
                } else {
                    $sql = "INSERT INTO Invoice (MemoID, File, Status, DateCreated) VALUES (:MemoID, :File, :status, now())";
                    $query = $this->conn->prepare($sql);
                    $query->bindValue(':MemoID', $memo_id, PDO::PARAM_STR);
                    $query->bindValue(':File', $file, PDO::PARAM_STR);
                    $query->bindValue(':status', $status, PDO::PARAM_STR);
                    $query->execute();
                    return true;
                }
            }
        }
    }

    public function get_invoice_details($id)
    {
        $sql = "SELECT *, b.Status AS 'memo_status' FROM Invoice a INNER JOIN Memo b ON a.MemoID = b.ID WHERE MemoID = :id";
        $query = $this->conn->prepare($sql);
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($result);
    }

    public function get_memo_status($id)
    {
        $sql = "SELECT * FROM Memo WHERE ID = :id";
        $query = $this->conn->prepare($sql);
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($result);
    }

    public function update_invoice($file, $memo_id)
    {
        $sql = "UPDATE Invoice SET File = :File, DateUpdated = now() WHERE MemoID = :id";
        $query = $this->conn->prepare($sql);
        $query->bindValue(':MemoID', $memo_id, PDO::PARAM_STR);
        $query->bindValue(':File', $file, PDO::PARAM_STR);
        $query->execute();
    }

    public function delete_invoice($id)
    {
        $sql = "DELETE FROM Invoice WHERE ID = :id";
        $query = $this->conn->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_STR);
        $query->execute();
        return true;
    }

    public function concurring_table($memo_id)
    {
        $sql = "SELECT CONCAT(Surname, ' ', Firstname) as 'full_name', DATE_FORMAT(a.DateUpdated, '%b %d, %Y') as DateUpdated, Signature, b.Title, a.Status
        FROM Approvals a INNER JOIN Users b ON a.UserID=b.UserID INNER JOIN Signature c ON a.UserID=c.UserID WHERE b.Title NOT IN ('ED', 'CEO') and a.MemoID = :memo_id";
        $query = $this->conn->prepare($sql);
        $query->bindParam(":memo_id", $memo_id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($result);
    }

    public function approval_table($memo_id)
    {
        $sql = "SELECT CONCAT(Surname, ' ', Firstname) as 'full_name', DATE_FORMAT(a.DateUpdated, '%b %d, %Y') as DateUpdated, Signature, b.Title
        FROM Approvals a INNER JOIN Users b ON a.UserID=b.UserID INNER JOIN Signature c ON a.UserID=c.UserID WHERE b.Title IN ('ED', 'CEO') and a.MemoID = :memo_id order by b.Title DESC";
        $query = $this->conn->prepare($sql);
        $query->bindParam(":memo_id", $memo_id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($result);
    }

    public function generate_memo_details($id)
    {
        $sql = "SELECT *, (SELECT CONCAT(Firstname, ' ', Surname) FROM Users WHERE UserID = Owner) as 'owner_name', DATE_FORMAT(MemoDate, '%M %d, %Y') as MemoDate,
        (SELECT CONCAT(Firstname, ' ', Surname) FROM Users WHERE UserID = Recipient) as 'recipient_name',
        (SELECT Title FROM Users WHERE UserID = Recipient) as 'recipient_title',
        (SELECT CONCAT(Firstname, ' ', Surname) FROM Users WHERE UserID = Through) as 'through_name',
        (SELECT Title FROM Users WHERE UserID = Through) as 'through_title'
        FROM Memo WHERE ID = :id";
        $query = $this->conn->prepare($sql);
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($result);
    }

    public function generate_first_concurring($id)
    {
        $sql = "SELECT (SELECT CONCAT(Firstname, ' ', Surname) FROM Users WHERE UserID = a.Owner) as 'owner_name' FROM Memo a WHERE ID = :id";
        $query = $this->conn->prepare($sql);
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($result);
    }

    public function get_staff_department($department)
    {

        $sql = "SELECT CONCAT(Firstname, ' ', Surname) as 'full_name', UserID FROM Users a WHERE Department = :department";
        $query = $this->conn->prepare($sql);
        $query->bindParam(":department", $department, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($result);
    }


    public function insert_signature($username, $signature)
    {

        $status = 1;

        $sql_signature = "SELECT * FROM Signature WHERE UserID = :userid";
        $query = $this->conn->prepare($sql_signature);
        $query->bindValue(':userid', $username, PDO::PARAM_STR);
        $query->execute();
        if ($query->rowCount() > 0) {

            $sql2 = "UPDATE Signature SET Signature = :Signature, DateUpdated = now() WHERE UserID = :userid";
            $query = $this->conn->prepare($sql2);
            $query->bindValue(':userid', $username, PDO::PARAM_STR);
            $query->bindValue(':Signature', $signature, PDO::PARAM_STR);
            $query->execute();
            return true;
        } else {
            $sql = "INSERT INTO Signature (UserID, Signature, Status, DateCreated) VALUES (:userid, :Signature, :status, now())";
            $query = $this->conn->prepare($sql);
            $query->bindValue(':userid', $username, PDO::PARAM_STR);
            $query->bindValue(':Signature', $signature, PDO::PARAM_STR);
            $query->bindValue(':status', $status, PDO::PARAM_STR);
            $query->execute();
            return true;
        }
    }

    public function get_total_memo($department)
    {
        $sql = "SELECT COUNT(*) AS 'total_memo' FROM Memo WHERE Department = :department";
        $query = $this->conn->prepare($sql);
        $query->bindParam(":department", $department, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($result);
    }

    public function total_approved_memo($department)
    {
        $status = 1;
        $sql = "SELECT IFNULL(COUNT(*), 0) AS 'total_memo_approved' FROM Memo WHERE Department = :department AND Status = :status";
        $query = $this->conn->prepare($sql);
        $query->bindParam(":department", $department, PDO::PARAM_STR);
        $query->bindParam(":status", $status, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($result);
    }

    public function total_rejected_memo($department)
    {
        $status = 2;
        $sql = "SELECT IFNULL(COUNT(*), 0) AS 'total_memo_rejected' FROM Memo WHERE Department = :department AND Status = :status";
        $query = $this->conn->prepare($sql);
        $query->bindParam(":department", $department, PDO::PARAM_STR);
        $query->bindParam(":status", $status, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($result);
    }

    public function total_ongoing_memo($department)
    {
        $status = 0;
        $sql = "SELECT IFNULL(COUNT(*), 0) AS 'total_memo_ongoing' FROM Memo WHERE Department = :department AND Status = :status";
        $query = $this->conn->prepare($sql);
        $query->bindParam(":department", $department, PDO::PARAM_STR);
        $query->bindParam(":status", $status, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($result);
    }

    public function memo_percentage($department)
    {

        $sql = "SELECT IFNULL((SELECT count(*) FROM Memo Where Status = 0 AND Department = :department),0) AS 'total_memo_ongoing',
        IFNULL((SELECT count(*) FROM Memo Where Status = 1 AND Department = :department),0) AS 'total_memo_approved',
        IFNULL((SELECT count(*) FROM Memo Where Status = 2 AND Department = :department),0) AS 'total_memo_rejected',
        IFNULL(COUNT((SELECT count(*) FROM Memo Where Status = 0 AND Department = :department)+(SELECT count(*) FROM Memo Where Status = 1 AND Department = :department)+(SELECT count(*) FROM Memo Where Status = 2 AND Department = :department)),0) as 'total'
        FROM Memo WHERE Department = :department";
        $query = $this->conn->prepare($sql);
        $query->bindParam(":department", $department, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($result);
    }

    public function memo_report($department)
    {
        $status = 2;
        $sql = "SELECT *, (SELECT (SELECT Concat(Surname, ' ', Firstname) FROM Users WHERE UserID = b.UserID) FROM Approvals b WHERE Status = 0 and MemoID = a.ID Limit 1) AS 'next_approval',
        IFNULL(DATEDIFF(DateUpdated, DateCreated), DATEDIFF(now(), DateCreated)) as 'time_spent', DATE_FORMAT(DateCreated, '%M %d, %Y') as 'DateCreated'
        FROM Memo a WHERE Status < :status AND Department = :department ORDER BY ID DESC LIMIT 4";
        $query = $this->conn->prepare($sql);
        $query->bindParam(":department", $department, PDO::PARAM_STR);
        $query->bindParam(":status", $status, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($result);
    }
}
