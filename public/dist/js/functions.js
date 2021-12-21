$(document).ready(function () {

	 //global variable
    var protocol = window.location.protocol;
    var hostname = window.location.hostname;
    var port = window.location.port;
    var pathname = window.location.pathname;
    pathname = pathname.split("/");
    var domainName = pathname[1];

    if(port){ // 127.0.0.1:8000
    	var globalURL = protocol + "//" + hostname + ":" + port + "/";
    }else{
    	var globalURL = protocol + "//" + hostname + "/";
    }



    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2').select2({
      theme: 'bootstrap4'
    })

    $( "#date_of_birth" ).datepicker({
      format: 'dd/mm/yyyy'
    });

/*    $( "#start_date" ).datepicker({
        format: 'dd/mm/yyyy'
    });

    $( "#end_date" ).datepicker({
        format: 'dd/mm/yyyy'
    });*/


/*
* division, district, post
* ========================================================
* division hit, loading district content
*/


    $("#school_division").change(function() {
        school_district_loading_document_id($(this).val());
    });


    $("#school_district").change(function() {
        school_post_loading_document_id($(this).val());
    });


    // Present Division, District
    $("#present_division_id").change(function() {
        present_district_loading_document_id($(this).val());
    });

    $("#present_district_id").change(function() {
        present_post_loading_document_id($(this).val());
    });

    // Parmanent Division, District
    $("#permanent_division_id").change(function() {
        permanent_district_loading_document_id($(this).val());
    });

    $("#permanent_district_id").change(function() {
        permanent_post_loading_document_id($(this).val());
    });








    // Edit school info
    $(".editSchoolInfo").click(function(){
          id = $(this).attr('id');

          var url = globalURL + "loading_school_info_ajax_hit/" + id;
          $.ajax({
                url: url,
                type: "get",
                dataType: 'json',
                success: function(response){
                    $("#school_name").val(response["school_name"]);
                    $("#school_ein").val(response["school_ein"]);
                    $("#school_mobile_sinfo").val(response["school_mobile"]);
                    $("#school_email").val(response["school_email"]);
                    $("#school_address").val(response["school_address"]);
                    $("#school_division").val(response["school_div"]);

                    // loading school district
                    school_district_loading_division_district_id(response["school_div"], response["school_dist"]);
                    school_district_loading_district_post_id(response["school_dist"], response["school_ps"]);

                    // display the logo
                    if(response["school_logo"] != null){
                        $("#hidden_school_logo").val(response["school_logo"]);
                        $("#school_logo_box").attr("src", "/storage/school_logo/" + response["school_logo"]);
                    }else{
                        $("#hidden_school_logo").val("");
                        $("#school_logo_box").attr("src", "/dist/img/image-not-available.jpg");
                    }

                    $("#hidden_school_info_id").val(response["id"]);
                },
                error: function(){
                    alert('We are sorry. Please try again.');
                }
            });

    });




    $(".EditSchoolType").click(function(){

        document.getElementById("addSchoolTypeTitle").innerText = "Update School Type";
        document.getElementById("schooTypeBtnTxt").innerText = "Update";

          id = $(this).attr('id');
          var url = globalURL + "edit-school-type/" + id;
          $.ajax({
                url: url,
                type: "get",
                dataType: 'json',
                success: function(response){
                    $("#school_type").val(response["school_type"]);
                    $("#school_status").val(response["status"]);
                    $("#hidden_school_type_id").val(response["id"]);
                },
                error: function(){
                    alert('We are sorry. Please try again.');
                }
            });

    });



    /*Edit Manage Fine*/
    $(".EditFine").click(function(){
         document.getElementById("fine_title").innerText = "Update Fine Details";
         document.getElementById("fine_btnText").innerText = "Update";
        id = $(this).attr('id');
          var url = globalURL + "edit-fine/" + id;
          $.ajax({
                url: url,
                type: "get",
                dataType: 'json',
                success: function(response){
                    $("#class_namee").val(response["class_id"]);
                    $("#student_idd").val(response["student_id"]);
                    class_wise_student(response["class_id"], response["student_id"]);
                    $("#month_numberr").val(response["month"]);
                    $("#head_id").val(response["head_id"]);
                    $("#fineAmount").val(response["amount"]);
                    $("#reasons").val(response["reasons"]);
                    $("#updateFine").val(response["id"]);
                },
                error: function(){
                    alert('We are sorry. Please try again.');
                }
            });

    });

    function class_wise_student(class_id, student_id){
        var class_id = class_id;
        var url = globalURL + "classWiseStudents";

        if(class_id == ''){
            $('#student_idd').prop('disabled',  true);
        }else{
            $('#student_idd').prop('disabled',  false);
            $.ajax({
                url: url,
                type: "get",
                data: {'class_id' : class_id},
                dataType: 'json',
                success: function(data){
                   $("#student_idd").html(data);
                   $("#student_idd").val(student_id);
                },
                error: function(){
                    alert('We are sorry to load Student ID. Please try again.');
                }
            });
        }
    }



    // Edit class info
    $(".editClassInfoItem").click(function(){
        document.getElementById("addClasses").innerText = "Update Class";
        document.getElementById("addClassBtnTxt").innerText = "Update";

          id = $(this).attr('id');

          var url = globalURL + "loading_class_info_item_ajax_hit/" + id;
          $.ajax({
                url: url,
                type: "get",
                dataType: 'json',
                success: function(response){
                    $("#class_name").val(response["name"]);
                    $("#hidden_class_id").val(response["id"]);
                },
                error: function(){
                    alert('We are sorry. Please try again.');
                }
            });

    });

    // ./ Class info


    // Edit shift info
    $(".editShiftInfoItem").click(function(){
          id = $(this).attr('id');

          var url = globalURL + "loading_shift_info_item_ajax_hit/" + id;
          $.ajax({
                url: url,
                type: "get",
                dataType: 'json',
                success: function(response){
                    $("#shift_name").val(response["name"]);
                    $("#description").val(response["description"]);
                    $("#start_time").val(response["start_time"]);
                    $("#end_time").val(response["end_time"]);
                    $("#hidden_shift_id").val(response["id"]);
                },
                error: function(){
                    alert('We are sorry. Please try again.');
                }
            });

    });

    // ./ Shift info


     // Edit section info
    $(".editSectionInfoItem").click(function(){
        document.getElementById("addSectionTitle").innerText = "Update Section";
        document.getElementById("addSectionBtnTxt").innerText = "Update";

          id = $(this).attr('id');

          var url = globalURL + "loading_section_info_item_ajax_hit/" + id;
          $.ajax({
                url: url,
                type: "get",
                dataType: 'json',
                success: function(response){
                    $("#section_name").val(response["name"]);
                    $("#hidden_section_id").val(response["id"]);
                },
                error: function(){
                    alert('We are sorry. Please try again.');
                }
            });

    });

    // ./ Section info


    // Edit session info
    $(".editSessionInfoItem").click(function(){
         document.getElementById("addSessionTitle").innerText = "Update Session";
         document.getElementById("sessionBtnTxt").innerText = "Update";
          id = $(this).attr('id');

          var url = globalURL + "loading_session_info_item_ajax_hit/" + id;
          $.ajax({
                url: url,
                type: "get",
                dataType: 'json',
                success: function(response){
                    $("#session_name").val(response["name"]);
                    $("#hidden_session_id").val(response["id"]);
                },
                error: function(){
                    alert('We are sorry. Please try again.');
                }
            });

    });

    // ./ Session info


     // Edit group info
    $(".editGroupInfoItem").click(function(){
         document.getElementById("addGroupTitle").innerText = "Update Group";
         document.getElementById("addGroupBtnTxt").innerText = "Update";
          id = $(this).attr('id');

          var url = globalURL + "loading_group_info_item_ajax_hit/" + id;
          $.ajax({
                url: url,
                type: "get",
                dataType: 'json',
                success: function(response){
                    $("#group_name").val(response["name"]);
                    $("#hidden_group_id").val(response["id"]);
                },
                error: function(){
                    alert('We are sorry. Please try again.');
                }
            });

    });

    // ./ Group info


    // Edit medium info
    $(".editMediumInfoItem").click(function(){
          id = $(this).attr('id');

          var url = globalURL + "loading_medium_info_item_ajax_hit/" + id;
          $.ajax({
                url: url,
                type: "get",
                dataType: 'json',
                success: function(response){
                    $("#medium_name").val(response["name"]);
                    $("#hidden_medium_id").val(response["id"]);
                },
                error: function(){
                    alert('We are sorry. Please try again.');
                }
            });

    });

    // ./ Medium info



    function school_district_loading_division_district_id(division_id, district_id){
         var division_id = division_id;

        var url = globalURL + "division/district_loading_ajax_hit";

        if(division_id == ''){
            $('#school_district').prop('disabled',  true);
        }else{
            $('#school_district').prop('disabled',  false),
            $.ajax({
                url: url,
                type: "get",
                data: {'division_id' : division_id},
                dataType: 'json',
                success: function(data){
                   $("#school_district").html(data);
                   $("#school_district").val(district_id);
                },
                error: function(){
                    alert('We are sorry to load district. Please try again.');
                }
            });
        }
    }

    function school_district_loading_district_post_id(district_id, post_id){
        var district_id = district_id;

        var url = globalURL + "division/post_loading_ajax_hit";
        if(district_id == ''){
            $('#school_post').prop('disabled',  true);
        }else{
            $('#school_post').prop('disabled',  false),
            $.ajax({
                url: url,
                type: "get",
                data: {'district_id' : district_id},
                dataType: 'json',
                success: function(data){
                   $("#school_post").html(data);
                   $("#school_post").val(post_id);
                },
                error: function(){
                    alert('We are sorry to load post. Please try again.');
                }
            });
        }
    }

    function school_district_loading_document_id(id){
         var division_id = id;

        var url = globalURL + "division/district_loading_ajax_hit";

        if(division_id == ''){
            $('#school_district').prop('disabled',  true);
        }else{
            $('#school_district').prop('disabled',  false),
            $.ajax({
                url: url,
                type: "get",
                data: {'division_id' : division_id},
                dataType: 'json',
                success: function(data){
                   $("#school_district").html(data);
                },
                error: function(){
                    alert('We are sorry to load district. Please try again.');
                }
            });
        }
    }

    function present_district_loading_document_id(id){
         var division_id = id;

        var url = globalURL + "division/district_loading_ajax_hit";

        if(division_id == ''){
            $('#present_district_id').prop('disabled',  true);
        }else{
            $('#present_district_id').prop('disabled',  false),
            $.ajax({
                url: url,
                type: "get",
                data: {'division_id' : division_id},
                dataType: 'json',
                success: function(data){
                   $("#present_district_id").html(data);
                },
                error: function(){
                    alert('We are sorry to load district. Please try again.');
                }
            });
        }
    }

    function permanent_district_loading_document_id(id){
         var division_id = id;

        var url = globalURL + "division/district_loading_ajax_hit";

        if(division_id == ''){
            $('#permanent_district_id').prop('disabled',  true);
        }else{
            $('#permanent_district_id').prop('disabled',  false),
            $.ajax({
                url: url,
                type: "get",
                data: {'division_id' : division_id},
                dataType: 'json',
                success: function(data){
                   $("#permanent_district_id").html(data);
                },
                error: function(){
                    alert('We are sorry to load district. Please try again.');
                }
            });
        }
    }

    function school_post_loading_document_id(id){
        var district_id = id;

        var url = globalURL + "division/post_loading_ajax_hit";
        if(district_id == ''){
            $('#school_post').prop('disabled',  true);
        }else{
            $('#school_post').prop('disabled',  false),
            $.ajax({
                url: url,
                type: "get",
                data: {'district_id' : district_id},
                dataType: 'json',
                success: function(data){
                   $("#school_post").html(data);
                },
                error: function(){
                    alert('We are sorry to load post. Please try again.');
                }
            });
        }
    }


      function present_post_loading_document_id(id){
        var district_id = id;

        var url = globalURL + "division/post_loading_ajax_hit";
        if(district_id == ''){
            $('#present_post_id').prop('disabled',  true);
        }else{
            $('#present_post_id').prop('disabled',  false),
            $.ajax({
                url: url,
                type: "get",
                data: {'district_id' : district_id},
                dataType: 'json',
                success: function(data){
                   $("#present_post_id").html(data);
                },
                error: function(){
                    alert('We are sorry to load post. Please try again.');
                }
            });
        }
    }


    function permanent_post_loading_document_id(id){
        var district_id = id;

        var url = globalURL + "division/post_loading_ajax_hit";
        if(district_id == ''){
            $('#permanent_post_id').prop('disabled',  true);
        }else{
            $('#permanent_post_id').prop('disabled',  false),
            $.ajax({
                url: url,
                type: "get",
                data: {'district_id' : district_id},
                dataType: 'json',
                success: function(data){
                   $("#permanent_post_id").html(data);
                },
                error: function(){
                    alert('We are sorry to load post. Please try again.');
                }
            });
        }
    }

