toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": true,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "2000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}

// Login

$("#login_submit").click(function (e) {

    e.preventDefault();

    // username required
    var username = $('#username').val();
    if (username == "") {
        toastr['warning']('Username is Required');
        $('input#username').focus();
        return false;
    }

    // password required
    var password = $('#password').val();
    if (password == '') {
        toastr['warning']('Password is Required');
        $('input#password').focus();
        return false;
    }


    $.ajax({
        type: "POST",
        url: "../process.php",
        data: {
            action: "login",
            username: username,
            password: password
        },
        beforeSend: function () {
            $("#login").val("Processing...");
        },
        success: function (response) {
            if (response == true) {

                toastr['success']('Login Successful');
                const RedirectURL = setTimeout(redirect, 3000);

                function redirect() {
                    $(location).attr('href', '../home.php');
                }

            } else {
                toastr['warning'](response);
                $("#login").val('Log in');
            }
        }
    });
});

// Insert Memo
$('#create_memo').click(function (e) {

    e.preventDefault();

    var formData = new FormData($("#uploadForm")[0]);
    var format_id = $('#format_id').val();

    var through = $('#through option:selected').val();
    if (through == "") {
        through = "";
    };

    var department = $('#department').val();
    var memo_code = $('#memo_code').val();
    var dept_code = $('#dept_code').val();
    var serial_no = $('#serial_no').val();
    var owner = $('#owner').val();
    var recipient = $('#recipient option:selected').val();

    var user_name = $('#user1 option:selected').val();
    if (user_name == "") {
        toastr['warning']("First Concurring is Required");
        $('input#user1').focus();
        return false;
    };


    // Date Required
    var date = $('#date_created').val();
    if (date == "") {
        toastr['warning']("Date is Required");
        $('input#date').focus();
        return false;
    };

    // Recipient Required
    var recipient = $('#recipient option:selected').val();
    if (recipient == "") {
        toastr['warning']("Recipient is Required");
        $('input#recipient').focus();
        return false;
    };

    // Position Required
    var position = $('#role1').val();
    if (position == "") {
        toastr['warning']("Position is Required");
        $('input#role1').focus();
        return false;
    };

    var user_name1 = $('#userid2 option:selected').val();
    var user_name2 = $('#userid3 option:selected').val();
    var user_name3 = $('#userid4 option:selected').val();
    var user_name4 = $('#userid5 option:selected').val();
    var user_name5 = $('#userid6 option:selected').val();
    var user_name6 = $('#userid7 option:selected').val();
    var user_name7 = $('#userid8 option:selected').val();
    var user_name8 = $('#userid9 option:selected').val();
    var user_name9 = $('#userid0 option:selected').val();

    if (user_name6 == "" || user_name1 == "" || user_name2 == "" || user_name3 == "" || user_name4 == "" || user_name5 == "" || user_name7 == "" || user_name8 == "" || user_name9 == "") {
        toastr['warning']("Concurring User Details is Required");
        return false;
    };

    // Subject Required
    var subject = $('#subject').val();
    if (subject == "") {
        toastr['warning']("Subject is Required");
        $('input#subject').focus();
        return false;
    };

    // Content Required
    var content = $('#file-picker').val();
    if (content == "") {
        toastr['warning']("Content is Required");
        $('textarea#file-picker').focus();
        return false;
    };

    var playdata = [];

    var data = {
        department,
        format_id,
        memo_code,
        dept_code,
        serial_no,
        owner,
        through,
        recipient,
        date,
        content,
        subject,
        position,
    }
    playdata.push(data);

    var concurr_array = [];

    var concurring = {
        user_name,
        user_name1,
        user_name2,
        user_name3,
        user_name4,
        user_name5,
        user_name6,
        user_name7,
        user_name8,
        user_name9,
    }
    concurr_array.push(concurring);

    var final_data = JSON.parse(JSON.stringify(playdata));
    var final_approval = JSON.parse(JSON.stringify(concurring));

    $.ajax({
        type: "POST",
        url: "process.php",
        data: {
            action: "insert_memo",
            data: final_data
        },
        beforeSend: function () {
            $("#create_memo").val("Processing...");
        },
        success: function (response) {

            if (response == true) {

                $.ajax({
                    url: "invoice_insert.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {

                        $.ajax({
                            type: "POST",
                            url: "process.php",
                            data: {
                                action: "insert_approval",
                                data: final_approval
                            },
                            success: function (response1) {
                                if (response1 == true) {
                                    toastr['success']('Memo Created Successfully');

                                    const RedirectURL = setTimeout(redirect, 3000);

                                    function redirect() {
                                        $(location).attr('href', 'home.php');
                                    }
                                } else {
                                    toastr['error'](response);
                                    $("#create_memo").val("Create Memo");
                                }
                            }
                        });
                    }
                });
            }
        }
    });


});

