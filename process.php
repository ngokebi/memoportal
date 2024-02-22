<?php

session_start();

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

error_reporting(E_ALL);


date_default_timezone_set("Africa/Lagos");

include "classes/Database.php";
include "classes/Authenticate.php";
include "classes/Log.php";
include "classes/Memo.php";


$database = new Database();
$auth = new Authenticate($database);
$log = new Logs($database);
$memo = new Memo($database);



function login($username, $password)
{
    global $auth, $log;
    $log->logActivity($username, $username . ' Successful Login');
    $result = $auth->login($username, $password);
    return $result;
}

function get_memo_format()
{
    global $memo;
    $result = $memo->get_memo_format();
    $output = json_decode($result, true);
    $html = "";
    foreach ($output as $row) {
        $id = base64_encode(base64_encode(base64_encode($row['ID'])));
        $preview = $row['Preview'];
        $thumbnail = $row['Thumbnail'];
        $format = $row['Format'];

        $html .= '<div class="col-sm-6 col-lg-3" ><div class="card d-block" id="memo_format_response">';
        $html .= '<img class="card-img-top" src="memo_format/' . $thumbnail . '" alt="IT ' . $format . ' Format">';
        $html .= '<div class="card-body">';
        $html .= '    <h5 class="card-title">' . $format . ' FORMAT</h5>';
        $html .= '    <a href="memo_format/' . $preview . '" class="btn btn-primary" target="_blank">Preview</a>';
        $html .= '<a type="button" href="create_memo.php?format_id=' . $id . '" class="btn btn-secondary" style="float: right;" class="btn btn-info">Create</a>';
        $html .= '</div>';
        $html .= '</div></div>';
    }

    echo $html;
}

function get_concurring_details($id)
{
    global $memo;
    $result = $memo->get_concurring_details($id);
    return $result;
}

function get_concurring_staff($username)
{
    global $memo;
    $result = $memo->get_concurring_staff($username);
    $output = json_decode($result, true);
    $html = "";
    foreach ($output as $row) {
        $id = $row['UserID'];
        $fullname = $row['Surname'] . ' ' . $row['Firstname'];

        $html .= '<option value = ' . $id . '>' . $fullname . '</option>';
    }
    echo $html;
}

function get_concurring_staff1()
{
    global $memo;
    $result = $memo->get_concurring_staff1();
    $output = json_decode($result, true);
    $html = "";
    foreach ($output as $row) {
        $id = $row['UserID'];
        $fullname = $row['Surname'] . ' ' . $row['Firstname'];

        $html .= '<option value = ' . $id . '>' . $fullname . '</option>';
    }
    echo $html;
}

function get_concurring_staff_title($userid)
{
    global $memo;
    $result = $memo->get_concurring_staff_title($userid);
    return $result;
}

function get_approval_user_detail($userid)
{
    global $memo;
    $result = $memo->get_approval_user_detail($userid);
    return $result;
}

function get_dept_code($department)
{
    global $memo;
    $result = $memo->get_dept_code($department);
    return $result;
}

function get_serial_no($department)
{
    global $memo;
    $result = $memo->get_serial_no($department);
    return $result;
}

function insert_memo($data)
{
    global $memo;
    $result = $memo->insert_memo($data);
    return $result;
}

function update_memo($data)
{
    global $memo;
    $result = $memo->update_memo($data);
    return $result;
}

function insert_approval($data)
{
    global $memo;
    $result = $memo->insert_approval($data);
    return $result;
}

function search_memo($start_date, $end_date, $department, $userid)
{
    global $memo;
    $result = $memo->search_memo($start_date, $end_date, $department, $userid);
    $output = json_decode($result, true);
    $html = "";
    if ($output == null) {
        echo "<h3><strong>No Records Found</strong></h3>";
    } else {
        foreach ($output as $row) {
            $id = base64_encode(base64_encode(base64_encode($row['MemoID'])));
            $subject = $row['Subject'];
            $request = htmlentities(strip_tags(substr($row['Content'], 0, 550) . '. . .'));
            $precentage = round($row['Percentage'], 0);
            $status = $row['Status'];


            $html .= '<div class="col-md-6 col-xxl-3">';
            $html .= '<div class="card d-block">';
            $html .= '<div class="card-body">';
            $html .= '<div class="dropdown card-widgets">';
            $html .= '<a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">';
            $html .= '<i class="ri-more-fill"></i>';
            $html .= '</a>';
            $html .= '<div class="dropdown-menu dropdown-menu-end">';
            $html .= '<a href="memo_edit.php?id=' . $id . '" class="dropdown-item"><i class="mdi mdi-pencil me-1"></i>Edit</a>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<h4 class="mt-0">';
            $html .= '<a href="memo_details.php?id=' . $id . '" class="text-title">' . $subject . '</a>';
            $html .= '</h4>';
            if ($status == 0 || $status == 1) {
                if ($precentage == 0) {
                    $html .= '<div class="badge bg-primary">New</div>';
                } else if ($precentage == 100) {
                    $html .= '<div class="badge bg-success">Finished</div>';
                } else {
                    $html .= '<div class="badge bg-secondary">Ongoing</div>';
                }
            } else {
                $html .= '<div class="badge bg-danger">Declined</div>';
            }

            $html .= '<p class="text-muted font-13 my-3">' . $request . '<a href="memo_details.php?id=' . $id . '" class="fw-bold text-muted">view more</a>';
            $html .= '</p>';
            $html .= '</div>';
            if ($status == 2) {
                $html .= '<ul class="list-group list-group-flush">';
                $html .= '<li class="list-group-item p-3"><br><br>';
                $html .= '<p class="mb-2 fw-bold"> <span class="float-end"></span></p>';
                $html .= '<div class="progress progress-sm">';
                $html .= '<div class="progress-bar" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" >';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</li>';
                $html .= '</ul>';
            } else {
                $html .= '<ul class="list-group list-group-flush">';
                $html .= '<li class="list-group-item p-3">';
                $html .= '<p class="mb-2 fw-bold">Progress <span class="float-end">' . $precentage . '%</span></p>';
                $html .= '<div class="progress progress-sm">';
                $html .= '<div class="progress-bar" role="progressbar" aria-valuenow="' . $precentage . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $precentage . '%;">';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</li>';
                $html .= '</ul>';
            }


            $html .= '</div>';
            $html .= '</div>';
        }
        echo $html;
    }
}

