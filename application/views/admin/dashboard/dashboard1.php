<!-- Main content -->
<section class="content">
    <?php if($this->session->has_userdata('is_admin_login')) { ?>
    <div class="row a_dashboard_row">
        <div class="col-md-3">
            <div class="box box-primary collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title">Orders by State</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div id="chart_order_state" style="height: 300px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-primary collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title">Orders by Division</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div id="chart_order_division" style="height: 300px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-primary collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title">Orders by Order Type</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div id="chart_order_type" style="height: 300px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-primary collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title">Order by Modality</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div id="chart_order_modality" style="height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <?php
    $adv_facility = '';
    $adv_patient_id = '';
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="box-body table-responsive a_dsh_main_cnt">
                <div class="box box-primary collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Advanced Search</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="a_dashboard_order_search_dv">
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Patient Name</label>
                                    <input type="text" class="g_ipt" id="adv_search_name" placeholder="Last, First" />
                                </div>
                                <div class="col-md-3">
                                    <label>Patient MR</label>
                                    <input type="text" class="g_ipt" id="adv_patient_id" />
                                </div>
                                <div class="col-md-3">
                                    <label>DOB</label>
                                    <input type="text" class="g_ipt" id="adv_search_dob" placeholder="mm/dd/yyyy" readonly />
                                </div>
                                <div class="col-md-3">
                                    <label>Facility</label>
                                    <select class="g_ipt" id="adv_facility">
                                        <option value="" selected disabled>Select Facility</option>
                                        <?php
                                        foreach ($facilities as $f) {
                                            echo '<option value="'.$f['id'].'">'.$f['facility_name'].'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 15px;">
                                <div class="col-md-3">
                                    <label>Order Or Exam Date Range</label>
                                    <div class="row">
                                        <div class="col-md-4" style="padding-right:2px; position:relative;">
                                            <input type="text" class="g_ipt" id="adv_date_from" placeholder="From" readonly style="padding-right:20px;" />
                                            <span class="adv-date-clear" data-target="adv_date_from" title="Clear" style="position:absolute;right:6px;top:50%;transform:translateY(-50%);cursor:pointer;color:#aaa;font-size:15px;">&times;</span>
                                        </div>
                                        <div class="col-md-4" style="padding-left:2px; padding-right:2px; position:relative;">
                                            <input type="text" class="g_ipt" id="adv_date_to" placeholder="To" readonly style="padding-right:20px;" />
                                            <span class="adv-date-clear" data-target="adv_date_to" title="Clear" style="position:absolute;right:6px;top:50%;transform:translateY(-50%);cursor:pointer;color:#aaa;font-size:15px;">&times;</span>
                                        </div>
                                        <div class="col-md-4" style="padding-left:2px;">
                                            <select class="g_ipt" id="adv_date_type">
                                                <option value="order">Order</option>
                                                <option value="exam">Exam</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label>Division</label>
                                    <select class="g_ipt" id="adv_division">
                                        <option value="" selected disabled>Select division</option>
                                        <?php if (!empty($divisions)) {
                                            foreach ($divisions as $division) {
                                                echo '<option value="'.$division['id'].'">'.$division['name'].'</option>';
                                            }
                                        } ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>State</label>
                                    <select class="g_ipt" id="adv_state">
                                        <option value="" selected disabled>Select state</option>
                                        <?php if (!empty($states)) {
                                            foreach ($states as $state) {
                                                echo '<option value="'.$state['fldSt'].'">'.$state['fldState'].'</option>';
                                            }
                                        } ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Modality</label>
                                    <select class="g_ipt" id="adv_modality">
                                        <option value="" selected disabled>Select modality</option>
                                        <option value="DX">DX</option>
                                        <option value="US">US</option>
                                        <option value="EKG">EKG</option>
                                        <option value="ECHO">ECHO</option>
                                        <option value="LINE PLACEMENT">LINE PLACEMENT</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 15px;">
                                <div class="col-md-3">
                                    <label>Order Status</label>
                                    <?php
                                    $order_status_list = array(
                                        0 => 'New',
                                        1 => 'N-STAT',
                                        2 => 'N-STAT-EMR',
                                        3 => 'N-ASAP',
                                        4 => 'N-ASAP-EMR',
                                        5 => 'N-Routine',
                                        6 => 'N-Routine-EMR',
                                        10 => 'D-Stat',
                                        11 => 'D-ASAP',
                                        12 => 'D-Routine',
                                        15 => 'order accepted',
                                        16 => 'order declined',
                                        18 => 'Delayed',
                                        20 => 'inroute',
                                        21 => 'arrived on site',
                                        25 => 'startProceedure',
                                        26 => 'endProceedure',
                                        30 => 'left site',
                                        35 => 'inPACS',
                                        40 => 'sent to Radiologist',
                                        41 => 'Radiologist requires more info',
                                        42 => 'Rad Consult reqested',
                                        45 => 'Prelim received',
                                        46 => 'results received',
                                        50 => 'Results sent to fac',
                                        51 => 'Results sent to EMR',
                                        52 => 'Results sent to OP',
                                        53 => 'Results sent to Other',
                                        60 => 'coded',
                                        70 => 'sent to billing',
                                        71 => 'ack from Billing',
                                        100 => 'Marked as EOS',
                                        999 => 'Cancelled'
                                    );
                                    ?>
                                    <select class="g_ipt" id="adv_status">
                                        <option value="" selected disabled>Select status</option>
                                        <?php foreach ($order_status_list as $status_key => $status_label) {
                                            echo '<option value="'.$status_key.'">'.$status_label.'</option>';
                                        } ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Time</label>
                                    <select class="g_ipt" id="adv_time">
                                        <option value="today" selected>Today</option>
                                        <option value="all">All</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Order Type</label>
                                    <div>
                                        <label style="display: inline-block; margin-right: 10px;"><input type="checkbox" id="nh_chk" value="1" /> NH</label>
                                        <label style="display: inline-block; margin-right: 10px;"><input type="checkbox" id="hb_chk" value="3" /> HB</label>
                                        <label style="display: inline-block; margin-right: 10px;"><input type="checkbox" id="cf_chk" value="2" /> CF</label>
                                        <label style="display: inline-block;"><input type="checkbox" id="lab_chk" value="4" /> LAB</label>
                                    </div>
                                </div>
                                <div class="col-md-3 text-right" style="padding-top: 20px;">
                                    <button class="btn btn-sm btn-primary" id="adv_search_btn" type="button">Search</button>
                                    <button class="btn btn-sm btn-danger" id="adv_reset_btn" type="button">Reset</button>
                                    <button class="btn btn-sm btn-success" id="adv_export_btn" type="button"><i class="fa fa-download"></i> Export Results</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row a_order_multi_action">
                    <div class="col-md-4">
                        <select class="g_ipt">
                            <option selected disabled>Select Action</option>
                            <option value="0">Dispatch</option>
                            <option value="1">Mark Completed</option>
                            <option value="2">Sent To</option>
                        </select>
                    </div>
                    <div class="col-md-8 g_txt_right">
                        <a href="<?=base_url();?>admin/order/add?from=dashboard" class="btn btn-sm btn-info">Add New Order</a>
                    </div>
                </div>
                <table id="dashboard_orders_tb" class="table table-bordered a_user_list_tb" width="100%">
                    <thead>
                    <tr>
                        <th><input type="checkbox" /></th>
                        <th>Status</th>
                        <th>Time in Status</th>
                        <th>Order date</th>
                        <th>Schedule Date</th>
                        <th>Patient MR</th>
                        <th>Patient Name</th>
                        <th>Facility</th>
                        <th>Tech</th>
                        <th>Procedure/X-ray</th>
                        <th style="min-width: 220px;">Action</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th><input type="date" class="g_ipt_full" /></th>
                        <th><input type="date" class="g_ipt_full" /></th>
                        <th><input type="text" class="g_ipt_full" /></th>
                        <th><input type="text" class="g_ipt_full" /></th>
                        <th>
                            <select class="g_ipt_full">
                                <option value="">All</option>
                                <?php foreach($facilities as $facility) { ?>
                                    <option value="<?php echo $facility['facility_name']; ?>"><?php echo $facility['facility_name']; ?></option>
                                <?php } ?>
                            </select>
                        </th>
                        <th>
                            <select class="g_ipt_full">
                                <option value="">All</option>
                                <?php foreach($technologist as $tech) { ?>
                                    <option value="<?php echo $tech['lastname'].' '.$tech['firstname']; ?>"><?php echo $tech['lastname'].' '.$tech['firstname']; ?></option>
                                <?php } ?>
                            </select>
                        </th>
                        <th><input type="text" class="g_ipt_full" /></th>
                        <th><button class="btn btn-sm btn-primary search-btn">Search</button></th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</section>

<div class="visibility-hidden">
    <div class="dispatch-dlg-container">
        <div class="dispatch-dlg" style="padding: 20px 20px 0 20px">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group text-left">
                        <div class="form-label">Date/Time</div>
                        <div class="">
                            <input type="text" class="form-control date_time dispatch_date_time" name="dispatch_date_time" value="<?php //echo date("m/d/Y H:i", time()); ?>">
                            <!--https://www.jqueryscript.net/time-clock/Time-Picker-jQuery-UI-Datepicker.html-->
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group text-left">
                        <div class="form-label">Technologist</div>
                        <div class="">
                            <select class="form-control technologist" name="technologist">
                                <option value="">Select technologist</option>
                                <?php
                                foreach($technologist as $key => $info){
                                    ?>
                                    <option value="<?php echo $info['id']; ?>"><?php echo $info['lastname'].' '.$info['firstname']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12" style="margin-top: 45px">
                    <div class="form-group text-center">
                        <button class="btn btn-primary btn-dispatch-submit" type="button">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mark-dlg-container">
        <div class="mark-dlg" style="padding: 20px 20px 0 20px">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group text-left">
                        <div class="form-label">Date/Time</div>
                        <div class="">
                            <input type="text" class="form-control date_time mark_date_time" name="mark_date_time" value="<?php //echo date("m/d/Y H:i", time()); ?>">
                            <!--https://www.jqueryscript.net/time-clock/Time-Picker-jQuery-UI-Datepicker.html-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="margin-top: 45px">
                    <div class="form-group text-center">
                        <button class="btn btn-primary btn-mark-submit" type="button">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="note-dlg-container">
        <div class="note-dlg" style="padding: 20px 20px 0 20px">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive-lg">
                        <table id="my_datatable" class="display table table-bordered table-striped table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Note</th>
                                <th>Attachment</th>
                                <th>Created by</th>
                                <th>Created at</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="margin-top: 15px">
                    <div class="form-group text-center">
                        <button class="btn btn-primary btn-add-note" type="button">Add Note</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="edit-note-dlg-container">
        <div class="edit-note-dlg" style="padding: 20px 20px 0 20px">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group text-left">
                        <div class="form-label">Note</div>
                        <div class="">
                            <textarea class="form-control note-text" rows="6" style="resize: none"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group text-left">
                        <div class="form-label">Attachment (PDF only)</div>
                        <div class="">
                            <input type="file" class="form-control note-attachment" accept=".pdf,application/pdf">
                            <small class="form-text text-muted">Upload a PDF file (optional)</small>
                            <div class="current-attachment" style="margin-top: 10px; display: none;">
                                <strong>Current attachment: </strong>
                                <a href="#" class="current-attachment-link" target="_blank"></a>
                                <button type="button" class="btn btn-xs btn-danger remove-attachment" style="margin-left: 10px;">Remove</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="margin-top: 25px">
                    <div class="form-group text-center">
                        <button class="btn btn-primary btn-note-update" type="button">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="delete-order-dlg-container">
        <div class="delete-order-dlg" style="padding: 20px 20px 0 20px">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group text-left">
                        <div class="form-label">Date/Time</div>
                        <div class="">
                            <input type="text" class="form-control date_time order_cancel_datetime" name="order_cancel_datetime" readonly value="">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group text-left">
                        <div class="form-label">Reason for Cancelation</div>
                        <div class="">
                            <textarea class="form-control reason_for_cancel" rows="3" style="resize: none"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group text-left">
                        <label class="">
                            <input type="checkbox" name="reschedule" class="reschedule" value="1"> <span>Need to reschedule?</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="margin-top: 20px">
                    <div class="form-group text-center">
                        <button class="btn btn-primary btn-cancel-submit" type="button">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Flot charts loaded via page_plugins in layout.php -->
<!-- DataTables loaded in layout.php -->
<!-- dashboard.js loaded via page_js in layout.php -->

<script>
(function($) {
    'use strict';

    // Initialize DataTable with AJAX data source - no server-side HTML rendering needed
    var ordersTable = $('#dashboard_orders_tb').DataTable({
        ajax: {
            url: BASE_URL + 'admin/dashboard/ajax_orders',
            type: 'GET',
            data: function(d) {
                d.adv_search_name = $('#adv_search_name').val() || '';
                d.adv_patient_id = $('#adv_patient_id').val() || '';
                d.adv_search_dob = $('#adv_search_dob').val() || '';
                d.adv_date_from = $('#adv_date_from').val() || '';
                d.adv_date_to = $('#adv_date_to').val() || '';
                d.adv_date_type = $('#adv_date_type').val() || 'order';
                d.adv_facility = $('#adv_facility').val() || '';
                d.adv_division = $('#adv_division').val() || '';
                d.adv_state = $('#adv_state').val() || '';
                d.adv_modality = $('#adv_modality').val() || '';
                d.adv_status = $('#adv_status').val() || '';
                d.adv_time = $('#adv_time').val() || 'today';
                var types = [];
                if ($('#nh_chk').prop('checked')) types.push('1');
                if ($('#cf_chk').prop('checked')) types.push('2');
                if ($('#hb_chk').prop('checked')) types.push('3');
                if ($('#lab_chk').prop('checked')) types.push('4');
                d.adv_order_types = types.join(',');
            }
        },
        responsive: true,
        deferRender: true,
        orderCellsTop: true,
        fixedHeader: true,
        order: [[3, 'desc']],
        columnDefs: [
            { orderable: false, targets: [0, 10] }
        ],
        search: {
            smart: true,
            regex: false,
            caseInsensitive: true
        }
    });

    $(document).ready(function(){
        var dispatch_dlg, mark_dlg, note_dlg, note_item_dlg, delete_order_dlg;
        var selected_order_id = 0;
        var $ordersTable = $('#dashboard_orders_tb');

        $(".date_time").attr("value", getFormattedDate('m/d/Y H:i'));

        // Datepicker initialization for advanced search
        $('#adv_search_dob').datepicker({ dateFormat: 'mm/dd/yy', changeMonth: true, changeYear: true, yearRange: '1900:+0' });
        $('#adv_date_from').datepicker({
            changeMonth: true, changeYear: true,
            onSelect: function(dateText) {
                $('#adv_date_to').datepicker('option', 'minDate', dateText);
            }
        });
        $('#adv_date_to').datepicker({
            changeMonth: true, changeYear: true,
            onSelect: function(dateText) {
                $('#adv_date_from').datepicker('option', 'maxDate', dateText);
            }
        });

        // Clear date buttons
        $(document).on('click', '.adv-date-clear', function() {
            var target = $(this).data('target');
            $('#' + target).val('');
            if (target === 'adv_date_from') {
                $('#adv_date_to').datepicker('option', 'minDate', null);
            } else {
                $('#adv_date_from').datepicker('option', 'maxDate', null);
            }
        });

        // Expand/collapse — refresh datepickers so they position correctly
        $('[data-widget="collapse"]').on('click', function() {
            setTimeout(function() {
                $('#adv_date_from, #adv_date_to, #adv_search_dob').datepicker('refresh');
            }, 350);
        });

        // Advanced search - reload data from server with current filter values
        $('#adv_search_btn').on('click', function(e) {
            e.preventDefault();
            // If any filter is filled in and time is still "today", auto-switch to "all"
            var hasFilter = $('#adv_search_name').val() || $('#adv_search_dob').val() ||
                $('#adv_date_from').val() || $('#adv_date_to').val() ||
                $('#adv_patient_id').val() || $('#adv_facility').val() ||
                $('#adv_division').val() || $('#adv_state').val() ||
                $('#adv_modality').val() || $('#adv_status').val() ||
                $('#nh_chk').prop('checked') || $('#hb_chk').prop('checked') ||
                $('#cf_chk').prop('checked') || $('#lab_chk').prop('checked');
            if (hasFilter && $('#adv_time').val() === 'today') {
                $('#adv_time').val('all');
            }
            ordersTable.ajax.reload();
        });

        // Reset all filters and reload with today's orders
        $('#adv_reset_btn').on('click', function(e) {
            e.preventDefault();
            $('#adv_search_name').val('');
            $('#adv_search_dob').val('');
            $('#adv_date_from').val('');
            $('#adv_date_to').val('');
            $('#adv_date_from').datepicker('option', 'maxDate', null);
            $('#adv_date_to').datepicker('option', 'minDate', null);
            $('#adv_date_type').val('order');
            $('#adv_patient_id').val('');
            // Reset selects with disabled default options back to first item
            $('#adv_facility, #adv_division, #adv_state, #adv_modality, #adv_status').each(function() {
                this.selectedIndex = 0;
            });
            $('#adv_time').val('today');
            $('#nh_chk, #hb_chk, #cf_chk, #lab_chk').prop('checked', false);
            ordersTable.columns().search('');
            $ordersTable.find('thead tr:eq(1) input, thead tr:eq(1) select').val('');
            ordersTable.ajax.reload();
        });

        // Export button handler
        $('#adv_export_btn').on('click', function(e) {
            e.preventDefault();
            var csvContent = "data:text/csv;charset=utf-8,";
            var headers = ["Status", "Time in Status", "Order Date", "Schedule Date", "Patient MR", "Patient Name", "Facility", "Tech", "Procedure/X-ray"];
            csvContent += headers.map(function(h) { return '"' + h.replace(/"/g, '""') + '"'; }).join(",") + "\n";
            ordersTable.rows({ search: 'applied' }).every(function() {
                var row = this.data();
                var rowValues = [
                    row[1], row[2], row[3], row[4], row[5],
                    row[6], row[7], row[8], row[9]
                ];
                csvContent += rowValues.map(function(v) { return '"' + (v || '').toString().replace(/<[^>]*>/g,'').replace(/"/g, '""') + '"'; }).join(",") + "\n";
            });
            var link = document.createElement("a");
            link.setAttribute("href", encodeURI(csvContent));
            link.setAttribute("download", "orders_export_" + new Date().getTime() + ".csv");
            link.click();
            showToast('Export completed successfully', 'success');
        });

        // Column-based search functionality
        $ordersTable.find('thead tr:eq(1)').on('keyup change', 'input, select', function() {
            var colIndex = $(this).closest('th').index();
            ordersTable.column(colIndex).search(this.value).draw();
        });

        // Search button click
        $ordersTable.on('click', '.search-btn', function(e) {
            e.preventDefault();
            ordersTable.draw();
        });

        // Dispatch dialog
        $(document).on("click", ".dispatch-btn", function() {
            var order_id = $(this).data('id');
            dispatch_dlg = show_dialog('Dispatch', $(".dispatch-dlg-container").html(), 'medium', true);
            setTimeout(function(){
                $(".jconfirm .date_time").datetimepicker({
                    changeMonth: true,
                    changeYear: true
                });
                $(".jconfirm .btn-dispatch-submit").data('id', order_id);
            }, 100);
        });
        // Dispatch submit
        $(document).on("click", ".jconfirm .btn-dispatch-submit", function() {
            var $dlg = $(".jconfirm");
            var $dateTime = $dlg.find(".dispatch_date_time");
            var $tech = $dlg.find(".technologist");
            var order_id = $(this).data('id');
            if (!order_id) {
                var $relatedBtn = $(".dispatch-btn.active, .dispatch-btn:focus");
                if ($relatedBtn.length) {
                    order_id = $relatedBtn.data('id');
                }
            }
            var isValid = true;
            if(is_empty($dateTime.val())){
                $dateTime.closest(".form-group").addClass('has-error');
                isValid = false;
            } else {
                $dateTime.closest(".form-group").removeClass('has-error');
            }
            if(is_empty($tech.val())){
                $tech.closest(".form-group").addClass('has-error');
                isValid = false;
            } else {
                $tech.closest(".form-group").removeClass('has-error');
            }
            if(!isValid) return false;
            show_loading(true);
            $.ajax({
                url: BASE_URL + 'admin/order/dispatch',
                type: "POST",
                data: {
                    order_id: order_id,
                    date_time: $dateTime.val(),
                    technologist: $tech.val()
                },
                success: function(res){
                    show_loading(false);
                    var obj = JSON.parse(res);
                    if(obj.status == '1'){
                        dispatch_dlg.close();
                        ordersTable.ajax.reload();
                    } else {
                        showToast(obj.message, 'error');
                    }
                },
                error: function(){
                    show_loading(false);
                    showToast('An error occurred', 'error');
                }
            });
        });

        // Mark completed dialog
        $(document).on("click", ".mark-btn", function() {
            var order_id = $(this).data('id');
            mark_dlg = show_dialog('Mark Completed', $(".mark-dlg-container").html(), 'small', true);
            setTimeout(function(){
                $(".jconfirm .btn-mark-submit").data('id', order_id);
                $(".jconfirm .date_time").datetimepicker({
                    changeMonth: true,
                    changeYear: true
                });
            }, 100);
        });

        // Mark completed submit
        $(document).on("click", ".jconfirm .btn-mark-submit", function() {
            var $dateTime = $(".jconfirm .mark_date_time");

            if(is_empty($dateTime.val())){
                $dateTime.closest(".form-group").addClass('has-error');
                return false;
            }
            $dateTime.closest(".form-group").removeClass('has-error');

            show_loading(true);
            $.ajax({
                url: BASE_URL + 'admin/order/mark_completed',
                type: "POST",
                data: {
                    order_id: $(this).data('id'),
                    date_time: $dateTime.val()
                },
                success: function(res){
                    show_loading(false);
                    var obj = JSON.parse(res);
                    if(obj.status == '1'){
                        mark_dlg.close();
                        ordersTable.ajax.reload();
                    } else {
                        showToast(obj.message, 'error');
                    }
                },
                error: function(){
                    show_loading(false);
                    showToast('An error occurred', 'error');
                }
            });
        });

        // Notes dialog
        $(document).on("click", ".note-btn", function() {
            selected_order_id = $(this).data('id');
            $(".btn-add-note").data('id', selected_order_id);
            note_dlg = show_dialog('Notes', $(".note-dlg-container").html(), 'large', true);
            setTimeout(function(){ drawOrderNoteTable(selected_order_id); }, 100);
        });

        $(document).on("click", ".btn-add-note", function(){
            $(".edit-note-dlg-container .note-text").val('');
            $(".edit-note-dlg-container .note-attachment").val('');
            $(".edit-note-dlg-container .current-attachment").hide();
            $(".btn-note-update").data('note-id', 0);
            $(".btn-note-update").attr('data-current-attachment', '');
            note_item_dlg = show_dialog('Add note', $(".edit-note-dlg-container").html());
        });

        $(document).on("click", ".ajax-note-edit-btn", function(){
            var note_text = $(this).closest('tr').find('.td-note').html();
            var attachment = $(this).data('attachment') || '';
            $(".edit-note-dlg-container .note-text").val(note_text);
            $(".edit-note-dlg-container .note-attachment").val('');
            $(".btn-note-update").data('note-id', $(this).data('id'));
            $(".btn-note-update").attr('data-current-attachment', attachment || '');
            note_item_dlg = show_dialog('Edit note', $(".edit-note-dlg-container").html());

            if(attachment) {
                setTimeout(function() {
                    $('.jconfirm .current-attachment').show();
                    $('.jconfirm .current-attachment-link').text(attachment);
                    $('.jconfirm .current-attachment-link').attr('href', BASE_URL + 'uploads/note_attachments/' + attachment);
                }, 100);
            }
        });
        // Note update
        $(document).on("click", ".btn-note-update", function(){
            var note_id = parseInt($(this).data('note-id'));
            var note_text = $('.jconfirm .note-text').val();
            var fileInput = $('.jconfirm .note-attachment')[0];
            var removeAttachment = $('.jconfirm .remove-attachment').data('remove') || 0;

            var formData = new FormData();
            formData.append('note_id', note_id);
            formData.append('order_id', selected_order_id);
            formData.append('note_text', note_text);
            formData.append('remove_attachment', removeAttachment);

            if(fileInput && fileInput.files && fileInput.files.length > 0) {
                formData.append('note_attachment', fileInput.files[0]);
            }

            show_loading(true);
            $.ajax({
                url: BASE_URL + 'admin/order/update_note',
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(res){
                    show_loading(false);
                    var obj = JSON.parse(res);
                    if(obj.status == '1'){
                        note_item_dlg.close();
                        note_table.ajax.reload();
                        $('.jconfirm .note-attachment').val('');
                        showToast(note_id > 0 ? "Note updated successfully" : "Note added successfully", 'success');
                    } else {
                        showToast(obj.message, 'error');
                    }
                },
                error: function(){
                    show_loading(false);
                    showToast('An error occurred', 'error');
                }
            });
        });

        // Remove attachment handler
        $(document).on("click", ".remove-attachment", function(){
            $(this).data('remove', 1);
            $('.jconfirm .current-attachment').hide();
            $('.jconfirm .note-attachment').val('');
            showToast('Attachment will be removed when you save', 'info');
        });

        // Note delete
        $(document).on("click", ".ajax-note-del-btn", function(){
            var note_id = $(this).data('id');
            show_confirmDlg("Are you sure to delete this note?", function(){
                show_loading(true);
                $.ajax({
                    url: BASE_URL + 'admin/order/delete_note',
                    type: "POST",
                    data: {
                        order_id: selected_order_id,
                        note_id: note_id
                    },
                    success: function(res){
                        show_loading(false);
                        var obj = JSON.parse(res);
                        if(obj.status == '1'){
                            note_table.ajax.reload();
                            showToast("Note deleted successfully", 'success');
                        } else {
                            showToast(obj.message, 'error');
                        }
                    },
                    error: function(){
                        show_loading(false);
                        showToast('An error occurred', 'error');
                    }
                });
            });
        });

        // Cancel order dialog
        $(document).on("click", ".cancel-btn", function() {
            var order_id = $(this).data('id');
            delete_order_dlg = show_dialog('Cancel Order', $(".delete-order-dlg-container").html(), 'small', true);
            setTimeout(function(){
                $(".jconfirm .btn-cancel-submit").data('id', order_id);
                $(".jconfirm .order_cancel_datetime").val(getFormattedDate('m/d/Y H:i'));
            }, 100);
        });

        // Cancel order submit
        $(document).on("click", ".btn-cancel-submit", function(){
            var $dlg = $(".jconfirm");

            show_loading(true);
            $.ajax({
                url: BASE_URL + 'admin/order/cancel',
                type: "POST",
                data: {
                    order_id: $(this).data('id'),
                    date_time: $dlg.find(".order_cancel_datetime").val(),
                    reason_for_cancel: $dlg.find(".reason_for_cancel").val(),
                    reschedule: $dlg.find(".reschedule").prop('checked') ? 1 : 0
                },
                success: function(res){
                    show_loading(false);
                    var obj = JSON.parse(res);
                    if(obj.status == '1'){
                        delete_order_dlg.close();
                        ordersTable.ajax.reload();
                    } else {
                        showToast(obj.message, 'error');
                    }
                },
                error: function(){
                    show_loading(false);
                    showToast('An error occurred', 'error');
                }
            });
        });
    });

})(jQuery);

    var note_table;
    function drawOrderNoteTable(order_id){
        note_table = jQuery('.jconfirm #my_datatable').DataTable(
            {
                "lengthMenu": [[5], [5]],
                'responsive': true,
                "ajax":"<?php echo base_url(); ?>admin/order/ajax_get_order_notes/" + order_id
            }
        );
    }
</script>
