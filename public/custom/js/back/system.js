jQuery(function(){
   // system log event function
   admin_system_logs_event_func();


});

/**
 * system log event function
 */
function admin_system_logs_event_func()
{
   jQuery("select[name='log_year'], select[name='logs_month'], select[name='logs_day'], select[name='logs_page_num']").change(function(){
      jQuery("input[name='logs_search_button']").trigger("click");
   });

   jQuery("input[name='log_type']").click(function(){
       jQuery("input[name='logs_search_button']").trigger("click");
   });

   jQuery("input[name='logs_actor_username'], input[name='logs_action_tag'], input[name='logs_to']").keypress(function(e){
      if ( e.which == 13 ) {
          jQuery("input[name='logs_search_button']").trigger("click");
      }
   });

    jQuery("input[name='logs_previous_button']").click(function(){
      var page = parseInt(jQuery("input[name='al_page']").val()) - 1;
      jQuery("select[name='logs_page_num']").val(page);
        jQuery("input[name='logs_search_button']").trigger("click");
    });

    jQuery("input[name='logs_next_button']").click(function(){
        var page = parseInt(jQuery("input[name='al_page']").val()) + 1;
        jQuery("select[name='logs_page_num']").val(page);
        jQuery("input[name='logs_search_button']").trigger("click");
    });

    jQuery("input[name='logs_search_button']").click(function(){
        jQuery("input[name='al_actor_type']").val(jQuery("input[name='log_type']:checked").val());
        jQuery("input[name='al_year']").val(jQuery("select[name='log_year']").val());
        jQuery("input[name='al_month']").val(jQuery("select[name='logs_month']").val());
        jQuery("input[name='al_day']").val(jQuery("select[name='logs_day']").val());
        jQuery("input[name='al_actor_username']").val(jQuery("input[name='logs_actor_username']").val());
        jQuery("input[name='al_action_tag']").val(jQuery("input[name='logs_action_tag']").val());
        jQuery("input[name='al_to']").val(jQuery("input[name='logs_to']").val());
        jQuery("input[name='al_page']").val(jQuery("select[name='logs_page_num']").val());
        jQuery("input[name='submit']").trigger("click");
    });

    // export logs as csv file and force download
    jQuery("#a_export_logs").click(function(){

        location.href = BASE_URL + "admin/export/export_logs_as_csv?" +
            "al_actor_type=" + jQuery("input[name='al_actor_type']").val() +
            "&al_year=" + jQuery("input[name='al_year']").val() +
            "&al_month=" + jQuery("input[name='al_month']").val() +
            "&al_day=" + jQuery("input[name='al_day']").val() +
            "&al_actor_username=" + jQuery("input[name='al_actor_username']").val() +
            "&al_action_tag=" + jQuery("input[name='al_action_tag']").val() +
            "&al_to=" + jQuery("input[name='al_to']").val()
    });
}