function search_memo_approval($start_date, $end_date, $userid)
{
    global $memo;
    $result = $memo->search_memo_approval($start_date, $end_date, $userid);
    $output = json_decode($result, true);
    $html = "";
    $cnt = 1;
    if ($output == null) {
        echo "<h3><strong>No Records Found</strong></h3>";
    } else {
        foreach ($output as $row) {
            $id = $row['MemoID'];
            $subject = $row['Subject'];
            $date = $row['DateCreated'];
            $dept = $row['Department'];
            $owner = $row['full_name'];
            $precentage = round($row['Percentage'], 0);
            $status = $row['memo_status'];

            $html .= '<tr>';
            $html .= '<td>' . $cnt . '</td>';
            $html .= '<td>' . $date . '</td>';
            $html .= '<td style="display:none;">' . $id . '</td>';
            $html .= '<td>' . $dept . '</td>';
            $html .= '<td>' . $owner . '</td>';
            $html .= '<td>' . $subject . '</td>';

            if ($status == 0 || $status == 1) {
                if ($precentage == 0) {
                    $html .= '<td><div class="mdi mdi-circle text-secondary">New</div></td>';
                } else if ($precentage == 100) {
                    $html .= '<td><div class="mdi mdi-circle text-success">Finished</div></td>';
                } else {
                    $html .= '<td><div class="mdi mdi-circle text-warning">Ongoing</div></td>';
                }
            } else {
                $html .= '<td><div class="mdi mdi-circle text-danger">Declined</div></td>';
            }




            $html .= '<td>';
            $html .= '<a class="btn btn-link btn-lg text-success"  title="Approval" data-id="' . $id . '" data-bs-toggle="modal" data-bs-target="#staticBackdrop2">';
            $html .= '<i class="ri-file-mark-line"></i></a>';
            $html .= '&nbsp;';
            $html .= '<a type="button" class="btn btn-link btn-lg text-primary" onclick="show_details(' . $id . ')" title="Show Details">';
            $html .= '<i class="ri-edit-box-line"></i></a>';
            $html .= '</td>';
            $html .= '</tr>';
            $cnt++;
        }
        echo $html;
    }
}

function check_approval($userid, $memo_id)
{
    global $memo;
    $result = $memo->check_approval($userid, $memo_id);
    return $result;
}

function check_approval_submit($userid, $memo_id)
{
    global $memo;
    $result = $memo->check_approval_submit($userid, $memo_id);
    return $result;
}

function get_memo_details_approval($id)
{
    global $memo;
    $result = $memo->get_memo_details($id);
    $output = json_decode($result, true);
    $html = "";
    foreach ($output as $row) {
        $subject = $row['Subject'];
        $serial_no = $row['SerialNumber'];
        $memo_date = $row['MemoDate'];
        $owner = $row['owner_name'];
        $through = $row['through_name'];
        $recipient = $row['recipient_name'];
        $content = $row['Content'];

        $html .= '<div class="card-body">';
        $html .= '<div class="mt-3">';
        $html .= '<i class="ri-attachment-line"></i><strong>Serial No: ' . $serial_no . '</strong>';
        $html .= '<span style="float: right;"><i class=" ri-time-fill "></i>' . $memo_date . '</span>';
        $html .= '</div>';
        $html .= '<div class="clearfix"></div>';
        $html .= '<h3 class="mt-3">' . $subject . '</h3>';

        if ($through == "") {
            $html .= '<div class="row">';
            $html .= '<div class="col-4">';
            $html .= '    <p class="mt-2 mb-1 text-muted fw-bold font-12 text-uppercase">Created By</p>';
            $html .= '    <div class="d-flex">';
            $html .= '        <i class="ri-account-box-fill font-18 me-1"></i>';
            $html .= '        <div>';
            $html .= '<h5 class="mt-1 font-14">' . $owner . '</h5>';
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
            $html .= '<div class="col-4">';
            $html .= '    <p class="mt-2 mb-1 text-muted fw-bold font-12 text-uppercase">Recipient</p>';
            $html .= '    <div class="d-flex">';
            $html .= '        <i class="ri-account-box-fill font-18 me-1"></i>';
            $html .= '        <div>';
            $html .= '<h5 class="mt-1 font-14">' . $recipient . '</h5>';
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
            $html .= '</div>';
        } else {
            $html .= '<div class="row">';
            $html .= '<div class="col-4">';
            $html .= '    <p class="mt-2 mb-1 text-muted fw-bold font-12 text-uppercase">Created By</p>';
            $html .= '    <div class="d-flex">';
            $html .= '        <i class="ri-account-box-fill font-18 me-1"></i>';
            $html .= '        <div>';
            $html .= '<h5 class="mt-1 font-14">' . $owner . '</h5>';
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
            $html .= '<div class="col-4">';
            $html .= '    <p class="mt-2 mb-1 text-muted fw-bold font-12 text-uppercase">Through</p>';
            $html .= '    <div class="d-flex">';
            $html .= '        <i class="ri-account-box-fill font-18 me-1"></i>';
            $html .= '        <div>';
            $html .= '<h5 class="mt-1 font-14">' . $through . '</h5>';
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
            $html .= '<div class="col-4">';
            $html .= '    <p class="mt-2 mb-1 text-muted fw-bold font-12 text-uppercase">Recipient</p>';
            $html .= '    <div class="d-flex">';
            $html .= '        <i class="ri-account-box-fill font-18 me-1"></i>';
            $html .= '        <div>';
            $html .= '<h5 class="mt-1 font-14">' . $recipient . '</h5>';
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
            $html .= '</div>';
        }
        $html .= '<p class="text-muted mb-4" style="margin-top:-30px; margin-botton:20px;">' . $content . '</p>';
        $html .= '</div>';
    }
    echo $html;
}