// Edit Memo
$('#update_memo').click(function (e) {

    e.preventDefault();

    var edit_id = $('#edit_id').val();

    var through = $('#through option:selected').val();
    if (through == "") {
        through = "";
    };

    // Date Required
    var date = $('#date_created').val();
    if (date == "") {
        toastr['warning']("Date is Required");
        $('input#date').focus();
        return false;
    };

    // Recipient Required
    var recipient = $('#recipient option:selected').val();
    if (recipient == "") {
        toastr['warning']("Recipient is Required");
        $('input#recipient').focus();
        return false;
    };

    // Subject Required
    var subject = $('#subject').val();
    if (subject == "") {
        toastr['warning']("Subject is Required");
        $('input#subject').focus();
        return false;
    };

    // Content Required
    var content = $('#file-picker').val();
    if (content == "") {
        toastr['warning']("Content is Required");
        $('textarea#file-picker').focus();
        return false;
    };

    var playdata = [];

    var data = {
        edit_id,
        through,
        recipient,
        date,
        content,
        subject,
    }
    playdata.push(data);

    var final_data = JSON.parse(JSON.stringify(playdata));

    $.ajax({
        type: "POST",
        url: "process.php",
        data: {
            action: "update_memo",
            data: final_data
        },
        beforeSend: function () {
            $("#update_memo").val("Processing...");
        },
        success: function (response) {

            if (response == true) {
                toastr['success']('Memo Updated Successfully');

                const RedirectURL = setTimeout(redirect, 3000);

                function redirect() {
                    $(location).attr('href', 'memo_list.php');
                }
            } else {
                toastr['error'](response);
                $("#update_memo").val("Update Memo");
            }
        }
    });
});


// Search Memo
$('#memo_search').click(function (e) {
    e.preventDefault();

    var start_date = $('#start_date').val();
    if (start_date == "") {
        toastr['warning']('Start Date is Required');
        return false
    }

    var end_date = $('#end_date').val();
    if (end_date == "") {
        toastr['warning']('End Date is Required');
        return false
    }

    var department = $('#department').val();
    var username = $('#username').val();

    $.ajax({
        type: "POST",
        url: "process.php",
        data: {
            action: "search_memo",
            start_date: start_date,
            end_date: end_date,
            department: department,
            username: username
        },
        beforeSend: function () {
            $('#memo_search').val('Processing...');
        },
        success: function (response) {
            $('#search_content').html(response);
        }
    })
})

// Delete Invoice
function delete_file(id) {
    var confirm_delete = confirm('Are you sure you want to delete?');
    if (confirm_delete == true) {
        $.ajax({
            type: "POST",
            url: "process.php",
            data: {
                action: "delete_invoice",
                id: id
            },
            success: function (response) {
                if (response == true) {
                    toastr["success"]("Invoice Deleted Successfully");
                    window.location.reload();
                } else if (response == false) {
                    toastr["error"]("Error, Something went Wrong");

                }
            },
        });
    };
}