/*Mominur written script start*/

    $("#class233").click(function(){
          var school_id = document.getElementById("school_id233").value;
          var class_id = document.getElementById("class233").value;
          var year_id = document.getElementById("year233").value;
          if (school_id !=="" && class_id !=="" && year_id!=="") {
            var url = globalURL + "fees-amount/" + school_id+'/'+class_id+'/'+year_id;
            $.ajax({
                url: url,
                type: "GET",
                dataType: 'json',
                success: function(response){
                 response.forEach(row =>{
                    var idd ="#amount"+row.fees_id;
                    $(idd).val(row.amount);

                 });

                },
            });

          }

    });

    $("#head_id").change(function(){
        id = document.getElementById("head_id");

          if(id !== "" ){
            var url = globalURL + "getsubhead/"+id;
              $('#subhead_id').empty();
              $('#subhead_id').append('<option value="0" disabled selected>Fetching Data...</option>');

              $.ajax({
                    type: "GET",
                    url: url,
                    dataType: 'json',
                    success: function(response){
                         $('#subhead_id').empty();
                         $('#subhead_id').append('<option value="0" disabled selected>Select Subhead</option>');

                         response.forEach(row =>{
                            $('#subhead_id').append('<option value="'+row.id+'">'+row.fees_subhead_name+'</option>');
                         });
                    },

                });
          }
          if(id==="") {
            alert("Select the Year First then Select Class");
          }

    });

    // $("#class_fw").change(function(){
    //     var year_id = document.getElementById("year_fw").value;
    //     var class_id = $(this).val();

    //     if(year_id !== "" && class_id !== ""){
    //         var url = globalURL + "fetch-student-id/"+year_id+ "/"+ class_id;
    //         $('#student_data_fw').empty();
    //         $('#student_data_fw').append('<option value="0" disabled selected>Fetching Data...</option>');

    //         $.ajax({
    //             type: "GET",
    //             url: url,
    //             dataType: 'json',
    //             success: function(response){
    //                 $('#student_data_fw').empty();
    //                 $('#student_data_fw').append('<option value="0" disabled selected>Select Student ID</option>');

    //                 response.forEach(row =>{
    //                     $('#student_data_fw').append('<option value="'+row.id+'">'+row.student_id+'</option>');
    //                 });
    //             },

    //         });
    //     }
    //     if(year_id==="") {
    //         alert("Select the Year First then Select Class");
    //     }

    // });



    $(".editAssignClassItem").click(function(){
          id = $(this).attr('id');

          var url = globalURL + "edit_assign_class/" + id;
          $.ajax({
                url: url,
                type: "get",
                dataType: 'json',
                success: function(response){
                    $("#school_id2").val(response["school_id"]);
                    $("#class_id2").val(response["class_id"]);
                    $("#hidden_menu_id").val(response["id"]);
                },
                error: function(){
                    alert('We are sorry. Please try again.');
                }
            });

    });


    $("#student_data_fw").click(function(){
          var year_id = document.getElementById("year_fw").value;
          var class_id = document.getElementById("class_fw").value;
          var student_id = document.getElementById("student_data_fw").value;
          if (year_id !=="" && class_id !=="" && student_id!=="") {
            var url = globalURL + "fees-waiver/" + year_id+'/'+class_id+'/'+student_id;
            $.ajax({
                url: url,
                type: "GET",
                dataType: 'json',
                success: function(response){
                 response.forEach(row =>{
                    var id12 ="#paid_waiver_amount"+row.fees_id;
                    var id13 ="#discount_amount"+row.fees_id;
                    $(id12).val(row.paid_waiver_amount);
                    $(id13).val(row.discount_amount);

                 });

                },
            });

          }
    });


    $(".dtd_fees_collection").click(function(){
          var year_id = document.getElementById("year_fw").value;
          var class_id = document.getElementById("class_fw").value;
          var student_id = document.getElementById("student_data_fw").value;
          if (year_id !=="" && class_id !=="" && student_id!=="") {
            var url = globalURL + "stu-fees-collection/" + year_id+'/'+class_id+'/'+student_id;
            $.ajax({
                url: url,
                type: "GET",
                dataType: 'json',
                success: function(response){
                 response.forEach(row =>{
                    var da ="#da"+row.fees_id;
                    $(da).val(row.received_amount);
                    var fa ="#fa"+row.fees_id;
                    var fees_amt = $(fa).val();
                    var due_amount_cal = fees_amt - row.received_amount;
                    var dua ="#due_amt"+row.fees_id;
                    $(dua).val(due_amount_cal);

                 });

                },
            });

          }
    });


 $("#class_namee").change(function(){
        var class_id = $(this).val();

        if(class_id !== ""){
            var url = globalURL + "find-class-wise-students/"+ class_id;

            $('#student_idd').empty();
            $('#student_idd').append('<option value="">Fetching Student ID...</option>');

            $.ajax({
                type: "GET",
                url: url,
                dataType: 'json',
                success: function(response){
                    if(response.hasStudent==1){
                        $('#student_idd').empty();
                        $('#student_idd').append('<option value="">Select Student ID</option>');
                        response.students.forEach(row =>{
                            $('#student_idd').append('<option value="'+row.id+'">'+row.student_fullid+'</option>');
                        });
                    }
                    else{
                        $('#student_idd').empty();
                        $('#student_idd').append('<option value="">No students available of this class</option>');
                    }

                },

            });
        }

    });


