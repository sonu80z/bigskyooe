jQuery(function(){
    // global events
    global_event();
});

/**
 * global events
 * */
function global_event()
{
    // multi select
    jQuery(".select2").select2();

    // diaplay states when selecet country
    jQuery("select[name=a_u_a_country]").change(function(){
        var country = jQuery(this).val();

        var params = {
            "country" : country
        };

        var url = BASE_URL + "admin/users/get_states_via_country";
        $.post(url, params, function(res) {
            if(res.status == "1" ) {
                var html = "";

                var lst = res.list;
                for ( i = 0; i < lst.length; i ++ ) {
                    html += '<option value="' + lst[i]["id"] + '">' + lst[i]["title"] + '</option>';
                }

                jQuery("select[name=a_u_a_state]").html(html);
                jQuery("select[name=a_u_a_city]").html('<option disabled selected>Please select city</option>');

            } else {
                jQuery("select[name=a_u_a_state]").html('<option disabled selected>Please select state</option>');
                jQuery("select[name=a_u_a_city]").html('<option disabled selected>Please select city</option>');
            }
        });

    });

    // diaplay cities when selecet state
    jQuery("select[name=a_u_a_state]").change(function(){
        var state = jQuery(this).val();

        var params = {
            "state" : state
        };

        var url = BASE_URL + "admin/users/get_cities_via_state";
        $.post(url, params, function(res) {
            if(res.status == "1" ) {
                var html = "";

                var lst = res.list;
                for ( i = 0; i < lst.length; i ++ ) {
                    html += '<option value="' + lst[i]["id"] + '">' + lst[i]["title"] + '</option>';
                }

                jQuery("select[name=a_u_a_city]").html(html);

            } else {
                jQuery("select[name=a_u_a_city]").html('<option disabled selected>Please select city</option>');
            }
        });
    });

    /**
     * validate number input value
     * it’s split into 2 columns, left and right, with a decimal in the middle
     * When the trader clicks on the field they initially type into the left column (left of the decimal).
     * They then would hit either “,” or “.” which would shift the cursor to the right column.
     * They will then type into the right column to enter the fractional value
     * */
    jQuery(".validate_number_value").keyup(function(e){
        var key = e.which;
        var val = jQuery(this).val();

        // if key press enter
        if ( key == 13 ) {
            var decimal = parseInt(jQuery(this).attr("decimal"));
            jQuery(this).val(point_to_comma_innum(parseFloat(comma_to_point_innum(val)).toFixed(decimal)));
            return;
        }

        // if special key, return
        if ( key >= 0 && key < 32 ) return;

        // if init key is space, zero, comma and dot, reeturn
        if ( val.length == 1 && ( ( key > 57 || key < 48 ) ) ) {
            jQuery(this).val("").focus();
            return;
        }

        // if entered key is not number, space, comma and dot, then return ( key == 32 || : space)
        if ( ( key < 58 && key > 47 ) || key == 188 ) { // number, space, comma, point
            return;
        } else if ( key == 190 ) { // if key is dot then change as comma
            jQuery(this).val(val.substr( 0, val.length - 1 ) + ",").focus();
        } else {
            jQuery(this).val(val.substr( 0, val.length - 1 )).focus();
            return;
        }
    }).blur(function(){
        var val = jQuery(this).val();
        if ( val == "" ) return;

        var decimal = parseInt(jQuery(this).attr("decimal"));
        jQuery(this).val(point_to_comma_innum(parseFloat(comma_to_point_innum(val)).toFixed(decimal)));
        return;
    });
}

/**
 * string validate
 * @param val: entered val (input, select, textarea etc...)
 * @return 0=>valid, 1=>invalid
 * */
function g_v_t(val)
{
    if ( val == "" || val == undefined || val == null )
        return true;
    else return false;
}

/**
 * select value validate
 * @param val: entered val (input, select, textarea etc...)
 * @return 0=>valid, 1=>invalid
 * */
function g_v_ts(val)
{
    if ( val == "" || val == undefined || val == null || val == "0" )
        return true;
    else return false;
}

/**
 * number validate
 * @param val: entered value
 * @return 0=>valid, 1=>invalid
 * */
function g_v_n(val)
{
    if ( isNaN(val) ) return true;
    else return false;
}

/**
 * phone number format
 * @param str: phone number
 * @return 0=>valid, 1=>invalid
 * */
function g_v_p(str)
{
    if ( str == undefined || str == "" || str == null ) return true;
    var strs = str.split("");
    if ( strs.length < 10 || strs.length > 13 ) return true;
    if ( isNaN(str)) return true;
    return false;
}

/**
 * date validate
 * @param str: date (yy-mm-dd)
 * @return 0=>valid, 1=>invalid
 * */
function g_v_d(str)
{
    if ( str == undefined || str == "" || str == null ) return true;
    var strs = str.split("-");
    if ( strs.length != 3 ) return true;
    if ( isNaN(strs[0]) || isNaN(strs[0]) || isNaN(strs[0]) ) return true;
    if ( parseInt(strs[0]) < 1900 || parseInt(strs[0]) > 2017 ) return true;
    if ( parseInt(strs[1]) < 1 || parseInt(strs[1]) > 12 ) return true;
    if ( parseInt(strs[2]) < 1 || parseInt(strs[2]) > 31 ) return true;
    return false;
}

/**
 * time validate
 * @param str: time (hh:mm)
 * @return 0=>valid, 1=>invalid
 * */
function g_v_t_hhmm(str)
{
    if ( str == undefined || str == "" || str == null ) return true;
    var strs = str.split(":");
    if ( strs.length != 2 ) return true;
    if ( isNaN(strs[0]) || isNaN(strs[0]) || isNaN(strs[0]) ) return true;
    if ( parseInt(strs[0]) < 0 || parseInt(strs[0]) > 23 ) return true;
    if ( parseInt(strs[1]) < 0 || parseInt(strs[1]) > 59 ) return true;
    return false;
}

/**
 * verify email foramt
 * @param str :email string (email@param.com)
 * @return 0=>valid, 1=>invalid
 * */
function g_v_e(str)
{
    if ( str == undefined || str == "" || str == null ) return true;
    var strs = str.split("@");
    if ( strs.length < 2 ) return true;
    var strs = str.split(".");
    if ( strs.length < 2 ) return true;
    return false;
}

/**
 * return valid value
 * @param val: entered val (input, select, textarea etc...)
 * @return valid value
 * */
function g_ret_val_val(val)
{
    if ( val == "" || val == undefined || val == null || val == "0" )
        return "";
    else return val;
}

/**
 * send eamil function
 * @param from: from eamil address
 * @param to: destination eamil address
 * @param subject: email subject
 * @param body: email content
 * */
function g_email_send( from, to, subject, body)
{
    // email send
    Email.send(
        from,
        to,
        subject,
        body,
        smtp_email,
        smtp_username,
        smtp_password
    );
}

/**
 * replace comma to point in number
 * @param num: contain comma
 * @return ret: replaced comma to point
 * */
function comma_to_point_innum(num)
{
    return num.replace(",", ".");
}

/**
 * replace comma to point in number
 * @param num: contain point
 * @return ret: replaced point to comma
 * */
function point_to_comma_innum(num)
{
    return num.replace(".", ",");
}