function get_invoice_approval_details($id)
{
    global $memo;
    $result = $memo->get_invoice_details($id);
    $output = json_decode($result, true);
    $html = "";

    foreach ($output as $row) {
        $file = substr($row['File'], 0, 10);
        $file_name = $row['File'];
        $file_ext = pathinfo($row['File'], PATHINFO_EXTENSION);
        $id = $row['ID'];

        $html .= '<div class="card my-1 shadow-none border">';
        $html .= '<div class="p-2">';
        $html .= '<div class="row align-items-center">';
        if ($file_ext == "pdf") {
            $html .= '<div class="col-auto">';
            $html .= '<a href="memo_image_upload/invoice/' . $file_name . '" target="_blank"><img src="memo_image_upload/logo/pdf_logo.jpeg" class="avatar-sm rounded" alt="invoice" /></a>';
            $html .= '</div>';
        }
        if ($file_ext == "docx") {
            $html .= '<div class="col-auto">';
            $html .= '<a href="memo_image_upload/invoice/' . $file_name . '" target="_blank"><img src="memo_image_upload/logo/word_logo.png" class="avatar-sm rounded" alt="invoice" /></a>';
            $html .= '</div>';
        }
        if ($file_ext == "jpg" || $file_ext == "jpeg" || $file_ext == "png") {
            $html .= '<div class="col-auto">';
            $html .= '<a href="memo_image_upload/invoice/' . $file_name . '" target="_blank"><img src="memo_image_upload/logo/no_image.jpeg" class="avatar-sm rounded" alt="invoice" /></a>';
            $html .= '</div>';
        }
        $html .= '<div class="col ps-0">';
        $html .= '<a href="memo_image_upload/invoice/' . $file_name . '" target="_blank" class="text-muted fw-bold">' . $file . '.' . $file_ext . '</a>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
    }
    echo $html;
}

function get_final_approval_details($id)
{
    global $memo;
    $result = $memo->get_approval_details($id);
    $output = json_decode($result, true);
    $html = "";
    $cnt = 0;

    foreach ($output as $row) {
        $name = $row['full_name'];
        $title = $row['title'];
        $status = $row['Status'];
        $date = $row['DateUpdated'];
        $level = $row['LevelApproval'];
        $id = $row['ID'];
        $comment = $row['Comments'];

        $html .= '<tr>';
        $html .= '<td>' . $cnt . '</td>';
        $html .= '<td>' . $name . '</td>';
        $html .= '<td style="width: 200px">' . $title . '</td>';
        $html .= '<td>' . $level . '</td>';
        if ($status == 0) {
            $html .= '<td><i class="mdi mdi-circle text-warning"></i>Pending</td>';
        } else {
            $html .= '<td><i class="mdi mdi-circle text-success"></i>Approved</td>';
        }
        $html .= '<td style="width: 100px">' . $date . '</td>';
        $html .= '<td style="width: 100px">' . $comment . '</td>';
        $html .= '</tr>';
        $cnt++;
    }
    echo $html;
}

