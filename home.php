<?php

include "auth/authorize.php";

$database = new Database();
$database = $database->getConnection();

?>
<!DOCTYPE html>
<html lang="en" data-layout-mode="detached">

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

    <!-- Plugin css -->
    <link href="assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />

    <!-- Theme Config Js -->
    <script src="assets/js/hyper-config.js"></script>

    <!-- App css -->
    <link href="assets/css/app-saas.min.css" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

</head>

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
                                        <li class="breadcrumb-item active">Dashboard</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">MEMO PORTAL</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <input type="hidden" id="department" value="<?php echo $_SESSION['department'] ?>">
                    <div class="row">
                        <div class="col-12">
                            <div class="card widget-inline">
                                <div class="card-body p-0">
                                    <div class="row g-0">
                                        <div class="col-sm-6 col-lg-3">
                                            <div class="card rounded-0 shadow-none m-0">
                                                <div class="card-body text-center">
                                                    <i class="ri-briefcase-line text-muted font-24"></i>
                                                    <h3 id="total_memo_section"><span></span></h3>
                                                    <p class="text-muted font-15 mb-0">Total Memos</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-lg-3">
                                            <div class="card rounded-0 shadow-none m-0 border-start border-light">
                                                <div class="card-body text-center">
                                                    <i class="ri-list-check-2 text-success font-24"></i>
                                                    <h3 id="total_approved_memo_section" class="text-success"><span></span></h3>
                                                    <p class="text-success font-15 mb-0">Approved Memos</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-lg-3">
                                            <div class="card rounded-0 shadow-none m-0 border-start border-light">
                                                <div class="card-body text-center">
                                                    <i class="ri-list-check-2 text-danger font-24"></i>
                                                    <h3 id="total_rejected_memo_section" class="text-danger"><span></span></h3>
                                                    <p class="text-danger font-15 mb-0">Rejected Memos</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-lg-3">
                                            <div class="card rounded-0 shadow-none m-0 border-start border-light">
                                                <div class="card-body text-center">
                                                    <i class="ri-list-check-2 text-primary font-24"></i>
                                                    <h3 id="total_ongoing_memo_section" class="text-primary"><span></span></h3>
                                                    <p class="text-primary font-15 mb-0">Ongoing Memos</p>
                                                </div>
                                            </div>
                                        </div>

                                    </div> <!-- end row -->
                                </div>
                            </div> <!-- end card-box-->
                        </div> <!-- end col-->
                    </div>
                    <!-- end row-->


                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="header-title">Memo Status</h4>
                                </div>

                                <div class="card-body pt-0">
                                    <div class="mt-3 mb-4 chartjs-chart" style="height: 204px;" id="chart_display">
                                        <canvas id="project-status-chart" data-colors="#0acf97,#727cf5,#fa5c7c"></canvas>
                                    </div>

                                    <div class="row text-center mt-2 py-2">
                                        <div class="col-sm-4">
                                            <div class="my-2 my-sm-0">
                                                <i class="mdi mdi-trending-up text-success mt-3 h3"></i>
                                                <h3 class="fw-normal" id="percentage_approved">
                                                    <span></span>
                                                </h3>
                                                <p class="text-muted mb-0">Completed</p>
                                            </div>

                                        </div>
                                        <div class="col-sm-4">
                                            <div class="my-2 my-sm-0">
                                                <i class="mdi mdi-trending-down text-primary mt-3 h3"></i>
                                                <h3 class="fw-normal" id="percentage_ongoing">
                                                    <span>%</span>
                                                </h3>
                                                <p class="text-muted mb-0"> In-progress</p>
                                            </div>

                                        </div>
                                        <div class="col-sm-4">
                                            <div class="my-2 my-sm-0">
                                                <i class="mdi mdi-trending-down text-danger mt-3 h3"></i>
                                                <h3 class="fw-normal" id="percentage_reject">
                                                    <span>%</span>
                                                </h3>
                                                <p class="text-muted mb-0"> Declined</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end row-->

                                </div> <!-- end card body-->
                            </div> <!-- end card -->
                        </div><!-- end col-->

                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="header-title">Memo Report</h4>
                                </div>
                                <div class="card-header bg-light-lighten border-top border-bottom border-light py-1 text-center">
                                    <p class="m-0"><b id="total_approved_memo_section1"></b> memo completed out of <span id="total_memo_section1"></span></p>
                                </div>
                                <div class="card-body pt-2">
                                    <div class="table-responsive">
                                        <table class="table table-centered table-nowrap table-hover mb-0">
                                            <tbody id="memo_report_section">

                                            </tbody>
                                        </table>
                                    </div> <!-- end table-responsive-->

                                </div> <!-- end card body-->
                            </div> <!-- end card -->
                        </div><!-- end col-->
                    </div>
                    <!-- end row-->

                </div> <!-- container -->

            </div> <!-- content -->

            <!-- Footer Start -->
            <?php include "common/footer.php"; ?>
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- Bootstrap Datepicker js -->
    <script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

    <!-- Chart js -->
    <script src="assets/vendor/chart.js/chart.min.js"></script>

    <!-- Projects Analytics Dashboard App js -->
    <script src="assets/js/pages/demo.dashboard-projects.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>
    <script src="action.js"></script>
    <script>
        $(document).ready(function() {

            var department = $('#department').val();

            $.ajax({
                type: "POST",
                url: "process.php",
                data: {
                    action: "get_total_memo",
                    department: department,
                },
                success: function(response) {

                    var response = JSON.parse(response);
                    if (response) {
                        $('#total_memo_section').html(response[0]['total_memo']);
                        $('#total_memo_section1').html(response[0]['total_memo']);
                    } else {
                        $("#total_memo_section").html("0");
                    }

                }

            });

            $.ajax({
                type: "POST",
                url: "process.php",
                data: {
                    action: "total_approved_memo",
                    department: department,
                },
                success: function(response) {

                    var response = JSON.parse(response);
                    if (response) {
                        $('#total_approved_memo_section').html(response[0]['total_memo_approved']);
                        $('#total_approved_memo_section1').html(response[0]['total_memo_approved']);
                    } else {
                        $("#total_approved_memo_section").html("0");
                    }

                }

            });

            $.ajax({
                type: "POST",
                url: "process.php",
                data: {
                    action: "total_rejected_memo",
                    department: department,
                },
                success: function(response) {

                    var response = JSON.parse(response);
                    if (response) {
                        $('#total_rejected_memo_section').html(response[0]['total_memo_rejected']);
                    } else {
                        $("#total_rejected_memo_section").html("0");
                    }

                }

            });

            $.ajax({
                type: "POST",
                url: "process.php",
                data: {
                    action: "total_ongoing_memo",
                    department: department,
                },
                success: function(response) {

                    var response = JSON.parse(response);
                    if (response) {
                        $('#total_ongoing_memo_section').html(response[0]['total_memo_ongoing']);
                    } else {
                        $("#total_ongoing_memo_section").html("0");
                    }

                }

            });

            $.ajax({
                type: "POST",
                url: "process.php",
                data: {
                    action: "memo_percentage",
                    department: department,
                },
                success: function(response) {

                    var response = JSON.parse(response);

                    if (response[0]['total'] == 0) {
                        var ongoing = 0;
                        var reject = 0;
                        var approved = 0;
                    } else {
                        var ongoing = Math.round(response[0]['total_memo_ongoing'] / response[0]['total'] * 100);
                        var reject = Math.round(response[0]['total_memo_rejected'] / response[0]['total'] * 100);
                        var approved = Math.round(response[0]['total_memo_approved'] / response[0]['total'] * 100);
                    }

                    if (response) {
                        $('#percentage_ongoing').html(ongoing + '%');
                        $('#percentage_reject').html(reject + '%');
                        $('#percentage_approved').html(approved + '%');
                    } else {
                        $("#percentage_ongoing").html("0%");
                        $("#percentage_reject").html("0%");
                        $("#percentage_approved").html("0%");
                    }

                }

            });

            $.ajax({
                type: "POST",
                url: "process.php",
                data: {
                    action: "memo_report",
                    department: department,
                },
                success: function(response) {
                    console.log(response);
                    $('#memo_report_section').html(response);
                }

            });
        })
    </script>

</body>

</html>