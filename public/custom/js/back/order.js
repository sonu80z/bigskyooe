jQuery(function(){
   // add order page's event function
   admin_add_order_event_proc_func();
});

if (typeof window.station_loaded_callback !== 'function') {
    window.station_loaded_callback = function() {};
}

if (typeof window.procedure_updated_callback !== 'function') {
    window.procedure_updated_callback = function() {};
}

/**
 * add order page's event function
 * */
function admin_add_order_event_proc_func()
{
   // add procedure
    jQuery(".ao_add_procedure_dv").click(function(){
        var tmp_idx = "1234567890";
       var html = jQuery(this).parent("div").prev().html();
        var old_name = jQuery(this).parent("div").prev("div").find(".ao_plrn").attr('name');
        jQuery(this).parent("div").prev("div").find(".ao_plrn").attr('name', 'ao_plrn['+tmp_idx+']');
       html = '<div class="form-group">' + html + '</div>';
       jQuery(this).parent("div").before(html);
        jQuery("[name='ao_plrn["+tmp_idx+"]']").attr('name',old_name);
        jQuery(this).parent("div").prev("div").find(".ao_plrn").removeAttr('checked');

       var num = parseInt(jQuery(this).attr("num")) + 1;
       jQuery(this).attr("num", num);
        jQuery(this).parent("div").prev("div").children("div:first-child").children("label").html("Procedure #" + num + " *");
        jQuery(this).parent("div").prev("div").find(".ao_procedure_list").attr('name', 'ao_procedure_list['+num+']').val('');
        jQuery(this).parent("div").prev("div").find(".cpt-autocomplete").val('');
        jQuery(this).parent("div").prev("div").find(".procedure-id").attr('name', 'ao_procedure_list['+num+']').val('');
        jQuery(this).parent("div").prev("div").find(".ao_plrn").attr('name', 'ao_plrn['+num+']');
        // Clear symptom input fields for new procedure row
        jQuery(this).parent("div").prev("div").find(".ao_symptom_1").attr('name', 'ao_symptom_1['+num+']').val('');
        jQuery(this).parent("div").prev("div").find(".ao_symptom_2").attr('name', 'ao_symptom_2['+num+']').val('');
        jQuery(this).parent("div").prev("div").find(".ao_symptom_3").attr('name', 'ao_symptom_3['+num+']').val('');
        
        // Re-initialize autocomplete for the newly added symptom fields
        jQuery(this).parent("div").prev("div").find(".icd10-autocomplete").each(function() {
            $(this).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: BASE_URL + 'admin/order/search_icd10',
                        dataType: 'json',
                        data: { term: request.term },
                        success: function(data) {
                            response(data);
                        },
                        error: function() {
                            response([]);
                        }
                    });
                },
                minLength: 2,
                select: function(event, ui) {
                    $(this).val(ui.item.value);
                    return false;
                }
            });
        });
        
        // Re-initialize autocomplete for the newly added procedure field
        jQuery(this).parent("div").prev("div").find(".cpt-autocomplete").each(function() {
            $(this).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: BASE_URL + 'admin/order/search_procedures',
                        dataType: 'json',
                        data: { term: request.term },
                        success: function(data) {
                            response(data);
                        },
                        error: function() {
                            response([]);
                        }
                    });
                },
                minLength: 2,
                select: function(event, ui) {
                    $(this).val(ui.item.value);
                    $(this).siblings('.procedure-id').val(ui.item.id);
                    return false;
                }
            });
        });
    });

    $("body").on("click",".btn-del-procedure",function(){
        var item_count = $(".btn-del-procedure").length;
        if(item_count > 1) {
            $(this).closest(".form-group").remove();
        }
    });

    // select facility
    jQuery("select[name='ao_ordering_facility']").change(function(){
        var facility = jQuery(this).val();
        
        // Handle "Not in list" option
        if(facility == 9999) {
            var html = $(".add-facility-dlg-container").html();
            window.facility_dlg = show_dialog("Add Facility", html, "medium", true);
            // Reset form
            $(".jconfirm").find(".add-facility-alert").addClass("g_none_dis");
            $(".jconfirm").find("input[name='quick_facility_name']").val('').focus();
            $(".jconfirm").find("select[name='quick_facility_type']").val('');
            $(".jconfirm").find("input[name='quick_facility_address']").val('');
            $(".jconfirm").find("input[name='quick_facility_city']").val('');
            $(".jconfirm").find("input[name='quick_facility_state']").val('');
            $(".jconfirm").find("input[name='quick_facility_zip']").val('');
            $(".jconfirm").find("input[name='quick_facility_phone']").val('');
            $(".jconfirm").find("input[name='quick_facility_fax']").val('');
            return false;
        }
        
        var params = {
            "facility" : facility
        };
        console.log(facility);

        var url = BASE_URL + "admin/facility/get_facility_info";
        jQuery.post(url, params, function(res) {
            console.log(res);
            if(res.status == "1" ) {
                jQuery("input[name='ao_ordered_address']").val(res.info.address1);
                jQuery("input[name='ao_ordered_city']").val(res.info.address_city);
                jQuery("input[name='ao_ordered_state']").val(res.info.address_state);
                jQuery("input[name='ao_ordered_zip']").val(res.info.address_zip);
                jQuery("input[name='ao_ordered_phone']").val(res.info.phone);
                jQuery("input[name='ao_ordered_fax']").val(res.info.fax);

                var html = '<option value="0">Select</option>';
                for ( i = 0; i < res.stations.length; i ++ )
                {
                    html += '<option value="' + res.stations[i]["Id"] + '">' + res.stations[i]["StationName"] + '</option>';
                }
                jQuery("select[name='ao_ordered_station']").html(html);

            } else {
                jQuery("input[name='ao_ordered_address']").val("");
                jQuery("input[name='ao_ordered_city']").val("");
                jQuery("input[name='ao_ordered_state']").val("");
                jQuery("input[name='ao_ordered_zip']").val("");
                jQuery("input[name='ao_ordered_phone']").val("");
                jQuery("input[name='ao_ordered_fax']").val("");

                jQuery("select[name='ao_ordered_station']").html('<option value="0">Select</option>');
            }
            if (typeof station_loaded_callback === 'function') {
                station_loaded_callback();
            }
        });

    });
    jQuery("select[name='ao_service_facility']").change(function(){
        var facility = jQuery(this).val();
        var params = {
            "facility" : facility
        };

        var url = BASE_URL + "admin/facility/get_facility_info";
        jQuery.post(url, params, function(res) {
            if(res.status == "1" ) {
                jQuery("input[name='ao_service_address']").val(res.info.address1);
                jQuery("input[name='ao_service_city']").val(res.info.address_city);
                jQuery("input[name='ao_service_state']").val(res.info.address_state);
                jQuery("input[name='ao_service_zip']").val(res.info.address_zip);
                jQuery("input[name='ao_service_phone']").val(res.info.phone);
                jQuery("input[name='ao_service_fax']").val(res.info.fax);

                var html = '<option value="0">Select</option>';
                for ( i = 0; i < res.stations.length; i ++ )
                {
                    html += '<option value="' + res.stations[i]["Id"] + '">' + res.stations[i]["StationName"] + '</option>';
                }
                jQuery("select[name='ao_service_station']").html(html);

            } else {
                jQuery("input[name='ao_service_address']").val("");
                jQuery("input[name='ao_service_city']").val("");
                jQuery("input[name='ao_service_state']").val("");
                jQuery("input[name='ao_service_zip']").val("");
                jQuery("input[name='ao_service_phone']").val("");
                jQuery("input[name='ao_service_fax']").val("");

                jQuery("select[name='ao_service_station']").html('<option value="0">Select</option>');
            }
            if (typeof station_loaded_callback === 'function') {
                station_loaded_callback();
            }
        });

    });
    //Clear Button
    jQuery(".clear").click(function () {
      jQuery("input[name = 'ao_f_address']").val('');
    });

    // select ordering dr
    jQuery("select[name='ao_ordering_dr']").change(function(){
        var user = jQuery(this).val();

        var params = {
            "user" : user
        };

        var url = BASE_URL + "admin/users/get_user_info";
        jQuery.post(url, params, function(res) {
            if(res.status == "1" ) {
                jQuery("input[name='ao_od_phone']").val(res.info.phone);
                jQuery("input[name='ao_od_fax']").val(res.info.fax);

            } else {
                jQuery("input[name='ao_od_phone']").val("");
                jQuery("input[name='ao_od_fax']").val("");
            }
        });
    });

    // select procedure type
    jQuery("input[name='ao_pt_radio']").click(function(){
       var modality = jQuery(this).next("label").html();
        var params = {
            "modality" : modality
        };

        var url = BASE_URL + "admin/procedure/get_procedures_via_modality";
        jQuery.post(url, params, function(res) {
            console.log("res", res);
            if(res.status == "1" ) {
                var html = '<option value="0">Select</option>';
                for ( i = 0; i < res.list.length; i ++ )
                {
                    html += '<option value="' + res.list[i]["id"] + '">' + res.list[i]["description"] + '</option>';
                }
                jQuery("select.ao_procedure_list").html(html);
                //add_order_symptom_display_list();
            } else {
                jQuery("select.ao_procedure_list").html('<option value="0">Select</option>');
                //add_order_symptom_display_list();
            }
        });
    });

    //select ao_kind setting
    var $kindSelect = jQuery("select[name='ao_kind']");
    if ($kindSelect.length) {
        $kindSelect.change(function () {
            var ao_kind = jQuery(this).val();
            console.log(ao_kind);
            if (ao_kind == 1) {
                jQuery("#service_location").css("display", "none");
            } else {
                jQuery("#service_location").css("display", "block");
            }
        });
        $kindSelect.trigger('change');
    } else {
        jQuery("#service_location").css("display", "none");
    }

    //No list add
    jQuery("select.ao_procedure_list").change(function () {
        var ao_kind = jQuery(this).val();

        if (ao_kind == 1111) {
            window.location.href=BASE_URL + "admin/procedure/add";
        }
    });
    jQuery("select[name='ao_service_dr']").change(function () {
        var ordering_physician = jQuery(this).val();
        console.log("ordering_physician:", ordering_physician);
        if (ordering_physician == 1111) {
            //show_dialog("test", "aaaaa", "small");
            //window.location.href=BASE_URL + "admin/users/add";
            return false;
        }
        var phone = "";
        var fax = "";
        var NPI = "";
        if(ordering_physician!=""){
            phone = $(this).find("option:checked").attr("data-phone");
            fax = $(this).find("option:checked").attr("data-fax");
            NPI = $(this).find("option:checked").attr("data-NPI");
        }
        $("input[name='ao_dr_phone']").val(phone);
        $("input[name='ao_dr_fax']").val(fax);
        $("input[name='ao_dr_NPI']").val(NPI);
    });
    $("body").on("change", "select.ao_procedure_list", function(){
        var ths = $(this);
        var procedure_id = $(this).val();
        console.log(procedure_id);
        $.ajax({
            url: BASE_URL + "admin/procedure/get_procedure_info_via_id",
            type:"POST",
            data:{
                id:procedure_id
            },
            success:function(res){
                console.log(res);
                var obj = res;// JSON.parse(res);
                if(obj.status == '1'){
                    // Keep all ICD10 codes available - don't filter by procedure
                    // The symptom dropdowns already have all ICD10 codes loaded from the page
                    // Users can select any ICD10 code regardless of procedure
                    
                    // Optional: If you want to highlight/suggest related symptoms, 
                    // you can add a comment or special class to related options here
                    
                    if (typeof procedure_updated_callback === 'function') {
                        procedure_updated_callback(ths);
                    }
                }
            }
        });
    });

    //clear button
    jQuery(".clear").click(function () {
        if(confirm("Do you want clear?")){
            var clear_value = jQuery("input[name='ao_ordered_address']").val('');
        }
    })

    //patient search

    jQuery(".patient_search").click(function(){

        var lastname = jQuery("input[name='ao_last_name']").val();
        var dob = jQuery("input[name='ao_dom']").val();
        var patientmr = jQuery("input[name='ao_patient_mr']").val();

        if (!lastname && !dob && !patientmr) {
            alert("Please enter Last Name, DOB, or Patient MR to search.");
            return;
        }

        var params = {
            "lastname" : lastname,
            "dob" : dob,
            "patientmr" : patientmr
        };

        var url = BASE_URL + "admin/order/get_search_result";
        jQuery.post(url, params, function(res) {

            if(res.status == "1" ) {

                var patient_num = res.list.length;
                jQuery(".patient_search_result").css('display', 'inline-block');
                jQuery(".patient_search_result").html("Results " + patient_num + " Members");

            } else {
                alert("No match!");
            }

        });
    });
    
    //patient show
    jQuery(".patient_search_result").click(function () {

        jQuery("#patient_search_result_modal").modal('show');

        var lastname = jQuery("input[name='ao_last_name']").val();
        var dob = jQuery("input[name='ao_dom']").val();
        var patientmr = jQuery("input[name='ao_patient_mr']").val();

        var params = {
            "lastname": lastname,
            "dob": dob,
            "patientmr": patientmr
        };

        var url = BASE_URL + "admin/order/get_search_result";
        jQuery.post(url, params, function (res) {

            if (res.status == "1") {
                var patient_num = res.list.length;
                var html = '';
                for (let i =0; i < patient_num;i++){
                    //console.log(res.list[i]["LAST_NAME"]);
                    html += '<tr class="select">'+'<td>' + i + '</td>' + '<td>' + res.list[i]["LAST_NAME"] + '</td>' + '<td>' + res.list[i]["FIRST_NAME"] + '</td>' + '<td>' + res.list[i]["SUFFIX"] + '</td>' + '<td>' + res.list[i]["PATIENT_MRN"] + '</td>' + '<td>' + res.list[i]["DOB"] + '</td>' + '<td>' + res.list[i]["PATIENT_NAME2"] + '</td>' + '<td>' + res.list[i]["GENDER"] + '</td>' + '</tr>';
                    jQuery("#order_search_tb tbody").html(html);
                }

            } else {
                console.log("No match!");
            }

        });
    });
    //select search content
    jQuery("#order_search_tb").on('click', 'tbody tr', function () {

        var lastname = jQuery(this).children("td:eq(1)").html();
        var firstname = jQuery(this).children("td:eq(2)").html();
        var suffix = jQuery(this).children("td:eq(3)").html();
        var patientmr = jQuery(this).children("td:eq(4)").html();
        var dob = jQuery(this).children("td:eq(5)").html();
        var patientssn = jQuery(this).children("td:eq(6)").html();
        var gender = jQuery(this).children("td:eq(7)").html();

        jQuery("input[name='ao_last_name']").val(lastname);
        jQuery("input[name='ao_first_name']").val(firstname);
        jQuery("input[name='ao_suffix_name']").val(suffix);
        jQuery("input[name='ao_patient_mr']").val(patientmr);
        jQuery("input[name='ao_dom']").val(dob);
        jQuery("input[name='ao_patient_ssn']").val(patientssn);
        jQuery("select[name='ao_sex']").val(gender);
        jQuery("#patient_search_result_modal").modal('hide');
        // window.location.href= BASE_URL + "admin/order/add";
    })
}
// ========================================
// Workflow Tracking Functions
// ========================================

