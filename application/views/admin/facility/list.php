
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-body with-border">
                <div class="col-md-6 a_page_top_title">
                    <?=$title;?>
                </div>
                <div class="col-md-6 text-right">
                    <button class="btn btn-success" id="facility_export_btn" type="button"><i class="fa fa-download"></i> Export Results</button>
                    <a href="<?= base_url('admin/facility/add'); ?>" class="btn btn-default">ADD FACILITY</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box-body table-responsive">
                <table id="na_datatable" class="table table-bordered table-hover table-striped a_user_list_tb" width="100%">
                    <thead>
                    <tr>
                        <th>NO</th>
                        <th>Facility Name</th>
                        <th>Admin Name</th>
                        <th>Address Line 1</th>
                        <th>Address Line 2</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Zip</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Fax Number</th>
                        <th style="min-width: 100px;">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no = 0;
                    foreach ( $facilities  as $row )
                    {
                        $no ++;
                        
                        // Combine all fax numbers into one field
                        $fax_numbers = array();
                        for ($i = 1; $i <= 4; $i++) {
                            $fax_key = 'fax' . $i;
                            if (!empty($row[$fax_key])) {
                                $fax_numbers[] = $row[$fax_key];
                            }
                        }
                        $fax_display = !empty($fax_numbers) ? implode(', ', $fax_numbers) : 
                                      (!empty($row['fax']) ? $row['fax'] : '');
                        
                        echo '<tr did="'.$row["id"].'">
                                  <td>'.$no.'</td>
                                  <td>'.$row["facility_name"].'</td>
                                  <td>'.$row["admin_name"].'</td>
                                  <td>'.$row["address1"].'</td>
                                  <td>'.$row["address2"].'</td>
                                  <td>'.$row["address_city"].'</td>
                                  <td>'.$row["address_state"].'</td>
                                  <td>'.$row["address_zip"].'</td>
                                  <td>'.$row["email"].'</td>
                                  <td>'.$row["phone"].'</td>
                                  <td>'.$fax_display.'</td>
                                  <td style="white-space:nowrap">
                                     <a title="Edit" class="update btn btn-sm btn-primary" href="'.base_url().'admin/facility/add/'.$row["id"].'" ><i class="fa fa-pencil-square-o"></i></a>
                                     <a title="Delete" class="delete btn btn-sm btn-danger" data-href="'.base_url().'admin/facility/del_facility/'.$row["id"].'" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
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
    var facilityTable = jQuery('#na_datatable').DataTable({responsive: true});

    $('#facility_export_btn').on('click', function(e) {
        e.preventDefault();
        var csvContent = "data:text/csv;charset=utf-8,";
        var headers = ["NO", "Facility Name", "Admin Name", "Address Line 1", "Address Line 2", "City", "State", "Zip", "Email", "Phone Number", "Fax Number"];
        csvContent += headers.map(function(h) { return '"' + h.replace(/"/g, '""') + '"'; }).join(",") + "\n";
        facilityTable.rows({ search: 'applied' }).every(function() {
            var row = this.data();
            var rowValues = [row[0], row[1], row[2], row[3], row[4], row[5], row[6], row[7], row[8], row[9], row[10]];
            csvContent += rowValues.map(function(v) { return '"' + $('<div>').html(v || '').text().replace(/"/g, '""') + '"'; }).join(",") + "\n";
        });
        var link = document.createElement("a");
        link.setAttribute("href", encodeURI(csvContent));
        link.setAttribute("download", "facilities_export_" + new Date().getTime() + ".csv");
        link.click();
        showToast('Export completed successfully', 'success');
    });
</script>

<script type="text/javascript">
    $('#confirm-delete').on('show.bs.modal', function(e) {
        $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    });
</script>