// Edit Approval
$(document).delegate("[data-bs-target='#staticBackdrop']", "click", function () {

    $('.insert').modal('show');
    var userid = $(this).attr('data-id');

    $.ajax({
        type: "POST",
        url: "process.php",
        data: {
            action: "get_approval_user_detail",
            userid: userid
        },
        success: function (response) {
            $.ajax({
                type: "POST",
                url: "process.php",
                data: {
                    action: "get_concurring_staff1"
                },
                success: function (response1) {
                    $(`#UserID`).html(`
                        <option value = "">Select User</option>
                        <option value="${response[0].UserID}" selected>${response[0].full_name}</option>
                        ${response1}
                    `);
                }
            });
            response = JSON.parse(response);
            $("#edit-details [name=\"id\"]").val(response[0].ID);
            $("#edit-details [name=\"memo_id\"]").val(response[0].MemoID);
            $("#edit-details [name=\"Title").val(response[0].title);
            $("#edit-details [name=\"LevelApproval").val(response[0].LevelApproval);
        }
    });

});

// Update Approval
$("#update_approval").click(function (e) {

    e.preventDefault();

    var approval_id = $("#approval_id").val();
    var memo_id = $("#memo_id").val();

    //userid required
    var userid = $("#UserID option:selected").val();
    if (userid == "") {
        toastr["warning"]('UserID is required');
        $("input#UserID").focus();
        return false;
    }

    // level required
    var level = $("input#LevelApproval").val();
    if (level == "") {
        toastr["warning"]('Level is required');
        $("input#LevelApproval").focus();
        return false;
    }

    $.ajax({
        type: "POST",
        url: "process.php",
        data: {
            action: "update_approval",
            approval_id: approval_id,
            memo_id: memo_id,
            userid: userid,
            level: level

        },
        beforeSend: function () {
            $("#update_approval").val("Processing...");
        },
        success: function (response) {
            if (response == true) {
                $('.modal').modal('hide');
                window.location.reload();
                toastr["success"]("Approval Details Updated");

            } else if (response == false) {
                toastr["error"]("Error, Something Went Wrong!!!!");
                $("#update_approval").val("Update");
            }
        },
    });

});

// Add Approval
$(document).delegate("[data-bs-target='#staticBackdrop1']", "click", function () {

    $('.add').modal('show');

    $.ajax({
        type: "POST",
        url: "process.php",
        data: {
            action: "get_concurring_staff1"
        },
        success: function (response) {
            $(`#username`).html(`
                        <option value = "">Select User</option>
                        <option></option>
                        ${response}
                    `);
        }

    });
});

// Delete Approvals
function delete_approval(id) {
    var confirm_delete = confirm('Are you sure you want to delete?');
    if (confirm_delete == true) {
        $.ajax({
            type: "POST",
            url: "process.php",
            data: {
                action: "delete_approval",
                id: id
            },
            success: function (response) {
                if (response == true) {
                    toastr["success"]("Approval Deleted Successfully");
                    window.location.reload();
                } else if (response == false) {
                    toastr["error"]("Error, Something went Wrong");

                }
            },
        });
    };
}

// Change Title
function getTitle(element) {
    var userid = element.value;

    $.ajax({
        type: "POST",
        url: "process.php",
        data: {
            action: "get_concurring_staff_title",
            userid: userid
        },
        success: function (response) {
            var title = JSON.parse(response);
            $('#title').val(title[0]['Title']);
            $('#add_title').val(title[0]['Title']);
        },
    });
}

