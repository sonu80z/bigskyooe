jQuery(function(){


   //  user add page proc func
   admin_user_add_proc_func();

   // user edit page proc func
    admin_user_edit_proc_func();

   // admin profile
    admin_profile_setting_proc_func();

});
//

/**
 * user add page proc func
 * */
function admin_user_add_proc_func()
{

    // multi select
    if(jQuery("select[id='a_u_a_facility']").length) {
        jQuery("select[id='a_u_a_facility']").selectpicker();
    }
    // add user
    jQuery(".a_add_user_btn").click(function(){
        // firstly confirm whether username is duplicate or not
        var username = jQuery("#a_u_a_username").val();
        if (!username || !username.trim()) {
            jQuery("#a_admin_add_alert").removeClass("g_none_dis");
            jQuery("#a_admin_add_alert").children("div").html("Username is required");
            jQuery("#a_u_a_username").focus();
            return;
        }
        //console.log(username);
        var params = {
            "username" : username
        };
        var url = BASE_URL + 'admin/users/confirm_admin_username';
        jQuery.post(url, params, function(res) {
            if (typeof res === 'string') {
                try {
                    res = JSON.parse(res);
                } catch (e) {
                    res = {};
                }
            }
            var status = (res && res.status !== undefined) ? parseInt(res.status, 10) : 0;
            if (status === 1) {
                jQuery("#a_admin_add_alert").removeClass("g_none_dis");
                jQuery("#a_admin_add_alert").children("div").html(res.msg || "Same username already exist");
                jQuery("#a_u_a_username").val("").focus();
                return;
            }

            // secondly confirm whether password match or not
            if (jQuery("#a_u_a_password").val() != jQuery("#a_u_a_rpassword").val() ) {
                jQuery("#a_admin_add_alert").removeClass("g_none_dis");
                jQuery("#a_admin_add_alert").children("div").html("Password do not match");
                jQuery("#a_u_a_rpassword").val("").focus();
                return;
            }
            jQuery("#a_add_user").find("input[name=submit]").trigger("click");
        }, 'json').fail(function(){
            jQuery("#a_admin_add_alert").removeClass("g_none_dis");
            jQuery("#a_admin_add_alert").children("div").html("Unable to validate username. Please try again.");
        });
    });
}

/**
 * user edit page proc func
 * */
function admin_user_edit_proc_func()
{

}

/**
 * admin profile
 * */
function admin_profile_setting_proc_func()
{
    // add new product gallery image
    jQuery("#c_t_p_add_gallery_image").click(function(){
        jQuery("#c_t_p_photo_upload_ipt").trigger("click");
    });
    jQuery("#c_t_p_photo_upload_ipt").change(function(){
        if ( jQuery(this).val() == "" || jQuery(this).val() == null ) return;
        jQuery("#c_t_p_photos_submit").trigger("click");
    });

    jQuery("#c_t_p_photos_submit").click(function(e){
        jQuery(this).parents("form").ajaxForm(options);
    });

    var options = {
        complete: function(response)
        {
            if(jQuery.isEmptyObject(response.responseJSON.error)){
                jQuery("#c_t_p_profile_image").val(response.responseJSON.success);
                jQuery("#c_t_p_add_gallery_image").attr("src", BASE_URL + 'uploads/profiles/' + response.responseJSON.success);
            }else{
                alert('Image Upload Error.');
            }
        }
    };

    // update security quesiton
    jQuery(".a_security_question_update_btn").click(function(){
       // var question = jQuery(this).parent("div").prev("div").prev("div").children("select").val();

        var id = jQuery(this).attr("did");
        var answer = jQuery(this).parent("div").prev("div").children("input").val();

        if ( jQuery(this).attr("is_admin") == "1" ) location.href = BASE_URL + "admin/profile/security_question_edit/" + id + "/" + answer;
        else location.href = BASE_URL + "users/profile/security_question_edit/" + id + "/" + answer;

    });

    // user`s security enable setting
    jQuery("#a_security_question_is_enable").click(function(){

        if ( parseInt(jQuery("#a_s_q_count").val()) < 1 ) {
            alert("Plese set one more security question firstly");
            jQuery(this).prop("checked", false);
            return;
        }
        var is_question = 0;
       if ( jQuery(this).is(":checked") ) {
           is_question = 1;
       }

        if ( jQuery(this).attr("is_admin") == "1" ) location.href = BASE_URL + "admin/profile/update_security_question_dstate/" + is_question;
        else location.href = BASE_URL + "users/profile/update_security_question_dstate/" + is_question;

    });

    // user`s 2fa enable setting
    jQuery("#a_2fa_is_enable").click(function(){

        var is_2fa = 0;
        if ( jQuery(this).is(":checked") ) {
            is_2fa = 1;
        }

        if ( jQuery(this).attr("is_admin") == "1" ) location.href = BASE_URL + "admin/profile/update_2fa_dstate/" + is_2fa;
        else location.href = BASE_URL + "users/profile/update_2fa_dstate/" + is_2fa;

    });

    // current password confirm in change password page 2018:09:11 by hmc
    jQuery("#a_p_c_password").keyup(function(){
        if ( jQuery(this).val() == "" ) return;
        var params = {
            "password" : jQuery(this).val()
        }
        url = BASE_URL + 'admin/users/confirm_admin_current_password';
        jQuery.post(url, params, function(res) {
            if(res.status == "1") {
                jQuery("#a_p_n_password, #a_p_cp_password").removeAttr("disabled");
            } else {
                jQuery("#a_p_n_password, #a_p_cp_password").prop("disabled", "disabled");
            }
        })
    }).blur(function(){
        jQuery(this).trigger("keyup");
    });
}
