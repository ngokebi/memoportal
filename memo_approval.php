<?php

include "auth/authorize.php";

$database = new Database();
$database = $database->getConnection();

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
    <link href="assets/plugins/summernote/dist/summernote-bs4.css" rel="stylesheet" />

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
                                        <li class="breadcrumb-item active">Memo Approval</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Memo Approval</h4>
                            </div>
                        </div>
                    </div>

                    <!-- end page title -->

                    <div class="row">
                        <input type="hidden" id="username" value="<?php echo $_SESSION['username']; ?>">
                        <div class="col-2">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" id="start_date" class="form-control form-control">
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" id="end_date" class="form-control form-control">
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="mb-3" style="margin-top: 30px;">
                                <a class="btn btn-primary" type="submit" id="memo_search_approval">Search</a>
                            </div>
                        </div>
                    </div>

                    <table class="table table-centered mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Dept.</th>
                                <th>Created By</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="search_approval_content">
                        </tbody>
                    </table>
                    <br>
                    <div class="row" style="display: none;" id="show_memo_details">
                        <div class="col-xxl-8 col-xl-7">
                            <div class="card d-block" id="memo_detail_section">
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Invoice Section </h4>
                                    <br>
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="table-head-preview" role="tabpanel">
                                            <div class="card d-block" id="invoice_section">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Concurring List </h4>
                                    <br>
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
                                                            <th>Status</th>
                                                            <th>Date Approved</th>
                                                            <th>Comment</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="final_concurring_section_approval">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade insert" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Approval Section</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                </div> <!-- end modal header -->
                                <div class="modal-body">
                                    <form id="approve-details">
                                        <div class="mb-3">
                                            <input type="hidden" name="memo_id" id="memo_id">
                                            <input type="hidden" id="username" value="<?php echo $_SESSION['username']; ?>">
                                            <label for="approval_status" class="form-label">Status</label>
                                            <select class="form-select mb-3" id="approval_status" name="approval_status">
                                                <option value="">Choose Status</option>
                                                <option value="2">Reject</option>
                                                <option value="1">Approve</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="signature" class="form-label">Signature</label>
                                            <input class="form-control" type="file" id="signature" onchange="mainthumbUrl(this)">
                                            <span class="font-13 text-danger">if your signture is saved on the database, no need to upload it again.</span>
                                            <br>
                                            <img src="" id="mainThumb" width="40%" style="margin-bottom: 5px" />
                                        </div>
                                        <div class="mb-3">
                                            <label for="comment" class="form-label">Comment</label>
                                            <textarea class="summernote form-control" id="comment" name="comment" placeholder="Comment..."></textarea>
                                        </div>
                                        <div id="update_approval_final">
                                            <button type="submit" class="btn btn-success" style="float: right;" id="update_approval_button">Update</button>
                                        </div>
                                    </form>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                </div> <!-- end modal footer -->
                            </div> <!-- end modal content-->
                        </div> <!-- end modal dialog-->
                    </div> <!-- end modal-->


                </div> <!-- container -->

            </div> <!-- content -->

            <!-- Footer Start -->
            <?php include "common/footer.php"; ?>
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

        <!-- END wrapper -->

        <!-- Vendor js -->
        <script src="assets/js/vendor.min.js"></script>

        <!-- plugin js -->
        <script src="assets/libs/dropzone/min/dropzone.min.js"></script>

        <!-- init js -->
        <script src="assets/js/ui/component.fileupload.js"></script>

        <script src="assets/plugins/summernote/dist/summernote-bs4.min.js"></script>

        <!-- App js -->
        <script src="assets/js/app.min.js"></script>
        <script src="action.js"></script>

        <script>
            $(document).ready(function() {

                $('.summernote').summernote({
                    height: 150, // set editor height
                    minHeight: null, // set minimum height of editor
                    maxHeight: null, // set maximum height of editor
                    focus: false // set focus to editable area after initializing summernote
                });
            });
        </script>
        <script type="text/javascript">
            function mainthumbUrl(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#mainThumb').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                };
            };
        </script>
</body>

</html>