// Add Approval
$("#add_approval").click(function (e) {

    e.preventDefault();

    var memo_id = $('#memo_id').val();

    //userid required
    var username = $("#username option:selected").val();
    if (username == "") {
        toastr["warning"]('UserID is required');
        $("input#UserID").focus();
        return false;
    }

    // level required
    var approval_level = $("input#approval_level").val();
    if (approval_level == "") {
        toastr["warning"]('Level is required');
        $("input#approval_level").focus();
        return false;
    }

    if (approval_level == 0) {
        toastr["warning"]('Level cant be same with Owner');
        $("input#approval_level").focus();
        return false;
    }

    $.ajax({
        type: "POST",
        url: "process.php",
        data: {
            action: "add_approval",
            memo_id: memo_id,
            username: username,
            approval_level: approval_level

        },
        beforeSend: function () {
            $("#add_approval").val("Processing...");
        },
        success: function (response) {
            if (response == true) {
                $('.add').modal('hide');
                window.location.reload();
                toastr["success"]("Approval Details Added");

            } else {
                toastr["error"](response);
                $("#add_approval").val("Add User");
            }
        },
    });

});

// Search Memo Approval
$('#memo_search_approval').click(function (e) {
    e.preventDefault();

    var start_date = $('#start_date').val();
    if (start_date == "") {
        toastr['warning']('Start Date is Required');
        return false
    }

    var end_date = $('#end_date').val();
    if (end_date == "") {
        toastr['warning']('End Date is Required');
        return false
    }

    var username = $('#username').val();

    $.ajax({
        type: "POST",
        url: "process.php",
        data: {
            action: "search_memo_approval",
            start_date: start_date,
            end_date: end_date,
            username: username
        },
        beforeSend: function () {
            $('#memo_search_approval').val('Processing...');
        },
        success: function (response) {
            $('#search_approval_content').html(response);
        }
    })
});

// Show Memo Details Approval
function show_details(memo_id) {

    $.ajax({
        type: "POST",
        url: "process.php",
        data: {
            action: "get_memo_details_approval",
            memo_id: memo_id
        },
        success: function (response) {
            $('#show_memo_details').css("display", "block");
            $('#memo_detail_section').html(response);

            $.ajax({
                type: "POST",
                url: "process.php",
                data: {
                    action: "get_invoice_approval_details",
                    memo_id: memo_id
                },
                success: function (response) {
                    $('#invoice_section').html(response);
                }
            });

            $.ajax({
                type: "POST",
                url: "process.php",
                data: {
                    action: "get_final_approval_details",
                    memo_id: memo_id
                },
                success: function (response) {
                    $('#final_concurring_section_approval').html(response);
                }
            });

        },
    });

}

// Signature
function get_signature(memo_id) {
    $.ajax({
        type: "POST",
        url: "process.php",
        data: {
            action: "concurring_table",
            memo_id: memo_id,
        },
        success: function (response) {
            $.ajax({
                type: "POST",
                url: "process.php",
                data: {
                    action: "approval_table",
                    memo_id: memo_id,
                },
                success: function (response1) {
                    $('#concurring_table_section').append(response);
                    $('#approval_table_section').html(response1);
                }
            });
        }
    });
};

function getUsername() {
    var username = $("#user1 option:selected").val();
    var concurring_number = $("input#concurring_number").val();

    $.ajax({
        type: "POST",
        url: "process.php",
        data: {
            action: "get_concurring_staff",
            username: username,
        },
        success: function (response) {

            for (i = 2; i <= concurring_number; i++) {

                $(`#userid` + `${parseInt(i)}`).html(`
                        <option value = "">Select User</option>
                        <option></option>
                        ${response}
                    `);
            }
        }
    });

}