function get_memo_details($id)
{
    global $memo;
    $result = $memo->get_memo_details($id);
    $output = json_decode($result, true);
    $html = "";
    foreach ($output as $row) {
        $id = base64_encode(base64_encode(base64_encode($row['ID'])));
        $subject = $row['Subject'];
        $serial_no = $row['SerialNumber'];
        $memo_date = $row['MemoDate'];
        $owner = $row['owner_name'];
        $through = $row['through_name'];
        $recipient = $row['recipient_name'];
        $content = $row['Content'];
        $format = $row['Format'];
        $level = $row['Percentage'];

        $html .= '<div class="card-body">';
        $html .= '<div class="dropdown card-widgets">';
        $html .= '<a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">';
        $html .= '<i class="uil uil-ellipsis-h"></i>';
        $html .= '</a>';
        $html .= '<div class="dropdown-menu dropdown-menu-end">';
        $html .= '<a href="memo_edit.php?id=' . $id . '" class="dropdown-item">';
        $html .= '<i class="uil uil-edit me-1"></i>Edit</a>';
        if ($level == 100) {
            if ($format == 'GENERAL') {
                $html .= '<a href="general_format.php?id=' . $id . '" class="dropdown-item" target="_blank">';
                $html .= '<i class="uil uil-file-copy-alt me-1"></i>Generate PDF</a>';
            }
            if ($format == 'FINANCE') {
                $html .= '<a href="finance_format.php?id=' . $id . '" class="dropdown-item" target="_blank">';
                $html .= '<i class="uil uil-file-copy-alt me-1"></i>Generate PDF</a>';
            }
            if ($format == 'PROCESS') {
                $html .= '<a href="process_format.php?id=' . $id . '" class="dropdown-item" target="_blank">';
                $html .= '<i class="uil uil-file-copy-alt me-1"></i>Generate PDF</a>';
            }
        }

        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="mt-3">';
        $html .= '<i class="ri-attachment-line"></i><strong>Serial No: ' . $serial_no . '</strong>';
        $html .= '<span style="float: right;"><i class=" ri-time-fill "></i>' . $memo_date . '</span>';
        $html .= '</div>';
        $html .= '<div class="clearfix"></div>';
        $html .= '<h3 class="mt-3">' . $subject . '</h3>';

        if ($through == "") {
            $html .= '<div class="row">';
            $html .= '<div class="col-4">';
            $html .= '    <p class="mt-2 mb-1 text-muted fw-bold font-12 text-uppercase">Created By</p>';
            $html .= '    <div class="d-flex">';
            $html .= '        <i class="ri-account-box-fill font-18 me-1"></i>';
            $html .= '        <div>';
            $html .= '<h5 class="mt-1 font-14">' . $owner . '</h5>';
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
            $html .= '<div class="col-4">';
            $html .= '    <p class="mt-2 mb-1 text-muted fw-bold font-12 text-uppercase">Recipient</p>';
            $html .= '    <div class="d-flex">';
            $html .= '        <i class="ri-account-box-fill font-18 me-1"></i>';
            $html .= '        <div>';
            $html .= '<h5 class="mt-1 font-14">' . $recipient . '</h5>';
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
            $html .= '</div>';
        } else {
            $html .= '<div class="row">';
            $html .= '<div class="col-4">';
            $html .= '    <p class="mt-2 mb-1 text-muted fw-bold font-12 text-uppercase">Created By</p>';
            $html .= '    <div class="d-flex">';
            $html .= '        <i class="ri-account-box-fill font-18 me-1"></i>';
            $html .= '        <div>';
            $html .= '<h5 class="mt-1 font-14">' . $owner . '</h5>';
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
            $html .= '<div class="col-4">';
            $html .= '    <p class="mt-2 mb-1 text-muted fw-bold font-12 text-uppercase">Through</p>';
            $html .= '    <div class="d-flex">';
            $html .= '        <i class="ri-account-box-fill font-18 me-1"></i>';
            $html .= '        <div>';
            $html .= '<h5 class="mt-1 font-14">' . $through . '</h5>';
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
            $html .= '<div class="col-4">';
            $html .= '    <p class="mt-2 mb-1 text-muted fw-bold font-12 text-uppercase">Recipient</p>';
            $html .= '    <div class="d-flex">';
            $html .= '        <i class="ri-account-box-fill font-18 me-1"></i>';
            $html .= '        <div>';
            $html .= '<h5 class="mt-1 font-14">' . $recipient . '</h5>';
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
            $html .= '</div>';
        }
        $html .= '<p class="text-muted mb-4" style="margin-top:-30px; margin-botton:20px;">' . $content . '</p>';
        $html .= '</div>';
    }
    echo $html;
}

function get_memo_details_edit($data)
{
    global $memo;
    $result = $memo->get_memo_details_edit($data);
    return $result;
}

function get_approval_details($id)
{
    global $memo;
    $result = $memo->get_approval_details($id);
    $output = json_decode($result, true);
    $html = "";
    $cnt = 0;

    foreach ($output as $row) {
        $name = $row['full_name'];
        $title = $row['title'];
        $level = $row['LevelApproval'];
        $id = $row['ID'];
        $userid = $row['UserID'];
        $status = $row['Status'];

        $html .= '<tr>';

        $html .= '<td>' . $cnt . '</td>';
        $html .= '<td>' . $name . '</td>';
        $html .= '<td style="width: 200px">' . $title . '</td>';
        $html .= '<td>' . $level . '</td>';
        if ($status > 0) {
        } else {
            $html .= '<td><a class="btn btn-link btn-lg text-secondary edit_info" data-id="' . $userid . '" data-bs-toggle="modal" title="Edit" data-bs-target="#staticBackdrop">';
            $html .= '<i class=" ri-edit-2-fill"></i></a>&nbsp;';
            $html .= '<a type="button" class="btn btn-link btn-sm text-danger" title="Delete" onclick="delete_approval(' . $id . ')">';
            $html .= '<i class="mdi mdi-delete"></i></a></td>';
        }
        $html .= '</tr>';
        $cnt++;
    }
    echo $html;
}

