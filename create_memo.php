<?php

include "auth/authorize.php";

$database = new Database();
$database = $database->getConnection();

formatID(base64_decode(base64_decode(base64_decode($_GET['format_id']))));

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
    <style>
        div.some-class {
            display: inline-block;
            width: 45%;
        }

        #result {
            display: flex;
            gap: 10px;
            padding: 10px 0;
        }

        .thumbnail {
            height: 300px;
        }
    </style>
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

                    <div class="row" id="concurr_section">
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="concurring_number" class="form-label">No of Concurring</label>
                                <input type="number" id="concurring_number" min="1" max="7" class="form-control form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3" style="margin-top: 30px;">
                                <a class="btn btn-primary" type="submit" id="generate_section">Create</a>
                            </div>
                        </div>
                    </div>

                    <div id="memo_section" style="display: none;">

                        <input type="hidden" id="format_id" value="<?php echo base64_decode(base64_decode(base64_decode($_GET['format_id']))); ?>">
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
                                                                <input type="date" class="form-control form-control-sm" id="date_created" data-date-today-highlight="true" placeholder="Current Date">
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
                                    <div class="card-body pt-1">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <br>
                                                <h4 class="header-title">Concurring Section</h4>
                                                <br>
                                                <div class="tab-content">
                                                    <div class="tab-pane show active" id="-preview" role="tabpanel">
                                                        <div class="row" id="concurring_section">
                                                        </div>
                                                        <!-- end row -->
                                                    </div> <!-- end preview-->
                                                </div> <!-- end tab-content-->

                                            </div> <!-- end card-body -->
                                        </div>
                                    </div>

                                </div> <!-- end card-->
                            </div> <!-- end col -->
                        </div>


                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Invoice Details</h4>
                                        <br>
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="input-types-preview">
                                                <div class="row">
                                                    <div class="col-sm-10">
                                                        <form id="uploadForm">
                                                            <div class="col-sm-6">
                                                                <p>Can upload more than 1 file</p>
                                                                <input class="form-control form-control-sm" type="file" name="invoice[]" id="invoice" multiple="">
                                                                <br>
                                                                <output id="result">
                                                            </div>
                                                        </form>
                                                    </div>

                                                </div>
                                                <!-- end row-->
                                            </div> <!-- end preview-->

                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                                                <input type="text" id="subject" name="subject" class="form-control form-control-sm" placeholder="Subject">

                                                            </div>
                                                            <div class="mb-3" id="background_section">
                                                                <label for="example-input-large" class="form-label" style="font-size: larger;"><strong>Content:</strong></label>
                                                                <textarea class="form-control" id="file-picker" rows="5" spellcheck="false"></textarea>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="mb-3">
                                                        <a class="btn btn-primary" type="submit" style="float: right;" id="create_memo">Create Memo</a>
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

    <!-- <script type="text/javascript">
        document.querySelector("#invoice").addEventListener("change", (e) => {
            if (window.File && window.FileReader && window.FileList && window.Blob) {
                const files = e.target.files;
                const output = document.querySelector("#result");
                output.innerHTML = "";

                for (let i = 0; i < files.length; i++) {
                    if (!files[i].type.match("image")) continue;
                    const picReader = new FileReader();
                    picReader.addEventListener("load", function(event) {
                        const picFile = event.target;
                        const div = document.createElement("div");
                        const width = 50
                        div.innerHTML =
                            `<img class="thumbnail" src="${picFile.result}" title="${picFile.name}"/>`;
                        output.appendChild(div);
                    });
                    picReader.readAsDataURL(files[i]);
                }
            } else {
                alert("Your browser does not support File API");
            }
        });
    </script> -->
</body>

</html>