<?php

include "auth/authorize.php";

$database = new Database();
$database = $database->getConnection();

isValidId(base64_decode(base64_decode(base64_decode($_GET['id']))));

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

    <!-- Bootstrap Datepicker css -->
    <link href="assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />

    <!-- Icons css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/96mnsk10pkg2eoqov5j9uvwckxdsvqkplraribk3dkypc1fi/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

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
                                        <li class="breadcrumb-item active">Formats</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Create Memo</h4>
                            </div>
                        </div>
                    </div>


                    <div id="memo_section">

                        <input type="hidden" id="edit_id" value="<?php echo base64_decode(base64_decode(base64_decode($_GET['id']))); ?>">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Basic Info</h4>
                                        <br>
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="input-types-preview">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <form>
                                                            <div class="mb-3">
                                                                <label for="serial_no" class="form-label">Serial Number</label>
                                                                <div id="serial_no_section"></div>
                                                            </div>
                                                            <div id="concurring_no_section" style="display: none;"></div>
                                                            <div class="mb-3">
                                                                <label for="dept_code" class="form-label">Department Code</label>
                                                                <div id="dept_code_section"></div>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="owner" class="form-label">Owner</label>
                                                                <input type="text" id="owner" name="owner" class="form-control form-control-sm" placeholder="UserID" value="<?php echo $_SESSION['username']; ?>" disabled>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="recipient" class="form-label">Recipient</label>
                                                                <select class="form-select form-select-sm mb-3" id="recipient">
                                                                    <option value="">Choose User</option>
                                                                </select>
                                                            </div>
                                                        </form>
                                                    </div>


                                                    <div class="col-sm-6">
                                                        <form>
                                                            <div class="mb-3">
                                                                <label for="memo_code" class="form-label">Memo Code</label>
                                                                <div id="memo_code_section"></div>
                                                            </div>
                                                            <div class="mb-3 position-relative" id="datepicker1">
                                                                <label class="form-label">Date</label>
                                                                <div id="date_section"></div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="department" class="form-label">Department </label>
                                                                <input type="text" id="department" name="department" class="form-control form-control-sm" placeholder="Department" value="<?php echo $_SESSION['department']; ?>" disabled>
                                                            </div>

                                                            <div class="mb-3" id="thro_section">
                                                                <label for="through" class="form-label">Through</label>
                                                                <select class="form-select form-select-sm mb-3" id="through">
                                                                    <option value="">Choose User</option>
                                                                </select>
                                                            </div>
                                                        </form>
                                                    </div>

                                                </div>
                                                <!-- end row-->
                                            </div> <!-- end preview-->

                                        </div> <!-- end tab-content-->
                                    </div> <!-- end card-body -->
                                </div> <!-- end card -->
                            </div><!-- end col -->
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Memo Details</h4>
                                        <br>
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="input-types-preview">
                                                <div class="row">
                                                    <div class="col-sm-10">
                                                        <form>
                                                            <div class="mb-3" id="subject_section">
                                                                <label for="example-input-large" class="form-label" style="font-size: larger;"><strong>Subject:</strong></label>
                                                                <div id="subject_section"></div>

                                                            </div>
                                                            <div class="mb-3" id="background_section">
                                                                <label for="example-input-large" class="form-label" style="font-size: larger;"><strong>Content:</strong></label>
                                                                <textarea class="form-control" id="file-picker" rows="5" spellcheck="false"></textarea>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="mb-3">
                                                        <a class="btn btn-primary" type="submit" style="float: right;" id="update_memo">Update Memo</a>
                                                    </div>
                                                </div>
                                                <!-- end row-->
                                            </div> <!-- end preview-->

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

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

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>
    <script src="action.js"></script>

    <script>
        $(document).ready(function() {


            var edit_id = $('#edit_id').val();
            var department = $('#department').val();

            const d = new Date();
            let day = ('0' + d.getDate()).slice(-2);
            let month = ('0' + (d.getMonth() + 1)).slice(-2);
            let year = d.getFullYear();

            $.ajax({
                type: "POST",
                url: "process.php",
                data: {
                    action: "get_memo_details_edit",
                    edit_id: edit_id
                },
                success: function(response) {
                    response = JSON.parse(response);

                    $('#serial_no_section').html(`<input type="text" id="serial_no" name="serial_no" value="${response[0]['SerialNumber']}" class="form-control form-control-sm" placeholder="Serial Number" disabled>`);
                    $('#dept_code_section').html(`<input type="text" id="dept_code" name="dept_code" value="${response[0]['DepartmentCode']}" class="form-control form-control-sm" placeholder="Dept. Code" disabled>`);
                    $('#memo_code_section').html(`<input type="text" id="memo_code" name="memo_code" value="${response[0]['MemoCode']}" class="form-control form-control-sm" placeholder="Memo Code" disabled>`);
                    $('#date_section').html(`<input type="date" class="form-control form-control-sm" value="${response[0]['MemoDate']}" id="date_created" data-date-today-highlight="true" placeholder="Current Date">`);
                    $('#subject_section').html(`<input type="text" id="subject" value="${response[0]['Subject']}" name="subject" class="form-control form-control-sm" placeholder="Subject">`);
                    $('#file-picker').append(`${response[0]['Content']}`);
                    tinyMCE.activeEditor.setContent(response[0]['Content']);

                    $.ajax({
                        type: "POST",
                        url: "process.php",
                        data: {
                            action: "get_concurring_staff1"
                        },
                        success: function(response1) {

                            $(`#recipient`).html(`
                                <option value = "">Select User</option>
                                <option></option>
                                <option value="${response[0]['Recipient']}" selected>${response[0]['recipient_name']}</option>
                                ${response1}
                            `);
                            if (response[0]['Through'] == "") {
                                $(`#through`).html(`
                                <option value = "">Select User</option>
                                <option></option>
                                ${response1}
                            `);
                            } else {
                                $(`#through`).html(`
                                <option value = "">Select User</option>
                                <option></option>
                                <option value="${response[0]['Through']}" selected>${response[0]['through_name']}</option>
                                ${response1}
                            `);
                            }


                        }
                    });
                }
            });




        });
    </script>

    <script>
        tinymce.init({
            selector: 'textarea#file-picker',
            setup: function(editor) {
                editor.on('init change', function() {
                    editor.save();
                });
            },
            plugins: 'image code preview importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap emoticons',
            toolbar: 'undo redo | link image | code | bold italic underline strikethrough | fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile media anchor codesample | ltr rtl',
            image_title: true,
            content_style: " body { font-family: comic sans ms; }",
            images_upload_url: 'upload_image.php',
            automatic_uploads: true,
            image_advtab: true,
            relative_urls: false,
            convert_urls: false,
            file_picker_types: 'image'
        });
    </script>

</body>

</html>