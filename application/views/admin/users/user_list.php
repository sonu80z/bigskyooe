
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-body with-border">
                <div class="col-md-6 a_page_top_title">
                    <?=$title;?>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= base_url('admin/users/add'); ?>" class="btn btn-default">ADD A USER</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box-body table-responsive">
                
                <!-- Filter Section -->
                <div class="box box-primary collapsed-box" style="margin-bottom: 20px;">
                    <div class="box-header with-border">
                        <h3 class="box-title">Filters</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="box-body" style="display: none;">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Filter by Role:</label>
                                <select id="filter_role" class="form-control">
                                    <option value="">All Roles</option>
                                    <option value="1">Super Admin</option>
                                    <option value="2">Admin</option>
                                    <option value="3">Coder</option>
                                    <option value="4">Dispatcher</option>
                                    <option value="5">Staff</option>
                                    <option value="6">Facility User</option>
                                    <option value="7">Ordering Physician</option>
                                    <option value="8">Technologist</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>&nbsp;</label>
                                <div style="margin-top: 5px;">
                                    <label>
                                        <input type="checkbox" id="filter_physician_only" /> 
                                        Physician Only (No User Account)
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>&nbsp;</label>
                                <div style="margin-top: 5px;">
                                    <button type="button" class="btn btn-sm btn-primary" id="apply_filters">Apply Filters</button>
                                    <button type="button" class="btn btn-sm btn-danger" id="clear_filters">Clear Filters</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <table id="na_datatable" class="table table-bordered table-hover table-striped a_user_list_tb" width="100%">
                    <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Real Name</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th style="width: 200px">Facilities</th>
                        <th>Location</th>
                        <th>Type</th>
                        <th>Create Datetime</th>
                        <th>Action</th>
                    </tr>
                    <tr>
                        <th><input type="text" class="g_ipt_full" placeholder="ID" /></th>
                        <th><input type="text" class="g_ipt_full" placeholder="Real Name" /></th>
                        <th><input type="text" class="g_ipt_full" placeholder="User Name" /></th>
                        <th><input type="text" class="g_ipt_full" placeholder="Email" /></th>
                        <th><input type="text" class="g_ipt_full" placeholder="Role" /></th>
                        <th><input type="text" class="g_ipt_full" placeholder="Facilities" /></th>
                        <th><input type="text" class="g_ipt_full" placeholder="Location" /></th>
                        <th><input type="text" class="g_ipt_full" placeholder="Type" /></th>
                        <th><input type="text" class="g_ipt_full" placeholder="Date" /></th>
                        <th><button class="btn btn-sm btn-primary search-btn">Filter</button></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no = 0;
                    $facility_ids = array();
                    $facility_name = "";
                    function convert_multi_array($facility_names_array) {
                        $out = implode(", ",array_map(function($a) {return implode(", ",$a);},$facility_names_array));
                        return ($out);
                    }
                    foreach ($users  as $row)
                    {
                        $no ++;
                        $roleLabels = array(
                            1 => 'Super Admin',
                            2 => 'Admin',
                            3 => 'Coder',
                            4 => 'Dispatcher',
                            5 => 'Staff',
                            6 => 'Facility User',
                            7 => 'Ordering Physician',
                            8 => 'Technologist',
                        );
                        $role = isset($roleLabels[$row['role']]) ? $roleLabels[$row['role']] : '';
                        $state = '';
                        /*if ( $row["mainstate"] == 1 ) {
                            $state = 'Pennsylvania';
                        } else if ( $row["mainstate"] == 2 ) {
                            $state = 'California';
                        } else if ( $row["mainstate"] == 3 ) {
                            $state = 'Colorado';
                        } else if ( $row["mainstate"] == 4 ) {
                            $state = 'Utah';
                        }*/
                        if(!empty($row['state_info'])){
                            $state = $row['state_info']['fldState'];
                        }
                        $facilities = "";
                        if ($row["facility"] != ""){
                            $facility_tokens = array_filter(explode('=', $row["facility"]));
                            $facility_names = array();
                            $numeric_ids = array();

                            foreach ($facility_tokens as $token) {
                                if (ctype_digit($token)) {
                                    $numeric_ids[] = (int) $token;
                                } elseif (!empty($token)) {
                                    $facility_names[] = str_replace('_', ' ', $token);
                                }
                            }

                            if (!empty($numeric_ids)) {
                                $facility_names_query = $this->db
                                    ->select('facility_name')
                                    ->from('tbl_facility')
                                    ->where_in('id', $numeric_ids)
                                    ->get()
                                    ->result_array();
                                $facility_names = array_merge($facility_names, array_map(function($row){
                                    return $row['facility_name'];
                                }, $facility_names_query));
                            }

                            if (!empty($facility_names)) {
                                $facilities = implode(', ', $facility_names);
                            }
                        }

                        // Determine user type (physician-only if username starts with "phys_")
                        $user_type = (strpos($row["username"], 'phys_') === 0) ? 'Physician Only' : 'User Account';
                        $user_type_class = (strpos($row["username"], 'phys_') === 0) ? 'badge-warning' : 'badge-info';
                        
                        echo '<tr id="'.$row["id"].'" data-role="'.$row["role"].'" data-type="'.htmlspecialchars($user_type).'">
                      <td>'.$row["id"].'</td>
                      <td>'.htmlspecialchars($row["fullname"]).'</td>
                      <td>'.htmlspecialchars($row["username"]).'</td>
                      <td>'.htmlspecialchars($row["email"]).'</td>
                      <td>'.$role.'</td>
                      <td style="width: 200px">'.$facilities.'</td>
                      <td>'.$state.'</td>
                      <td><span class="badge '.$user_type_class.'">'.$user_type.'</span></td>
                      <td>'.$row["created_at"].'</td>
                      <td style="white-space:nowrap">
                         <a title="View" class="view btn btn-sm btn-info" href="'.base_url().'admin/users/view/'.$row["id"].'"><i class="fa fa-eye"></i></a>
                         <a title="Edit" class="update btn btn-sm btn-primary" href="'.base_url().'admin/users/edit/'.$row["id"].'"><i class="fa fa-pencil-square-o"></i></a>
                         <a title="Delete" class="delete btn btn-sm btn-danger" data-href="'.base_url().'admin/users/del/'.$row["id"].'" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
                      </td>
                  </tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
</section>

<!-- Modal -->
<div id="confirm-delete" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Delete</h4>
            </div>
            <div class="modal-body">
                <p>As you sure you want to delete.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>


<!-- DataTables init -->
<script>
    //---------------------------------------------------
    var usersTable = jQuery('#na_datatable').DataTable({
        "lengthMenu": [[10, 20, 50, 100], [10, 20, 50, 100]], 
        responsive: true,
        orderCellsTop: true,
        fixedHeader: true,
        "columnDefs": [
            { "orderable": false, "targets": [9] }  // Action column
        ]
    });
    
    // Custom filter function
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        var table = $.fn.dataTable.fnTables(true);
        if (table.length === 0) return true;
        if (settings.nTable.id !== 'na_datatable') return true;
        
        var selectedRole = $('#filter_role').val();
        var physicianOnlyChecked = $('#filter_physician_only').is(':checked');
        var rowRole = $(settings.aoData[dataIndex].nTr).data('role');
        var rowType = $(settings.aoData[dataIndex].nTr).data('type');
        
        // Filter by role
        if (selectedRole && selectedRole !== '') {
            if (rowRole != selectedRole) {
                return false;
            }
        }
        
        // Filter by physician only
        if (physicianOnlyChecked) {
            if (rowType !== 'Physician Only') {
                return false;
            }
        }
        
        return true;
    });
    
    // Apply filters
    $('#apply_filters').on('click', function() {
        usersTable.draw();
    });
    
    // Clear filters
    $('#clear_filters').on('click', function() {
        $('#filter_role').val('');
        $('#filter_physician_only').prop('checked', false);
        usersTable.draw();
    });
    
    // Column-based search functionality
    $('#na_datatable thead tr:eq(1)').on('keyup change', 'input', function() {
        var colIndex = $(this).closest('th').index();
        usersTable.column(colIndex).search(this.value).draw();
    });
    
    // Search button click
    $(document).on('click', '.search-btn', function(e) {
        e.preventDefault();
        usersTable.draw();
        return false;
    });
    
    // Allow Enter key to trigger search
    $('#na_datatable thead tr:eq(1) input').on('keypress', function(e) {
        if(e.which == 13) {
            e.preventDefault();
            $(this).trigger('change');
            return false;
        }
    });
</script>

<script type="text/javascript">
    $('#confirm-delete').on('show.bs.modal', function(e) {
        $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    });
</script>
