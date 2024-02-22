<?php
session_start();
include 'classes/Database.php';
include 'classes/Memo.php';
// include 'classes/Log.php';


$database = new Database();
$memo = new Memo($database);
// $log = new Logs($database);


$id = $_POST['memo_id'];

if (isset($_FILES['invoice'])) {

    foreach ($_FILES['invoice']['name'] as $key => $val) {

        $file_name = $_FILES['invoice']['name'][$key];
        $file_size = $_FILES['invoice']['size'][$key];
        $file_tmp = $_FILES['invoice']['tmp_name'][$key];
        $file_type = $_FILES['invoice']['type'][$key];


        $extension = "." . strtolower(pathinfo($file_name,PATHINFO_EXTENSION));
        $allowed_extensions = array(".jpg", ".jpeg", ".png", ".gif", ".pdf" , ".docx", ".doc");

        if (!in_array($extension, $allowed_extensions)) {
            echo "Invalid Format. Only jpg / jpeg / png / gif / pdf / word Format Allowed";
        } else {

            $imgnewfile = md5($file_name) . $extension;
            move_uploaded_file($file_tmp, "memo_image_upload/invoice/" . $imgnewfile);
            $result = $memo->insert_invoice($imgnewfile);
        }
    }
}


if (isset($_FILES['invoice_update'])) {

        $file_name = $_FILES['invoice_update']['name'];
        $file_size = $_FILES['invoice_update']['size'];
        $file_tmp = $_FILES['invoice_update']['tmp_name'];
        $file_type = $_FILES['invoice_update']['type'];


        $extension = "." . strtolower(pathinfo($file_name,PATHINFO_EXTENSION));
        $allowed_extensions = array(".jpg", ".jpeg", ".png", ".gif", ".pdf", ".docx", ".doc");

        if (!in_array($extension, $allowed_extensions)) {
            echo "Invalid Format. Only jpg / jpeg / png / gif / pdf / word Format Allowed";
        } else {

            $imgnewfile =  md5($file_name) . $extension;
            move_uploaded_file($file_tmp, "memo_image_upload/invoice/" . $imgnewfile);
            
            $result = $memo->insert_invoice($imgnewfile);
        }
}
