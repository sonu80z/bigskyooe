jQuery(function(){
   // region setting page proc function
   admin_config_region();
   // cuisine management page proc function
    admin_config_cuisine_page_proc_func();

    // trader 2fa setting page proc function
    backend_manager_2fa_proc_func();
});


/**
 * region setting page proc function
 */
function admin_config_region()
{
    // add country
    jQuery("#a_c_r_country_add").prev("input").keypress(function(e){
        if ( e.which == 13 )
            jQuery("#a_c_r_country_add").trigger("click");
    });
    jQuery("#a_c_r_country_add").click(function(){
        var country = jQuery(this).prev("input").val();

        if ( g_v_t( country ) ) {
            jQuery(this).prev("input").focus();
            return;
        }

        var params = {
            "country" : country
        };
        //  set user`s online state
        var url = BASE_URL + "admin/config/region_add_country_via_ajax";
        jQuery.post(url, params, function(res) {
            if(res.status == "1" ) {
                alert("Country have been added successfully!");
                location.reload();
            } else {

            }
        });
    });

    // add country
    jQuery("#a_c_r_city_add").prev("input").keypress(function(e){
        if ( e.which == 13 )
            jQuery("#a_c_r_city_add").trigger("click");
    });
    jQuery("#a_c_r_city_add").click(function(){
        var country = jQuery(".a_c_r_county_clicked").attr("did");
        var city = jQuery(this).prev("input").val();

        if ( g_v_t( country ) ) {
            alert("Select country");
            return;
        }

        if ( g_v_t( city ) ) {
            jQuery(this).prev("input").focus();
            return;
        }

        var params = {
            "country" : country,
            "city" : city
        };
        //  set user`s online state
        var url = BASE_URL + "admin/config/region_add_city_via_ajax";
        jQuery.post(url, params, function(res) {
            if(res.status == "1" ) {
                alert("City have been added successfully!");
                location.reload();
            } else {

            }
        });
    });

    // add country
    jQuery("#a_c_r_area_add").prev("input").keypress(function(e){
        if ( e.which == 13 )
            jQuery("#a_c_r_area_add").trigger("click");
    });
    jQuery("#a_c_r_area_add").click(function(){
        var city = jQuery(".a_c_r_city_clicked").attr("did");
        var area = jQuery(this).prev("input").val();

        if ( g_v_t( city ) ) {
            alert("Select city");
            return;
        }

        if ( g_v_t( area ) ) {
            jQuery(this).prev("input").focus();
            return;
        }

        var params = {
            "city" : city,
            "area" : area
        };
        //  set user`s online state
        var url = BASE_URL + "admin/config/region_add_area_via_ajax";
        jQuery.post(url, params, function(res) {
            if(res.status == "1" ) {
                alert("Area have been added successfully!");
                location.reload();
            } else {

            }
        });
    });
}

/**
 * cuisine management page proc function
 * */
function admin_config_cuisine_page_proc_func()
{
    var g_is_gallery_first = true;

    jQuery(".a_c_cm_add_img").click(function(){
        jQuery("#a_c_cm_photo_upload_ipt1").trigger("click");
        g_is_gallery_first = true;
    });

    jQuery("#a_c_cm_photo_upload_ipt1").change(function(){
        jQuery("#a_c_cm_photos_submit1").trigger("click");
    });

    jQuery("#a_c_cm_photos_submit1").click(function(e){
        jQuery(this).parents("form").ajaxForm(options);
    });

    var options = {
        complete: function(response)
        {
            if ( g_is_gallery_first ) {
                if(jQuery.isEmptyObject(response.responseJSON.error)){
                    jQuery(".a_c_cm_cuisine_img").val(response.responseJSON.success);
                    jQuery(".a_c_cm_add_img").attr("src", BASE_URL + "uploads/config/" + response.responseJSON.success);
                }else{
                    alert('Image Upload Error.');
                }
            }


        }
    };
}

// trader 2fa setting page proc function
function backend_manager_2fa_proc_func()
{

    // toggle show detail password confirm dialog
    jQuery(".f_2fa_detail_txt").click(function(){
        if ( jQuery(".f_confirm_dv").hasClass("g_none_dis") ) {
            jQuery(".f_confirm_dv").removeClass("g_none_dis");
        } else {
            jQuery(".f_confirm_dv").addClass("g_none_dis");
        }
    });

    // close confirm dialog
    jQuery("#f_2fa_pass_cancel_btn").click(function(){
        jQuery("#f_2fa_confirm_password").val("");
        jQuery(".f_confirm_dv").addClass("g_none_dis");
    });

    jQuery("#f_2fa_confirm_password").keypress(function(e){
        if ( e.which == 13 ) {
            jQuery("#f_2fa_pass_confirm_btn").trigger("click");
        }
    });

    // confirm trader
    jQuery("#f_2fa_pass_confirm_btn").click(function(){
        var is_2fa = jQuery(this).attr("state");
        var pass = jQuery("#f_2fa_confirm_password").val();
        if ( pass == "" ) {
            jQuery("#f_2fa_confirm_password").focus();
            return;
        }
        var params = {
            "pass" : pass
        };
        var url = BASE_URL + "users/profile/confirm_user_password";
        jQuery.post(url, params, function(res) {
            if(res.status == "1" ) {
                jQuery("#f_2fa_confirm_password").val("");
                //if ( is_2fa == "0" ) {   // disable 2fa
                    jQuery("#c_2fa_enable_modal_btn").trigger("click");
                //} else {                // enable 2fa
                //    jQuery("#c_2fa_disable_modal_btn").trigger("click");
                //}
            } else {
                alert("Do not match password");
            }
        });
    });

    // enable 2fa
    jQuery("#c_2fa_confirm_submit_btn").click(function(){

        var token = jQuery("#c_2fa_enable_confirm_digit").val();
        if ( token == "" || token == undefined ) {
            jQuery("#c_2fa_enable_confirm_digit").focus();
            return;
        }

        var params = {
            "token" : token
        };
        //  set user`s online state
        var url = BASE_URL + "users/profile/confirm_2fa_enable";
        jQuery.post(url, params, function(res) {
            if(res.status == "1" ) {
                location.href = BASE_URL + "users/profile/auth_setting";
            } else {
                alert("2FA do not match. Please try again!");
                jQuery("#c_2fa_enable_confirm_digit").val("").focus();
            }
        });

    });

    // disable 2fa
    jQuery("#c_2fa_disable_submit_btn").click(function(){
        if( !confirm( "Would you like to disable the 2FA?") ) return;
        location.href = BASE_URL + "users/profile/update_2fa_dstate/" + 0;
    });
}