/*Mominur written script end*/



    // Payonline
    hiddenTotalInvoiceNos = "";
    hiddenTotalStudentIDs = "";
    hiddenTotalPayableAmounts = "";
    payableTotalAmount  = 0;
    $('.checkmark').on("click", function(){
        id = $(this).val();
        amount = parseInt($("#totalAmount_"+id).html());
        if($(this).prop("checked") == true){
           payableTotalAmount += amount;
           hiddenTotalPayableAmounts += $('#hiddenTotalAmount_'+id).val() + ",";
           hiddenTotalInvoiceNos += $('#hiddenTotalInvoiceNo_'+id).val() + ",";
           hiddenTotalStudentIDs += $('#hiddenTotalStudentId_'+id).val() + ",";

           $('#buttononclickdisable_'+id).attr("disabled", true);
           $("#buttononclickdisable_"+id).removeClass("payNowChildBtn").addClass("payNowChildBtnSecond");
        }else{
            if($(this).prop("checked") == false){
               payableTotalAmount -= amount;
               $('#buttononclickdisable_'+id).attr("disabled", false);
               $("#buttononclickdisable_"+id).removeClass("payNowChildBtnSecond").addClass("payNowChildBtn");
            }
        }

        $("#totalPayableAmount").html(payableTotalAmount);
        $("#hiddenTotalPayableAmount").val(parseInt(payableTotalAmount));
        $("#hiddenTotalPayableAmounts").val(hiddenTotalPayableAmounts);
        $("#hiddenTotalInvoiceNos").val(hiddenTotalInvoiceNos);
        $("#hiddenTotalStudentIDs").val(hiddenTotalStudentIDs);

        if(payableTotalAmount > 0){
            $('#totalPaynowBtn').attr("disabled", false);
        }else{
            $('#totalPaynowBtn').attr("disabled", true);
        }
    });




	// Confirmation message
	$('.confirm_edit_dialog').click(function () {
		if (confirm('Do you really want to edit these records?')) {
        	return true;
	    }else{
	      	return false;
	    }
	});

	$('.confirm_delete_dialog').click(function () {
		if (confirm('Do you really want to delete these records?')) {
        	return true;
	    }else{
	      	return false;
	    }
	});

    $('.confirm_restore_dialog').click(function () {
        if (confirm('Do you really want to restore this record?')) {
            return true;
        }else{
            return false;
        }
    });

    $('.confirm_school_active_pending_dialog').click(function () {
        if (confirm('Do you really want to pending status from active this record?')) {
            return true;
        }else{
            return false;
        }
    });

    $('.confirm_school_pending_to_active_dialog').click(function () {
        if (confirm('Do you really want to active status from pending this record?')) {
            return true;
        }else{
            return false;
        }
    });


    $('.confirm_school_approved_from_pending_dialog').click(function () {
        if (confirm('Do you really want to change the status from pending to approved?')) {
            return true;
        }else{
            return false;
        }
    });
	// ./ Confirmation message


	// Alert message

    $('#alertMessage').show('fade');

    setTimeout(function () {
        $('#alertMessage').hide('fade');
    }, 3000);

    // ./ Alert message

});