function add_approval($memo_id, $username, $level)
{
    global $memo;
    $result = $memo->add_approval($memo_id, $username, $level);
    return $result;
}

function get_invoice_details($id)
{
    global $memo;
    $result = $memo->get_invoice_details($id);
    $output = json_decode($result, true);
    $html = "";

    foreach ($output as $row) {
        $file = substr($row['File'], 0, 10);
        $file_name = $row['File'];
        $file_ext = pathinfo($row['File'], PATHINFO_EXTENSION);
        $id = $row['ID'];
        $status = $row['memo_status'];

        $html .= '<div class="card my-1 shadow-none border">';
        $html .= '<div class="p-2">';
        $html .= '<div class="row align-items-center">';
        if ($file_ext == "pdf") {
            $html .= '<div class="col-auto">';
            $html .= '<a href="memo_image_upload/invoice/' . $file_name . '" target="_blank"><img src="memo_image_upload/logo/pdf_logo.jpeg" class="avatar-sm rounded" alt="invoice" /></a>';
            $html .= '</div>';
        }
        if ($file_ext == "docx") {
            $html .= '<div class="col-auto">';
            $html .= '<a href="memo_image_upload/invoice/' . $file_name . '" target="_blank"><img src="memo_image_upload/logo/word_logo.png" class="avatar-sm rounded" alt="invoice" /></a>';
            $html .= '</div>';
        }
        if ($file_ext == "jpg" || $file_ext == "jpeg" || $file_ext == "png") {
            $html .= '<div class="col-auto">';
            $html .= '<a href="memo_image_upload/invoice/' . $file_name . '" target="_blank"><img src="memo_image_upload/logo/no_image.jpeg" class="avatar-sm rounded" alt="invoice" /></a>';
            $html .= '</div>';
        }
        $html .= '<div class="col ps-0">';
        $html .= '<a href="memo_image_upload/invoice/' . $file_name . '" target="_blank" class="text-muted fw-bold">' . $file . '.' . $file_ext . '</a>';
        $html .= '</div>';
        if ($status == 0) {
            $html .= '<div class="col-auto">';
            $html .= '<a type="button" class="btn btn-link btn-lg text-muted" onclick="delete_file(' . $id . ')">';
            $html .= '<i class="mdi mdi-delete"></i>';
            $html .= '</a>';
            $html .= '</div>';
        } else {
        }
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
    }
    echo $html;
}

function delete_invoice($id)
{
    global $memo;
    $result = $memo->delete_invoice($id);
    return $result;
}

function delete_approval($id)
{
    global $memo;
    $result = $memo->delete_approval($id);
    return $result;
}

