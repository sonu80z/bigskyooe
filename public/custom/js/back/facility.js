jQuery(function(){
   // add facility event proc function
   admin_facillity_add_proc_func();
});

/**
 * add facility event proc function
 * */
function admin_facillity_add_proc_func()
{
    // select division
    jQuery("select[name='af_divisions']").change(function(){
        var val = jQuery(this).val();
        if ( val == "0" ) {
            jQuery("select[name='af_subdivisions']").html('<option value="0">Select Subdivision</option>').prop("disabled", true);
            jQuery("select[name='af_regions']").html('<option value="0">Select Region</option>').prop("disabled", true);
            return;
        }
        var params = {
            "division" : val
        };

        var url = BASE_URL + "admin/facility/get_division_via_parent";
        jQuery.post(url, params, function(res) {
            if(res.status == "1" ) {
                var html = '<option value="0">Select Subdivision</option>';
                for ( i = 0; i < res.list.length; i ++ ) {
                    html += '<option value="' + res.list[i]["id"] + '">' + res.list[i]["name"] + '</option>';
                }
                jQuery("select[name='af_subdivisions']").html(html).removeAttr("disabled", "disabled");
            } else {
                jQuery("select[name='af_subdivisions']").html('<option value="0">Select Subdivision</option>').prop("disabled", true);
                jQuery("select[name='af_regions']").html('<option value="0">Select Region</option>').prop("disabled", true);
            }
        });
    });

    // select division
    jQuery("select[name='af_subdivisions']").change(function(){
        var val = jQuery(this).val();
        if ( val == "0" ) {
            jQuery("select[name='af_regions']").html('<option value="0">Select Region</option>').prop("disabled", true);
            return;
        }
        var params = {
            "division" : val
        };

        var url = BASE_URL + "admin/facility/get_division_via_parent";
        jQuery.post(url, params, function(res) {
            if(res.status == "1" ) {
                var html = '<option value="0">Select Subdivision</option>';
                for ( i = 0; i < res.list.length; i ++ ) {
                    html += '<option value="' + res.list[i]["id"] + '">' + res.list[i]["name"] + '</option>';
                }
                jQuery("select[name='af_regions']").html(html).removeAttr("disabled", "disabled");
            } else {
                jQuery("select[name='af_regions']").html('<option value="0">Select Subdivision</option>').prop("disabled", true);
            }
        });
    });

    // remove station
    jQuery(".ad_s_item_del").click(function(){
       jQuery(this).parent("div").parent("div").remove();

        set_station_info_to_hidden_field();
    });

    // add station
    jQuery(".ad_s_item_add").click(function(){

        var name = jQuery("input[name='af_s_name']").val();
        var phone = jQuery("input[name='af_s_phone']").val();
        var fax = jQuery("input[name='af_s_fax']").val();

        if ( name == "" ) {
            jQuery("input[name='af_s_name']").focus();
            return;
        }

        if ( phone == "" ) {
            jQuery("input[name='af_s_phone']").focus();
            return;
        }

        if ( fax == "" ) {
            jQuery("input[name='af_s_fax']").focus();
            return;
        }

        var html = '<div class="row ad_s_cnt_td">' +
                        '<div class="col-sm-3">' + name + '</div>' +
                        '<div class="col-sm-3">' + phone + '</div>' +
                        '<div class="col-sm-3">' + fax + '</div>' +
                        '<div class="col-sm-3"><input type="button" class="btn btn-xs btn-danger ad_s_item_del" value="Del" /></div>' +
                    '</div>';
        jQuery(".ad_s_cnt").append(html);
        jQuery("input[name='af_s_name']").val("");
        jQuery("input[name='af_s_phone']").val("");
        jQuery("input[name='af_s_fax']").val("");

        jQuery(".ad_s_item_del").click(function(){
            jQuery(this).parent("div").parent("div").remove();
        });

        set_station_info_to_hidden_field();
    });

    // set stations info to hidden field
    function set_station_info_to_hidden_field()
    {
        var val = '';
        jQuery(".ad_s_cnt_td").each(function(e){
            if ( val != "" ) val += "###";
            val += jQuery(this).children(":first-child").html();
            val += "&&&";
            val += jQuery(this).children(":first-child").html();
            val += "&&&";
            val += jQuery(this).children(":first-child").html();
        });
        jQuery("input[name='stations']").val(val);
    }
}