function OpenFineModal() {
    document.getElementById("fine_title").innerText = "Add Fine Details";
    document.getElementById("fine_btnText").innerText = "Save";
    $("#class_namee").val('');
    $("#student_idd").val('');
    $("#month_numberr").val('');
    $("#head_id").val('');
    $("#fineAmount").val('');
    $("#reasons").val('');
    $('#FineModal').modal('show');
}

function OpenClassModal() {
    document.getElementById("addClasses").innerText = "Add Class";
    document.getElementById("addClassBtnTxt").innerText = "Save";
    $("#class_name").val('');
    $('#modal-default').modal('show');
}

function OpenShiftModal() {
    document.getElementById("addShiftTitle").innerText = "Add Shift";
    document.getElementById("AddShiftBtnTxt").innerText = "Save";
    $('#modal-default').modal('show');
}

function OpenSectionModal() {
    document.getElementById("addSectionTitle").innerText = "Add Section";
    document.getElementById("addSectionBtnTxt").innerText = "Save";
    $("#section_name").val('');
    $('#modal-default').modal('show');
}

function OpenGroupModal() {
    document.getElementById("addGroupTitle").innerText = "Add Group";
    document.getElementById("addGroupBtnTxt").innerText = "Save";
    $("#group_name").val('');
    $('#modal-default').modal('show');
}