function approval_table($memo_id)
{
    global $memo;
    $result = $memo->approval_table($memo_id);
    $output = json_decode($result, true);
    $html = "";
    if ($output == null) {
        $html .= '<tr style="mso-yfti-irow: 1; height: 28.15pt;">';
        $html .= '<td style="width: 125.9pt; border: solid windowtext 1.0pt; border-top: none; mso-border-top-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; mso-background-themecolor: accent2; mso-background-themetint: 51; padding: 0in 5.4pt 0in 5.4pt; height: 28.15pt;" width="168">';
        $html .= '<p class="MsoNormal" style="margin-bottom: 2.0pt; text-align: justify; line-height: normal; mso-element: frame; mso-element-frame-hspace: 9.0pt; mso-element-wrap: around; mso-element-anchor-vertical: paragraph; mso-element-anchor-horizontal: margin; mso-element-top: 18.45pt; mso-height-rule: exactly;">';
        $html .= '<span style="font-size: 12pt; font-family: "comic sans ms", sans-serif; color: black;">No Records Found</span></p></td>';
        $html .= '</tr>';
        echo $html;
    } else {
        foreach ($output as $row) {
            $name = $row['full_name'];
            $date = $row['DateUpdated'];
            $signature = $row['Signature'];
            $title = $row['Title'];

            $html .= '<tr style="mso-yfti-irow: 1; height: 28.15pt;">';
            $html .= '<td style="width: 125.9pt; border: solid windowtext 1.0pt; border-top: none; mso-border-top-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; mso-background-themecolor: accent2; mso-background-themetint: 51; padding: 0in 5.4pt 0in 5.4pt; height: 28.15pt;" width="168">';
            $html .= '        <p class="MsoNormal" style="margin-bottom: 2.0pt; text-align: justify; line-height: normal; mso-element: frame; mso-element-frame-hspace: 9.0pt; mso-element-wrap: around; mso-element-anchor-vertical: paragraph; mso-element-anchor-horizontal: margin; mso-element-top: 18.45pt; mso-height-rule: exactly;">';
            $html .= '<span style="font-size: 12pt; font-family: "comic sans ms", sans-serif; color: black;">' . $name . '</span>';
            $html .= '        </p>';
            $html .= '</td>';
            $html .= '<td style="width: 255.15pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; border-bottom: solid windowtext 1.0pt; border-right: solid windowtext 1.0pt;  mso-border-top-alt: solid windowtext .5pt; mso-border-left-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; padding: 0in 5.4pt 0in 5.4pt; height: 28.15pt;" width="340">';
            $html .= '        <p class="MsoNormal" style="margin-bottom: 2.0pt; text-align: justify; line-height: normal; mso-element: frame; mso-element-frame-hspace: 9.0pt; mso-element-wrap: around; mso-element-anchor-vertical: paragraph; mso-element-anchor-horizontal: margin; mso-element-top: 18.45pt; mso-height-rule: exactly;">';
            $html .= '<span style="font-size: 12pt; font-family: "comic sans ms", sans-serif;">' . $title . '</span>';
            $html .= '        </p>';
            $html .= '</td>';
            $html .= '<td style="width: 155.95pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; border-bottom: solid windowtext 1.0pt; border-right: solid windowtext 1.0pt;  mso-border-top-alt: solid windowtext .5pt; mso-border-left-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; padding: 0in 5.4pt 0in 5.4pt; height: 28.15pt;" width="208">';
            $html .= '        <p class="MsoNormal" style="margin-bottom: 2.0pt; text-align: justify; line-height: normal; mso-element: frame; mso-element-frame-hspace: 9.0pt; mso-element-wrap: around; mso-element-anchor-vertical: paragraph; mso-element-anchor-horizontal: margin; mso-element-top: 18.45pt; mso-height-rule: exactly;">';
            $html .= '<span style="font-size: 12pt; font-family: "comic sans ms", sans-serif;"><img src="memo_image_upload/signature/' . $signature . '" width="100%"/></span>';
            $html .= '        </p>';
            $html .= '</td>';
            $html .= '<td style="width: 255.15pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; border-bottom: solid windowtext 1.0pt; border-right: solid windowtext 1.0pt;  mso-border-top-alt: solid windowtext .5pt; mso-border-left-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; padding: 0in 5.4pt 0in 5.4pt; height: 28.15pt;" width="340">';
            $html .= '        <p class="MsoNormal" style="margin-bottom: 2.0pt; text-align: justify; line-height: normal; mso-element: frame; mso-element-frame-hspace: 9.0pt; mso-element-wrap: around; mso-element-anchor-vertical: paragraph; mso-element-anchor-horizontal: margin; mso-element-top: 18.45pt; mso-height-rule: exactly;">';
            $html .= '<span style="font-size: 12pt; font-family: "comic sans ms", sans-serif;">' . $date . '</span>';
            $html .= '        </p>';
            $html .= '</td>';
            $html .= '</tr>';
        }
        echo $html;
    }
}

function concurring_table($memo_id)
{
    global $memo;
    $result = $memo->concurring_table($memo_id);
    $output = json_decode($result, true);
    $html = "";
    if ($output == null) {
        $html .= '<tr style="mso-yfti-irow: 1; height: 28.15pt;">';
        $html .= '<td style="width: 125.9pt; border: solid windowtext 1.0pt; border-top: none; mso-border-top-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; mso-background-themecolor: accent2; mso-background-themetint: 51; padding: 0in 5.4pt 0in 5.4pt; height: 28.15pt;" width="168">';
        $html .= '<p class="MsoNormal" style="margin-bottom: 2.0pt; text-align: justify; line-height: normal; mso-element: frame; mso-element-frame-hspace: 9.0pt; mso-element-wrap: around; mso-element-anchor-vertical: paragraph; mso-element-anchor-horizontal: margin; mso-element-top: 18.45pt; mso-height-rule: exactly;">';
        $html .= '<span style="font-size: 12pt; font-family: "comic sans ms", sans-serif; color: black;">No Records Found</span></p></td>';
        $html .= '</tr>';

        echo $html;
    } else {
        foreach ($output as $row) {
            $name = $row['full_name'];
            $date = $row['DateUpdated'];
            $signature = $row['Signature'];
            $title = $row['Title'];

            $html .= '<tr style="mso-yfti-irow: 1; height: 28.15pt;">';
            $html .= '<td style="width: 125.9pt; border: solid windowtext 1.0pt; border-top: none; mso-border-top-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; mso-background-themecolor: accent2; mso-background-themetint: 51; padding: 0in 5.4pt 0in 5.4pt; height: 28.15pt;" width="168">';
            $html .= '        <p class="MsoNormal" style="margin-bottom: 2.0pt; text-align: justify; line-height: normal; mso-element: frame; mso-element-frame-hspace: 9.0pt; mso-element-wrap: around; mso-element-anchor-vertical: paragraph; mso-element-anchor-horizontal: margin; mso-element-top: 18.45pt; mso-height-rule: exactly;">';
            $html .= '<span style="font-size: 12pt; font-family: "comic sans ms", sans-serif; color: black;">' . $name . '</span>';
            $html .= '        </p>';
            $html .= '</td>';
            $html .= '<td style="width: 255.15pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; border-bottom: solid windowtext 1.0pt; border-right: solid windowtext 1.0pt;  mso-border-top-alt: solid windowtext .5pt; mso-border-left-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; padding: 0in 5.4pt 0in 5.4pt; height: 28.15pt;" width="340">';
            $html .= '        <p class="MsoNormal" style="margin-bottom: 2.0pt; text-align: justify; line-height: normal; mso-element: frame; mso-element-frame-hspace: 9.0pt; mso-element-wrap: around; mso-element-anchor-vertical: paragraph; mso-element-anchor-horizontal: margin; mso-element-top: 18.45pt; mso-height-rule: exactly;">';
            $html .= '<span style="font-size: 12pt; font-family: "comic sans ms", sans-serif;">' . $title . '</span>';
            $html .= '        </p>';
            $html .= '</td>';
            $html .= '<td style="width: 155.95pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; border-bottom: solid windowtext 1.0pt; border-right: solid windowtext 1.0pt;  mso-border-top-alt: solid windowtext .5pt; mso-border-left-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; padding: 0in 5.4pt 0in 5.4pt; height: 28.15pt;" width="208">';
            $html .= '        <p class="MsoNormal" style="margin-bottom: 2.0pt; text-align: justify; line-height: normal; mso-element: frame; mso-element-frame-hspace: 9.0pt; mso-element-wrap: around; mso-element-anchor-vertical: paragraph; mso-element-anchor-horizontal: margin; mso-element-top: 18.45pt; mso-height-rule: exactly;">';
            $html .= '<span style="font-size: 12pt; font-family: "comic sans ms", sans-serif;"><img src="memo_image_upload/signature/' . $signature . '" width="100%"/></span>';
            $html .= '        </p>';
            $html .= '</td>';
            $html .= '<td style="width: 255.15pt; border: solid windowtext 1.0pt; border-top: none; border-left: none; border-bottom: solid windowtext 1.0pt; border-right: solid windowtext 1.0pt;  mso-border-top-alt: solid windowtext .5pt; mso-border-left-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; padding: 0in 5.4pt 0in 5.4pt; height: 28.15pt;" width="340">';
            $html .= '        <p class="MsoNormal" style="margin-bottom: 2.0pt; text-align: justify; line-height: normal; mso-element: frame; mso-element-frame-hspace: 9.0pt; mso-element-wrap: around; mso-element-anchor-vertical: paragraph; mso-element-anchor-horizontal: margin; mso-element-top: 18.45pt; mso-height-rule: exactly;">';
            $html .= '<span style="font-size: 12pt; font-family: "comic sans ms", sans-serif;">' . $date . '</span>';
            $html .= '        </p>';
            $html .= '</td>';
            $html .= '</tr>';
        }
        echo $html;
    }
}