// Memo Section
$("#generate_section").click(function (e) {

    e.preventDefault();

    var concurring_number = $('#concurring_number').val();
    var format_id = $('#format_id').val();
    var department = $('#department').val();
    var user_name = $('#user1 option:selected').val();


    //concurring_no required
    var concurring_number = $("input#concurring_number").val();

    if (concurring_number == "") {
        toastr["warning"]('Number is required');
        $("input#UserID").focus();
        return false;
    }

    if (concurring_number < 1) {
        toastr["warning"]('Positive Number is required');
        $("input#UserID").focus();
        return false;
    }

    const d = new Date();
    let day = ('0' + d.getDate()).slice(-2);
    let month = ('0' + (d.getMonth() + 1)).slice(-2);
    let year = d.getFullYear();

    var i = 0;

    for (i = 1; i <= concurring_number; i++) {
        if (i == 1)
            $("#concurring_section").after(`<div class="col-md-4 some-class" style="margin-bottom: 10px; margin-right: 10px;" id="concurring_section1">
                                        <div class="card mb-md-0 mb-3">
                                            <div class="card-body">
                                                <div class="card-widgets">
                                                    <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                                                        aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                                                </div>
                                                <h5 class="card-title mb-0">Owner Details</h5>

                                                <div id="cardCollpase1" class="collapse pt-3 show">
                                                    <div class="tab-content">
                                                        <div class="tab-pane show active" id="basic-form-preview" role="tabpanel">
                                                            <form>
                                                                <div class="mb-3">
                                                                <select class="form-select mb-3" id="user1" onchange="getUsername()">
                                                                                <option value="">Choose User</option>
                                                                            </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <input type="text" class="form-control" id="role1" placeholder="Position or Department">
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`);
        else {
            var div = parseInt(i) - 1;
            $("#concurring_section" + div).after(`<div class="col-md-4 some-class" style="margin-bottom: 10px; margin-right: 10px;" id="concurring_section${parseInt(i)}">
                                                <div class="card mb-md-0 mb-3">
                                                    <div class="card-body">
                                                        <div class="card-widgets">
                                                            <a data-bs-toggle="collapse" href="#cardCollpase${parseInt(i)}" role="button" aria-expanded="false"
                                                                aria-controls="cardCollpase${parseInt(i)}"><i class="mdi mdi-minus"></i></a>
                                                        </div>
                                                        <h5 class="card-title mb-0">Concurring User Details</h5>

                                                        <div id="cardCollpase${parseInt(i)}" class="collapse pt-3 show">
                                                            <div class="tab-content">
                                                                <div class="tab-pane show active" id="basic-form-preview" role="tabpanel">
                                                                    <form>
                                                                        <div class="mb-3">
                                                                            <select class="form-select mb-3" id="userid${parseInt(i)}" onchange="check(${parseInt(i)});">
                                                                                <option value="">Choose User</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="mb-3" id="title${parseInt(i)}">
                                                                       
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>`);
        }
    }

    $('#memo_section').css('display', 'block');
    $('#concurr_section').css('display', 'none');


    $.ajax({
        type: "POST",
        url: "process.php",
        data: {
            action: "get_staff_department",
            department: department
        },
        success: function (response) {
            $('#user1').html(`
                <option value = "">Select User</option>
                <option></option>
                ${response}
            `);
        }
    })

    $.ajax({
        type: "POST",
        url: "process.php",
        data: {
            action: "get_serial_no",
            department: department
        },
        success: function (response) {
            var response = JSON.parse(response);

            if (response == false) {
                var serial_no = 1;
            } else {
                serial_no = Number(response['SerialNumber']) + 1;
            }
            $('#serial_no_section').html(`<input type="text" id="serial_no" name="serial_no" value="${serial_no}" class="form-control form-control-sm" placeholder="Serial Number" disabled>`);

            $.ajax({
                type: "POST",
                url: "process.php",
                data: {
                    action: "get_dept_code",
                    department: department
                },
                success: function (response) {
                    var response1 = JSON.parse(response);
                    $('#dept_code_section').html(`<input type="text" id="dept_code" name="dept_code" value="${response1['DepartmentCode']}" class="form-control form-control-sm" placeholder="Dept. Code" disabled>`);
                    var memo_code = `PIFS/HQ/${response1['DepartmentCode']}/${year}/${month}/${day}/${serial_no}`;
                    $('#memo_code_section').html(`<input type="text" id="memo_code" name="memo_code" value="${memo_code}" class="form-control form-control-sm" placeholder="Memo Code" disabled>`)

                }
            });
        }
    });

    $.ajax({
        type: "POST",
        url: "process.php",
        data: {
            action: "get_concurring_staff1"
        },
        success: function (response) {

            $(`#recipient`).html(`
                        <option value = "">Select User</option>
                        <option></option>
                        ${response}
                    `);

            $(`#through`).html(`
                        <option value = "">Select User</option>
                        <option></option>
                        ${response}
                    `);

        }
    });

});

