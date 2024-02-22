<?php

include "auth/authorize.php";

$database = new Database();
$database = $database->getConnection();

isValidId(base64_decode(base64_decode(base64_decode($_GET['id']))));

?>
<html lang="en">

<head>
        <meta charset="utf-8" />
        <title>Memo Portal | Memo PDF</title>
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

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <!-- App css -->
        <link href="assets/css/pdf.css" rel="stylesheet" type="text/css" id="app-style" />

        <!-- Toastr -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <style>
                p {
                        font-family: 'Futura' !important;
                }

                span {
                        font-family: 'Futura' !important;
                }
        </style>
</head>

<body>
        <div style="margin: 20px;">
        <input type="hidden" id="memo_id" value="<?php echo base64_decode(base64_decode(base64_decode($_GET['id']))); ?>">
                <p class="MsoNormal" style="text-align: justify; line-height: normal;"><span style="font-size: 10.0pt; font-family: 'Century Gothic',sans-serif; color: black; mso-themecolor: text1; mso-ansi-language: EN-US; mso-no-proof: yes;"><span style="font-family: 'comic sans ms', sans-serif; font-size: 12pt;"><img src="assets/memo.png" alt="E98E4F92-A180-4FE4-9441-D51AA7CC7C98@pagemfbank" width="163" height="71"></span></span><span style="font-size: 12pt; font-family: 'comic sans ms', sans-serif; color: black;"><span style="mso-spacerun: yes;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;
                                </span></span>
                                <span style="font-family: 'Futura';"> <span style="mso-spacerun: yes;"></span><span style="mso-spacerun: yes;"> &nbsp;</span><span style="font-size: 6.5pt;"><span style="mso-spacerun: yes;" id="memo_code">
                                        </span></span></span>
                </p>

                <div style="mso-element: para-border-div; border-top: double gray 1.0pt; border-left: none; border-bottom: double gray 1.0pt; border-right: none; mso-border-top-alt: double gray .75pt; mso-border-bottom-alt: double gray .75pt; padding: 8.0pt 0in 4.0pt 0in;">
                        <p class="MsoNormal" style="mso-margin-top-alt: auto; margin-bottom: 2.15pt; text-align: justify; line-height: normal; border: none; mso-border-top-alt: double gray .75pt; mso-border-bottom-alt: double gray .75pt; padding: 0in; mso-padding-alt: 8.0pt 0in 4.0pt 0in;">
                                <span style="font-size: 12pt; font-family: 'comic sans ms', sans-serif; color: black;">INTEROFFICE
                                        MEMO</span>
                        </p>
                </div>
                <p class="MsoNormal" style="text-align: justify; line-height: 1;"><span style="font-size: 12pt; font-family: 'comic sans ms', sans-serif; color: black;">Date:<span style="mso-tab-count: 2;">
                                </span><span id="memo_date"></span></span></p>
                <p class="MsoNormal" style="text-align: justify; line-height: normal;font-family: 'comic sans ms', sans-serif; line-height: 1; margin-top: -10px;">
                        To:&nbsp;&nbsp; <span id="recipient_name"></span> &ndash;
                        <span id="recipient_title"></span>&nbsp;&nbsp;&nbsp;<span style="font-size: 12pt; font-family: 'comic sans ms', sans-serif; color: black;"><span style="mso-spacerun: yes;">
                                </span></span></p>
                <p class="MsoNormal" style="text-align: justify; line-height: normal; line-height: 1; margin-top: -10px;"><span style="font-size: 12pt; font-family: 'comic sans ms', sans-serif; color: black;">Thru:&nbsp;&nbsp;<span id="through_name"></span>
                                 &ndash; <span id="through_title"></span></span></p>
                <p class="MsoNormal" style="text-align: justify; line-height: normal; line-height: 1; margin-top: -10px;"><span style="font-size: 12pt; font-family: 'comic sans ms', sans-serif; color: black;">Prepared by:<span style="mso-spacerun: yes;">&nbsp; </span><span style="mso-tab-count: 1;">
                                </span><span id="owner"></span> &ndash; <span id="department"></span></span></p>
                <div style="mso-element: para-border-div; border: none; border-bottom: solid windowtext 1.5pt; padding: 0in 0in 1.0pt 0in;">
                        <p class="MsoNormal" style="text-align: justify; line-height: normal; line-height: 1; margin-top: -10px; border: none; mso-border-bottom-alt: solid windowtext 1.5pt; padding: 0in; mso-padding-alt: 0in 0in 1.0pt 0in;">
                                <span style="font-family: 'comic sans ms', sans-serif; font-size: 12pt;"><span style="color: black;">Subject:<span style="mso-tab-count: 1;">&nbsp;&nbsp;
                                                </span></span><span lang="EN-GB" style="color: black;" id="subject"></span></span>
                        </p>
                </div>

                <p class="MsoNormal" style="margin-bottom: 2.0pt; text-align: justify; line-height: normal;"><span style="font-family: 'comic sans ms', sans-serif; font-size: 12pt;"><strong><span lang="EN-GB">&nbsp;</span></strong></span></p>

                <p class="MsoNormal" style="text-align: justify;" id="content"><span style="font-family: 'comic sans ms', sans-serif;"></span></p>

                <p class="MsoNormal" style="margin-bottom: 0in; text-align: justify; line-height: normal;"><span style="font-size: 12pt; font-family: 'comic sans ms', sans-serif;">&nbsp;</span></p>

                <p class="MsoNormal" style="margin-bottom: 0.1n; text-align: justify; line-height: 1;"><span style="font-family: 'comic sans ms', sans-serif; font-size: 12pt;"><strong>CONCURRENCE</strong><strong><span lang="EN-GB"><span style="mso-spacerun: yes;">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></strong></span><span style="font-family: 'comic sans ms', sans-serif; font-size: 12pt;"><strong><span lang="EN-GB"><span style="mso-spacerun: yes;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                </span></span></strong></span></p>

                <table class="MsoTableGrid" style="width: 537.0pt; border-collapse: collapse; border: none; mso-border-alt: solid windowtext .5pt; mso-yfti-tbllook: 1184; mso-table-lspace: 9.0pt; margin-left: 6.75pt; mso-table-rspace: 9.0pt; margin-right: 6.75pt; mso-table-anchor-vertical: paragraph; mso-table-anchor-horizontal: margin; mso-table-left: left; mso-table-top: 18.45pt; mso-padding-alt: 0in 5.4pt 0in 5.4pt;" border="1" width="716" cellspacing="0" cellpadding="0" align="left">
                        <thead>
                                <tr style="mso-yfti-irow: 0; mso-yfti-firstrow: yes; height: 4.85pt;">
                                        <td style="width: 125.9pt; border: solid windowtext 1.0pt; mso-border-alt: solid windowtext .5pt; background: #ED7D31; mso-background-themecolor: accent2; padding: 0in 5.4pt 0in 5.4pt; height: 4.85pt;" width="168">
                                                <p class="MsoNormal" style="margin-bottom: 2.0pt; text-align: justify; line-height: normal; mso-element: frame; mso-element-frame-hspace: 9.0pt; mso-element-wrap: around; mso-element-anchor-vertical: paragraph; mso-element-anchor-horizontal: margin; mso-element-top: 18.45pt; mso-height-rule: exactly;">
                                                        <span style="font-family: 'Futura'; font-size: 12pt;"><strong><span style="color: black;">Name</span></strong></span>
                                                </p>
                                        </td>
                                        <td style="width: 255.15pt; border: solid windowtext 1.0pt; border-left: none; mso-border-left-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; background: #ED7D31; mso-background-themecolor: accent2; padding: 0in 5.4pt 0in 5.4pt; height: 4.85pt;" width="340">
                                                <p class="MsoNormal" style="margin-bottom: 2.0pt; text-align: justify; line-height: normal; mso-element: frame; mso-element-frame-hspace: 9.0pt; mso-element-wrap: around; mso-element-anchor-vertical: paragraph; mso-element-anchor-horizontal: margin; mso-element-top: 18.45pt; mso-height-rule: exactly;">
                                                        <span style="font-family: 'Futura'; font-size: 12pt;"><strong><span style="color: black;">Designation</span></strong></span>
                                                </p>
                                        </td>
                                        <td style="width: 155.95pt; border: solid windowtext 1.0pt; border-left: none; mso-border-left-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; background: #ED7D31; mso-background-themecolor: accent2; padding: 0in 5.4pt 0in 5.4pt; height: 4.85pt;" width="208">
                                                <p class="MsoNormal" style="margin-bottom: 2.0pt; text-align: justify; line-height: normal; mso-element: frame; mso-element-frame-hspace: 9.0pt; mso-element-wrap: around; mso-element-anchor-vertical: paragraph; mso-element-anchor-horizontal: margin; mso-element-top: 18.45pt; mso-height-rule: exactly;">
                                                        <span style="font-family: 'Futura'; font-size: 12pt;"><strong><span style="color: black;">Signature</span></strong></span>
                                                </p>
                                        </td>
                                        <td style="width: 155.95pt; border: solid windowtext 1.0pt; border-left: none; mso-border-left-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; background: #ED7D31; mso-background-themecolor: accent2; padding: 0in 5.4pt 0in 5.4pt; height: 4.85pt;" width="208">
                                                <p class="MsoNormal" style="margin-bottom: 2.0pt; text-align: justify; line-height: normal; mso-element: frame; mso-element-frame-hspace: 9.0pt; mso-element-wrap: around; mso-element-anchor-vertical: paragraph; mso-element-anchor-horizontal: margin; mso-element-top: 18.45pt; mso-height-rule: exactly;">
                                                        <span style="font-family: 'Futura'; font-size: 12pt;"><strong><span style="color: black;">
                                                                                Date</span></strong></span>
                                                </p>
                                        </td>
                                </tr>
                        </thead>
                        <tbody id="concurring_table_section">

                        </tbody>
                </table>
                <p class="MsoNormal" style="margin-bottom: 2.0pt; text-align: justify; line-height: normal;"><span style="font-size: 12pt; font-family: 'Futura';">&nbsp;</span></p>
                <p class="MsoNormal" style="margin-bottom: 0.1in; text-align: justify; line-height: 1;"><span style="font-family: 'Futura'; font-size: 12pt;"><strong>APPROVAL</strong><strong><span lang="EN-GB"><span style="mso-spacerun: yes;">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></strong></span><span style="font-family: 'Futura'; font-size: 12pt;"><strong><span lang="EN-GB"><span style="mso-spacerun: yes;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                </span></span></strong></span></p>

                <table class="MsoTableGrid" style="width: 537.0pt; border-collapse: collapse; border: none; mso-border-alt: solid windowtext .5pt; mso-yfti-tbllook: 1184; mso-table-lspace: 9.0pt; margin-left: 6.75pt; mso-table-rspace: 9.0pt; margin-right: 6.75pt; mso-table-anchor-vertical: paragraph; mso-table-anchor-horizontal: margin; mso-table-left: left; mso-table-top: 23.9pt; mso-padding-alt: 0in 5.4pt 0in 5.4pt;" border="1" width="716" cellspacing="0" cellpadding="0" align="left">
                        <thead>
                                <tr style="mso-yfti-irow: 0; mso-yfti-firstrow: yes; height: 4.85pt;">
                                        <td style="width: 125.9pt; border: solid windowtext 1.0pt; mso-border-alt: solid windowtext .5pt; background: #ED7D31; mso-background-themecolor: accent2; padding: 0in 5.4pt 0in 5.4pt; height: 4.85pt;" width="168">
                                                <p class="MsoNormal" style="margin-bottom: 0in; text-align: justify; line-height: normal; mso-element: frame; mso-element-frame-hspace: 9.0pt; mso-element-wrap: around; mso-element-anchor-vertical: paragraph; mso-element-anchor-horizontal: margin; mso-element-top: 23.9pt; mso-height-rule: exactly;">
                                                        <span style="font-size: 12pt; font-family: 'Futura';"><strong><span style="color: black;">Name</span></strong></span>
                                                </p>
                                        </td>
                                        <td style="width: 241.0pt; border: solid windowtext 1.0pt; border-left: none; mso-border-left-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; background: #ED7D31; mso-background-themecolor: accent2; padding: 0in 5.4pt 0in 5.4pt; height: 4.85pt;" width="321">
                                                <p class="MsoNormal" style="margin-bottom: 0in; text-align: justify; line-height: normal; mso-element: frame; mso-element-frame-hspace: 9.0pt; mso-element-wrap: around; mso-element-anchor-vertical: paragraph; mso-element-anchor-horizontal: margin; mso-element-top: 23.9pt; mso-height-rule: exactly;">
                                                        <span style="font-size: 12pt; font-family: 'Futura';"><strong><span style="color: black;">Designation
                                                                        </span></strong></span>
                                                </p>
                                        </td>
                                        <td style="width: 170.1pt; border: solid windowtext 1.0pt; border-left: none; mso-border-left-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; background: #ED7D31; mso-background-themecolor: accent2; padding: 0in 5.4pt 0in 5.4pt; height: 4.85pt;" width="227">
                                                <p class="MsoNormal" style="margin-bottom: 0in; text-align: justify; line-height: normal; mso-element: frame; mso-element-frame-hspace: 9.0pt; mso-element-wrap: around; mso-element-anchor-vertical: paragraph; mso-element-anchor-horizontal: margin; mso-element-top: 23.9pt; mso-height-rule: exactly;">
                                                        <span style="font-size: 12pt; font-family: 'Futura';"><strong><span style="color: black;">Signature</span></strong></span>
                                                </p>
                                        </td>
                                        <td style="width: 170.1pt; border: solid windowtext 1.0pt; border-left: none; mso-border-left-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; background: #ED7D31; mso-background-themecolor: accent2; padding: 0in 5.4pt 0in 5.4pt; height: 4.85pt;" width="227">
                                                <p class="MsoNormal" style="margin-bottom: 0in; text-align: justify; line-height: normal; mso-element: frame; mso-element-frame-hspace: 9.0pt; mso-element-wrap: around; mso-element-anchor-vertical: paragraph; mso-element-anchor-horizontal: margin; mso-element-top: 23.9pt; mso-height-rule: exactly;">
                                                        <span style="font-size: 12pt; font-family: 'Futura';"><strong><span style="color: black;">
                                                                                Date</span></strong></span>
                                                </p>
                                        </td>
                                </tr>
                        </thead>
                        <tbody id="approval_table_section">
                        </tbody>
                </table>

        </div>
        <script src="action.js"></script>
        <script>
                $(document).ready(function() {
                        var memo_id = $('#memo_id').val();
                        $.ajax({
                                type: "POST",
                                url: "process.php",
                                data: {
                                        action: "concurring_table",
                                        memo_id: memo_id,
                                },
                                success: function(response) {
                                        $.ajax({
                                                type: "POST",
                                                url: "process.php",
                                                data: {
                                                        action: "approval_table",
                                                        memo_id: memo_id,
                                                },
                                                success: function(response1) {
                                                        $('#concurring_table_section').html(response);
                                                        $('#approval_table_section').html(response1);
                                                }
                                        });
                                }
                        });

                        $.ajax({
                                type: "POST",
                                url: "process.php",
                                data: {
                                        action: "generate_memo_details",
                                        memo_id: memo_id,
                                },
                                success: function(response) {
                                        $.ajax({
                                                type: "POST",
                                                url: "process.php",
                                                data: {
                                                        action: "generate_first_concurring",
                                                        memo_id: memo_id,
                                                },
                                                success: function(response1) {
                                                        result1 = JSON.parse(response1);
                                                        $('#owner').html(result1[0]['owner_name']);
                                                }
                                        });
                                        result = JSON.parse(response);
                                        console.log(result);
                                        $('#memo_code').html(result[0]['MemoCode']);
                                        $('#memo_date').html(result[0]['MemoDate']);
                                        $('#recipient_name').html(result[0]['recipient_name']);
                                        $('#recipient_title').html(result[0]['recipient_title']);
                                        $('#subject').html(result[0]['Subject']);
                                        $('#content').html(result[0]['Content']);
                                        $('#department').html(result[0]['Position']);
                                        $('#through_name').html(result[0]['through_name']);
                                        $('#through_title').html(result[0]['through_title']);
                                }
                        });

                });
        </script>
</body>

</html>