function generate_memo_details($id)
{
    global $memo;
    $result = $memo->generate_memo_details($id);
    return $result;
}

function generate_first_concurring($id)
{
    global $memo;
    $result = $memo->generate_first_concurring($id);
    return $result;
}

function get_staff_department($department)
{
    global $memo;
    $result = $memo->get_staff_department($department);
    $output = json_decode($result, true);
    $html = "";
    foreach ($output as $row) {
        $id = $row['UserID'];
        $fullname = $row['full_name'];

        $html .= '<option value = ' . $id . '>' . $fullname . '</option>';
    }
    echo $html;
}

function update_approval($approval_id, $memo_id, $userid, $level)
{
    global $memo;
    $result = $memo->update_approval($approval_id, $memo_id, $userid, $level);
    return $result;
}

function submit_approval($memo_id, $userid, $status, $comment)
{
    global $memo;
    $result = $memo->submit_approval($memo_id, $userid, $status, $comment);
    return $result;
}

function update_memo_status($id)
{
    global $memo;
    $result = $memo->update_memo_status($id);
    return $result;
}

function get_memo_status($id)
{
    global $memo;
    $result = $memo->get_memo_status($id);
    return $result;
}

function get_total_memo($department)
{
    global $memo;
    $result = $memo->get_total_memo($department);
    return $result;
}

function total_approved_memo($department)
{
    global $memo;
    $result = $memo->total_approved_memo($department);
    return $result;
}

function total_rejected_memo($department)
{
    global $memo;
    $result = $memo->total_rejected_memo($department);
    return $result;
}

function total_ongoing_memo($department)
{
    global $memo;
    $result = $memo->total_ongoing_memo($department);
    return $result;
}

function memo_percentage($department)
{
    global $memo;
    $result = $memo->memo_percentage($department);
    return $result;
}

function memo_report($department)
{
    global $memo;
    $result = $memo->memo_report($department);
    $output = json_decode($result, true);
    $html = "";
    foreach ($output as $row) {
        $total_time = $row['time_spent'];
        $fullname = $row['next_approval'];
        $date_created = $row['DateCreated'];
        $subject = $row['Subject'];
        $status = $row['Status'];

        $html .= '<tr>';
        $html .= '<td>';
        $html .= '<h5 class="font-14 my-1"><a href="javascript:void(0);" class="text-body">' . $subject . '</a></h5>';
        $html .= '<span class="text-muted font-13">created' . $date_created . '</span>';
        $html .= '</td>';
        if ($status == 0) {
            $html .= '<td>';
            $html .= '<span class="text-muted font-13">Status</span> <br />';
            $html .= '<span class="badge badge-warning-lighten">In-progress</span>';
            $html .= '</td>';
        }
        if ($status == 1) {
            $html .= '<td>';
            $html .= '<span class="text-muted font-13">Status</span> <br />';
            $html .= '<span class="badge badge-success-lighten">Approved</span>';
            $html .= '</td>';
        }
        if ($status == 1) {
            $html .= '<td>';
            $html .= '<span class="text-muted font-13">Approved by</span>';
            $html .= '<h5 class="font-14 mt-1 fw-normal">CEO</h5>';
            $html .= '</td>';
            $html .= '<td>';
        } else {
            $html .= '<td>';
            $html .= '<span class="text-muted font-13">Assigned to</span>';
            $html .= '<h5 class="font-14 mt-1 fw-normal">' . $fullname . '</h5>';
            $html .= '</td>';
            $html .= '<td>';
        }

        $html .= '<span class="text-muted font-13">Total time spend</span>';
        $html .= '<h5 class="font-14 mt-1 fw-normal">' . $total_time . ' Days</h5>';
        $html .= '</td>';
        $html .= '</tr>';
    }
    echo $html;
}

