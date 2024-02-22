<?php
session_start();
include 'classes/Database.php';
include 'classes/Memo.php';
include 'classes/Log.php';


$database = new Database();
$memo = new Memo($database);
// $log = new Logs($database);


$userid = $_POST['username'];

if (isset($_FILES['signature'])) {

        $file_name = $_FILES['signature']['name'];
        $file_size = $_FILES['signature']['size'];
        $file_tmp = $_FILES['signature']['tmp_name'];
        $file_type = $_FILES['signature']['type'];


        $extension = "." . strtolower(pathinfo($file_name,PATHINFO_EXTENSION));
        $allowed_extensions = array(".jpg", ".jpeg", ".png", ".gif");

        if (!in_array($extension, $allowed_extensions)) {
            echo "Invalid Format. Only jpg / jpeg / png Format Allowed";
        } else {

            $imgnewfile =  md5($file_name) . $extension;
            move_uploaded_file($file_tmp, "memo_image_upload/signature/" . $imgnewfile);
            
            $result = $memo->insert_signature($userid, $imgnewfile);
        }
}
