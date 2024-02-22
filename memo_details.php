<?php

include "auth/authorize.php";

$database = new Database();
$database = $database->getConnection();

isValidId(base64_decode(base64_decode(base64_decode($_GET['id']))));

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Memo Portal | Page Finance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />

    <!-- App favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicons/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicons/favicon.ico">
    <link rel="manifest" href="assets/images/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="assets/images/favicons/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">

    <!-- Theme Config Js -->
    <script src="assets/js/hyper-config.js"></script>

    <!-- App css -->
    <link href="assets/css/app-saas.min.css" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Plugins css -->
    <link href="assets/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css" />

    <!-- Icons css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>
<style>
    p {
        font-family: 'Futura' !important;
    }

    span {
        font-family: 'Futura' !important;
    }
</style>

<body>

    <!-- Pre-loader -->
    <div id="preloader">
        <div id="status">
            <div class="bouncing-loader">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
    <!-- End Preloader-->
    <!-- Begin page -->
    <div class="wrapper">

        <!-- ========== Topbar Start ========== -->
        <?php include "common/header.php"; ?>
        <!-- ========== Topbar End ========== -->

        <!-- ========== Left Sidebar Start ========== -->
        <?php include "common/sidebar.php"; ?>
        <!-- ========== Left Sidebar End ========== -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->


        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Memo Portal</a></li>
                                        <li class="breadcrumb-item active">Memo Detail</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Memo Detail</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <input type="hidden" id="memo_id" value="<?php echo base64_decode(base64_decode(base64_decode($_GET['id']))); ?>">
                    <div class="row">
                        <div class="col-xxl-8 col-xl-7">
                            <div class="card d-block" id="memo_detail_section">

                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Concurring List </h4>
                                    <br>
                                    <button type="button" class="btn btn-outline-info" style="margin-bottom: 10px; float:right;" data-bs-toggle="modal" title="Edit" data-bs-target="#staticBackdrop1" id="add_concurring"><i class="mdi mdi-plus-thick"></i> Add </button>

                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="table-head-preview" role="tabpanel">
                                            <div class="table-responsive-sm">
                                                <table class="table table-centered mb-0">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Name</th>
                                                            <th>Title</th>
                                                            <th>Level</th>
                                                            <th id="action">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="concurring_section">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="col-xxl-4 col-xl-5">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Invoice Upload</h5>
                                    <form action="#" class="dropzone">
                                        <div class="fallback">
                                            <input name="file" type="file" id="invoice_file" style="display: none;" />
                                        </div>
                                        <div class="dz-message needsclick">
                                            <div class="mb-3">
                                                <i class="h3 text-muted ri-upload-cloud-2-line"></i>
                                            </div>
                                            <h4>Drop files here or click to upload.</h4>
                                        </div>
                                    </form>

                                    <div id="invoice_section"></div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div> <!-- container -->

            </div> <!-- content -->

            <!-- Footer Start -->
            <?php include "common/footer.php"; ?>
            <!-- end Footer -->

        </div>

        <!-- Modal -->
        <div class="modal fade insert" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Edit Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div> <!-- end modal header -->
                    <div class="modal-body">
                        <form id="edit-details">
                            <input type="hidden" name="id" id="approval_id">
                            <input type="hidden" name="memo_id" id="memo_id">
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">UserID</label>
                                <select class="form-select mb-3" name="UserID" id="UserID" onchange="getTitle(this)">
                                    <option value="">Choose User</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="Title" placeholder="Title">
                            </div>
                            <div class="mb-3">
                                <label for="LevelApproval" class="form-label">Approval Level</label>
                                <input type="text" class="form-control" id="LevelApproval" name="LevelApproval" placeholder="Approval Level">
                            </div>
                            <button type="submit" class="btn btn-secondary" style="float: right;" id="update_approval">Update</button>
                        </form>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div> <!-- end modal footer -->
                </div> <!-- end modal content-->
            </div> <!-- end modal dialog-->
        </div> <!-- end modal-->

        <!-- Modal -->
        <div class="modal fade add" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Add Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label for="username" class="form-label">UserID</label>
                                <select class="form-select mb-3" id="username" onchange="getTitle(this)">
                                    <option value="">Choose User</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="add_title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="add_title" placeholder="Title">
                            </div>
                            <div class="mb-3">
                                <label for="approval_level" class="form-label">Approval Level</label>
                                <p style="margin-top: -10px;"><i class="text-danger">Enable you edit the approval level of other users to make it sequencial (i.e, 1,2,3,...)</i></p>
                                <input type="number" class="form-control" id="approval_level" placeholder="Approval Level">
                            </div>
                            <button type="submit" class="btn btn-success" style="float: right;" id="add_approval">Add User</button>
                        </form>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

        <!-- END wrapper -->

        <!-- plugin js -->
        <script src="assets/libs/dropzone/min/dropzone.min.js"></script>

        <!-- init js -->
        <script src="assets/js/ui/component.fileupload.js"></script>

        <!-- Vendor js -->
        <script src="assets/js/vendor.min.js"></script>

        <!-- App js -->
        <script src="assets/js/app.min.js"></script>
        <script src="action.js"></script>
        <script>
            $(document).ready(function() {
                var memo_id = $('#memo_id').val();

                $.ajax({
                    type: "POST",
                    url: "process.php",
                    data: {
                        action: "get_memo_details",
                        memo_id: memo_id
                    },
                    success: function(response) {
                        $('#memo_detail_section').html(response);
                    }
                });

                $.ajax({
                    type: "POST",
                    url: "process.php",
                    data: {
                        action: "get_approval_details",
                        memo_id: memo_id
                    },
                    success: function(response) {
                        $('#concurring_section').html(response);
                    }
                });

                $.ajax({
                    type: "POST",
                    url: "process.php",
                    data: {
                        action: "get_invoice_details",
                        memo_id: memo_id
                    },
                    success: function(response) {
                        $('#invoice_section').html(response);
                    }
                });

                $.ajax({
                    type: "POST",
                    url: "process.php",
                    data: {
                        action: "get_memo_status",
                        memo_id: memo_id
                    },
                    success: function(response) {
                        var response = JSON.parse(response);
                        console.log(response);
                        if (response[0]['Status'] > 0 ) {
                            $('#add_concurring').css('display', 'none');
                            $('#action').css('display', 'none');

                        }

                    }
                });

            });
        </script>

        <script type="text/javascript">
            Dropzone.autoDiscover = false;

            $(function() {
                var memo_id = $('#memo_id').val();
                //Dropzone class
                var myDropzone = new Dropzone(".dropzone", {
                    url: "invoice_insert.php",
                    paramName: "invoice_update",
                    params: {
                        memo_id
                    },
                    maxFilesize: 20,
                    maxFiles: 1,
                    acceptedFiles: ".pdf, image/jpeg, image/png, image/gif, image/*, application/pdf, .doc, .docx",
                    init: function() {


                        this.on("success", function(file, response) {
                            toastr["success"]('Invoice Loaded Successfully');
                            window.location.reload();
                        });

                        this.on("error", function(file, response) {
                            toastr["error"](response);
                            myDropzone.removeFile(file);
                        });
                    },

                });
            });
        </script>
</body>

</html>