function OpenSessionModal() {
    document.getElementById("addSessionTitle").innerText = "Add Session";
    document.getElementById("sessionBtnTxt").innerText = "Save";
    $("#session_name").val('');
    $('#modal-default').modal('show');
}



function OpenSchoolTypeModal() {
    document.getElementById("addSchoolTypeTitle").innerText = "Add School Type";
    document.getElementById("schooTypeBtnTxt").innerText = "Save";
    $("#school_type").val('');
    $("#school_status").val('');
    $('#modal-default').modal('show');
}

function withdraw_popup(id){


    $('#modal-default-withdraw').modal('show');
    var url = globalURL + "withdraw/list/" + id;
    console.log(url);
    $.ajax({
        url: url,
        type: "get",
        dataType: 'json',
        success: function(response){
            console.log(response);
            console.log(response[0].school_name);
            let payable_amt = response[0].total_amount - response[0].service_charge;
            document.getElementById("withdraw_school_name").innerText=response[0].school_name;
            document.getElementById("withdraw_total_amount").innerText=response[0].total_amount;
            document.getElementById("withdraw_commision").innerText=response[0].service_charge;
            document.getElementById("withdraw_payable").innerText=payable_amt;
            document.getElementById("withdraw_req_date").innerText=response[0].req_date;
            document.getElementById("withdrawId").value=id;
            //alert(document.getElementById("withdrawId").value);



/*            $("#withdraw_request_method").val(response["class_id"]);
            $("#withdraw_total_amount").val(response["id"]);
            $("#withdraw_commision").val(response["school_id"]);
            $("#withdraw_payable").val(response["class_id"]);
            $("#withdraw_req_date").val(response["id"]);*/
        },
        error: function(){
            alert('We are sorry. Please try again.');
        }
    });

}