function check(i) {

    var userid = $("#userid" + i + " option:selected").val();
    $.ajax({
        type: "POST",
        url: "process.php",
        data: {
            action: "get_concurring_staff_title",
            userid: userid
        },
        success: function (response) {
            var title = JSON.parse(response);
            $(`#title${parseInt(i)}`).html(`<input type="text" class="form-control" id="position${parseInt(i)}" value="${title[0]['Title']}" placeholder="Position" disabled>`);

        }
    })
}

// Check Approval
$(document).delegate("[data-bs-target='#staticBackdrop2']", "click", function () {


    $('.insert').modal('show');

    var memo_id = $(this).attr('data-id');
    var username = $('#username').val();

    $.ajax({
        type: "POST",
        url: "process.php",
        data: {
            action: "check_approval",
            username: username,
            memo_id: memo_id
        },
        success: function (response) {
            var response = JSON.parse(response);
            if (response == "") {
            } else {
                if (response[0]['Status'] == 0) {
                    toastr['error'](`${response[0].full_name} is yet to Approve`);
                    $('#approve-details').css('display', 'none');
                }
            }

        }
    });

    $.ajax({
        type: "POST",
        url: "process.php",
        data: {
            action: "check_approval_submit",
            username: username,
            memo_id: memo_id
        },
        success: function (response) {
            var response = JSON.parse(response);
            $("#approve-details [name=\"memo_id\"]").val(response[0].MemoID);
            if (response[0]['Status'] == 1) {
                toastr['error'](`You Can't Approve Again`);
                $('#approve-details').css('display', 'none');
            }

            if (response[0]['check_decline'] > 0) {
                toastr['error'](`This Memo has been Declined`);
                $('#approve-details').css('display', 'none');
            }






        }
    });
});

// Approve Memo
$("#update_approval_button").click(function (e) {

    e.preventDefault();

    var memo_id = $('#memo_id').val();
    var userid = $('#username').val();

    // Status required
    var status = $('#approval_status option:selected').val();
    if (status == "") {
        toastr["warning"]('Status is required');
        $("input#approval_status").focus();
        return false;
    }

    // Comment required
    var comments = $("#comment").val();
    if (comments == "") {
        toastr["warning"]('Comment is required');
        $("#comment").focus();
        return false;
    }

    // Signature Required
    var signature = $('#signature').prop('files')[0];

    var form_data = new FormData();

    form_data.append('signature', signature);
    form_data.append('username', userid);

    if (signature == null) {
    } else {

        $.ajax({
            url: "signature_insert.php",
            type: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
            }
        });

    }

    if (status == 2) {
        $.ajax({
            url: "process.php",
            type: "POST",
            data: {
                action: "update_memo_status",
                memo_id: memo_id,
            },
            success: function (response) {
            }
        });
    }

    $.ajax({
        type: "POST",
        url: "process.php",
        data: {
            action: "submit_approval",
            memo_id: memo_id,
            userid: userid,
            status: status,
            comments: comments

        },
        beforeSend: function () {
            $("#update_approval_button").val("Processing...");
        },
        success: function (response) {
            if (response == true) {
                $('.add').modal('hide');
                window.location.reload();
                toastr["success"]("Updated Approval");
            } else {
                toastr["error"](response);
                $("#update_approval_button").val("Update");
            }
        },
    });

});