jQuery(document).ready(function(){
    
    // Add Facility button handler
    $("body").on("click", ".add-facility-btn", function() {
        var $btn = $(this);
        // Walk up to the dialog content pane; fall back to global .jconfirm
        var $dlg = $btn.closest(".jconfirm-content");
        if (!$dlg.length) { $dlg = $btn.closest(".jconfirm"); }
        if (!$dlg.length) { $dlg = $("body"); }
        var facilityName    = ($dlg.find("input[name='quick_facility_name']").val()    || '').trim();
        var facilityType    = ($dlg.find("select[name='quick_facility_type']").val()   || '');
        var facilityAddress = ($dlg.find("input[name='quick_facility_address']").val() || '').trim();
        var facilityCity    = ($dlg.find("input[name='quick_facility_city']").val()    || '').trim();
        var facilityState   = ($dlg.find("input[name='quick_facility_state']").val()   || '').trim();
        var facilityZip     = ($dlg.find("input[name='quick_facility_zip']").val()     || '').trim();
        var facilityPhone   = ($dlg.find("input[name='quick_facility_phone']").val()   || '').trim();
        var facilityFax     = ($dlg.find("input[name='quick_facility_fax']").val()     || '').trim();

        // Validate required fields
        if (!facilityName || !facilityType || !facilityAddress || !facilityCity || !facilityState || !facilityZip || !facilityPhone || !facilityFax) {
            $dlg.find(".add-facility-alert").removeClass("g_none_dis");
            $dlg.find(".add-facility-alert div").html("Please fill in all required fields (Name, Type, Address, City, State, Zip, Phone, Fax)");
            return false;
        }
        
        show_loading(true);
        $.ajax({
            url: BASE_URL + 'admin/facility/create_quick',
            type: 'POST',
            data: {
                facility_name: facilityName,
                facility_type: facilityType,
                address1: facilityAddress,
                address_city: facilityCity,
                address_state: facilityState,
                address_zip: facilityZip,
                phone: facilityPhone,
                fax: facilityFax
            },
            success: function(res) {
                show_loading(false);
                try {
                    var obj = (typeof res === 'string') ? JSON.parse(res) : res;
                    if(obj.status == '1') {
                        var facilityId = obj.facility_id;
                        var $select = $("select[name='ao_ordering_facility']");
                        var $existing = $select.find("option[value='" + facilityId + "']");
                        if ($existing.length === 0) {
                            var newOption = '<option value="' + facilityId + '">' + facilityName + '</option>';
                            var $notInList = $select.find("option[value='9999']");
                            if ($notInList.length) {
                                $notInList.before(newOption);
                            } else {
                                $select.append(newOption);
                            }
                        }

                        $select.val(facilityId).change();
                        if (window.facility_dlg) {
                            window.facility_dlg.close();
                        }
                        showToast(obj.message || 'Facility created successfully', 'success');
                    } else {
                        $dlg.find(".add-facility-alert").removeClass("g_none_dis");
                        $dlg.find(".add-facility-alert div").html(obj.message || 'Error creating facility');
                    }
                } catch(e) {
                    show_loading(false);
                    showToast('Error: ' + e.message, 'error');
                }
            },
            error: function() {
                show_loading(false);
                $dlg.find(".add-facility-alert").removeClass("g_none_dis");
                $dlg.find(".add-facility-alert div").html('Error communicating with server');
            }
        });
    });
    
    // Close facility dialog button
    $("body").on("click", ".btn-close-facility-dlg", function() {
        if (window.facility_dlg) {
            window.facility_dlg.close();
        }
        // Reset facility dropdown to blank
        $("select[name='ao_ordering_facility']").val('');
    });
    
    // Submit for Reading button
    jQuery('body').on('click', '.submit-reading-btn', function() {
        var order_id = jQuery(this).data('id');
        
        jQuery.confirm({
            title: 'Submit for Reading',
            content: '' +
            '<form action="" class="submit-reading-form">' +
            '<div class="form-group">' +
            '<label>Remarks (optional)</label>' +
            '<textarea class="form-control reading-remarks" rows="3"></textarea>' +
            '</div>' +
            '</form>',
            buttons: {
                submit: {
                    text: 'Submit',
                    btnClass: 'btn-primary',
                    action: function () {
                        var remarks = this.$content.find('.reading-remarks').val();
                        
                        show_loading(true);
                        jQuery.ajax({
                            url: BASE_URL + 'admin/order/submit_for_reading',
                            type: 'POST',
                            data: {
                                order_id: order_id,
                                remarks: remarks
                            },
                            success: function(res) {
                                show_loading(false);
                                var obj = JSON.parse(res);
                                if(obj.status == '1') {
                                    showToast(obj.message, 'success');
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 1000);
                                } else {
                                    showToast(obj.message, 'error');
                                }
                            },
                            error: function() {
                                show_loading(false);
                                showToast('An error occurred. Please try again.', 'error');
                            }
                        });
                    }
                },
                cancel: {
                    text: 'Cancel'
                }
            }
        });
    });
    
    // View Timeline button
    jQuery('body').on('click', '.view-timeline-btn', function() {
        var order_id = jQuery(this).data('id');
        
        show_loading(true);
        jQuery.ajax({
            url: BASE_URL + 'admin/order/get_status_history/' + order_id,
            type: 'GET',
            success: function(res) {
                show_loading(false);
                var obj = JSON.parse(res);
                if(obj.status == '1') {
                    showTimelineDialog(obj.history);
                } else {
                    showToast('Failed to load timeline', 'error');
                }
            },
            error: function() {
                show_loading(false);
                showToast('An error occurred while loading timeline', 'error');
            }
        });
    });
});

