jQuery(function(){
   // procedure add page events
   procedure_add_page_events();
});

// procedure add page events
function procedure_add_page_events()
{
    // multi select
    if(jQuery("select[name='symptoms_slt']").length) {
        jQuery("select[name='symptoms_slt']").selectpicker();
    }

    // set symptoms value to hidden field
    jQuery("select[name='symptoms_slt']").change(function(){
        jQuery("input[name='symptoms']").val(jQuery(".filter-option-inner-inner").html());
    });
}