switch ($_POST['action']) {

    case 'login': {
            echo login($_POST['username'], $_POST['password']);
            break;
        }

    case 'get_memo_format': {
            echo get_memo_format();
            break;
        }

    case 'get_concurring_details': {
            echo get_concurring_details($_POST['id']);
            break;
        }

    case 'get_concurring_staff': {
            echo get_concurring_staff($_POST['username']);
            break;
        }

    case 'get_concurring_staff1': {
            echo get_concurring_staff1();
            break;
        }

    case 'get_concurring_staff_title': {
            echo get_concurring_staff_title($_POST['userid']);
            break;
        }

    case 'get_approval_user_detail': {
            echo get_approval_user_detail($_POST['userid']);
            break;
        }

    case 'get_dept_code': {
            echo get_dept_code($_POST['department']);
            break;
        }

    case 'get_serial_no': {
            echo get_serial_no($_POST['department']);
            break;
        }

    case 'insert_memo': {
            echo insert_memo($_POST['data']);
            break;
        }

    case 'update_memo': {
            echo update_memo($_POST['data']);
            break;
        }

    case 'insert_approval': {
            echo insert_approval($_POST['data']);
            break;
        }

    case 'search_memo': {
            echo search_memo($_POST['start_date'], $_POST['end_date'], $_POST['department'], $_POST['username']);
            break;
        }

    case 'search_memo_approval': {
            echo search_memo_approval($_POST['start_date'], $_POST['end_date'], $_POST['username']);
            break;
        }

    case 'check_approval': {
            echo check_approval($_POST['username'], $_POST['memo_id']);
            break;
        }

    case 'check_approval_submit': {
            echo check_approval_submit($_POST['username'], $_POST['memo_id']);
            break;
        }

    case 'get_memo_details': {
            echo get_memo_details($_POST['memo_id']);
            break;
        }

    case 'get_memo_details_edit': {
            echo get_memo_details_edit($_POST['edit_id']);
            break;
        }

    case 'get_memo_details_approval': {
            echo get_memo_details_approval($_POST['memo_id']);
            break;
        }

    case 'get_invoice_approval_details': {
            echo get_invoice_approval_details($_POST['memo_id']);
            break;
        }

    case 'get_final_approval_details': {
            echo get_final_approval_details($_POST['memo_id']);
            break;
        }

    case 'get_approval_details': {
            echo get_approval_details($_POST['memo_id']);
            break;
        }

    case 'update_approval': {
            echo update_approval($_POST['approval_id'], $_POST['memo_id'], $_POST['userid'], $_POST['level']);
            break;
        }

    case 'submit_approval': {
            echo submit_approval($_POST['memo_id'], $_POST['userid'], $_POST['status'], $_POST['comments']);
            break;
        }

    case 'add_approval': {
            echo add_approval($_POST['memo_id'], $_POST['username'], $_POST['approval_level']);
            break;
        }

    case 'get_invoice_details': {
            echo get_invoice_details($_POST['memo_id']);
            break;
        }

    case 'delete_invoice': {
            echo delete_invoice($_POST['id']);
            break;
        }

    case 'delete_approval': {
            echo delete_approval($_POST['id']);
            break;
        }

    case 'concurring_table': {
            echo concurring_table($_POST['memo_id']);
            break;
        }

    case 'approval_table': {
            echo approval_table($_POST['memo_id']);
            break;
        }

    case 'generate_memo_details': {
            echo generate_memo_details($_POST['memo_id']);
            break;
        }

    case 'generate_first_concurring': {
            echo generate_first_concurring($_POST['memo_id']);
            break;
        }

    case 'get_staff_department': {
            echo get_staff_department($_POST['department']);
            break;
        }

    case 'update_memo_status': {
            echo update_memo_status($_POST['memo_id']);
            break;
        }

    case 'get_memo_status': {
            echo get_memo_status($_POST['memo_id']);
            break;
        }

    case 'get_total_memo': {
            echo get_total_memo($_POST['department']);
            break;
        }

    case 'total_approved_memo': {
            echo total_approved_memo($_POST['department']);
            break;
        }

    case 'total_rejected_memo': {
            echo total_rejected_memo($_POST['department']);
            break;
        }

    case 'total_ongoing_memo': {
            echo total_ongoing_memo($_POST['department']);
            break;
        }

    case 'memo_percentage': {
            echo memo_percentage($_POST['department']);
            break;
        }

    case 'memo_report': {
            echo memo_report($_POST['department']);
            break;
        }
}