function showTimelineDialog(history) {
    var html = '<div class="timeline-wrapper" style="max-height: 500px; overflow-y: auto;">';
    html += '<table class="table table-bordered table-striped">';
    html += '<thead><tr>';
    html += '<th>Date/Time</th>';
    html += '<th>Action</th>';
    html += '<th>User</th>';
    html += '<th>Time in Status</th>';
    html += '</tr></thead><tbody>';
    
    if(history.length === 0) {
        html += '<tr><td colspan="4" class="text-center">No history found</td></tr>';
    } else {
        for(var i = 0; i < history.length; i++) {
            var h = history[i];
            var timeInStatus = '';
            if(h.time_in_status !== null && h.time_in_status !== undefined) {
                var minutes = parseInt(h.time_in_status);
                if(minutes < 60) {
                    timeInStatus = minutes + ' min';
                } else if(minutes < 1440) {
                    timeInStatus = Math.round(minutes / 60 * 10) / 10 + ' hrs';
                } else {
                    timeInStatus = Math.round(minutes / 1440 * 10) / 10 + ' days';
                }
            }
            
            html += '<tr>';
            html += '<td>' + (h.created_at_display || h.created_at || '-') + '</td>';
            html += '<td>' + (h.action || '-') + '</td>';
            html += '<td>' + (h.user_name || '-') + '</td>';
            html += '<td>' + timeInStatus + '</td>';
            html += '</tr>';
        }
    }
    
    html += '</tbody></table></div>';
    
    jQuery.confirm({
        title: 'Order Status Timeline',
        content: html,
        columnClass: 'large',
        buttons: {
            close: {
                text: 'Close',
                btnClass: 'btn-primary'
            }
        }
    });
}
