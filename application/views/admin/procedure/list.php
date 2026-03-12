
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-body with-border">
                <div class="col-md-6 a_page_top_title">
                    <?=$title;?>
                </div>
                <div class="col-md-6 text-right">
                    <button class="btn btn-success" id="procedure_export_btn" type="button"><i class="fa fa-download"></i> Export Results</button>
                    <a href="<?= base_url('admin/procedure/add'); ?>" class="btn btn-default">ADD PROCEDURE</a>
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
                        <th>CPT Code</th>
                        <th>Description</th>
                        <th>Modality</th>
                        <th>Category</th>
                        <th>Created Datetime</th>
                        <th style="min-width: 100px;">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no = 0;
                    foreach ( $facilities  as $row )
                    {
                        $no ++;
                        echo '<tr did="'.$row["id"].'">
                                  <td>'.$no.'</td>
                                  <td>'.$row["cpt_code"].'</td>
                                  <td>'.$row["description"].'</td>
                                  <td>'.$row["modality"].'</td>
                                  <td>'.$row["category"].'</td>
                                  <td>'.$row["created_at"].'</td>
                                  <td style="white-space:nowrap">
                                     <a title="Edit" class="update btn btn-sm btn-primary" href="'.base_url().'admin/procedure/add/'.$row["id"].'" ><i class="fa fa-pencil-square-o"></i></a>
                                     <a title="Delete" class="delete btn btn-sm btn-danger" data-href="'.base_url().'admin/procedure/del_procedure/'.$row["id"].'" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
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
    var procedureTable = jQuery('#na_datatable').DataTable({responsive: true});

    $('#procedure_export_btn').on('click', function(e) {
        e.preventDefault();
        var csvContent = "data:text/csv;charset=utf-8,";
        var headers = ["NO", "CPT Code", "Description", "Modality", "Category", "Created Datetime"];
        csvContent += headers.map(function(h) { return '"' + h.replace(/"/g, '""') + '"'; }).join(",") + "\n";
        procedureTable.rows({ search: 'applied' }).every(function() {
            var row = this.data();
            var rowValues = [row[0], row[1], row[2], row[3], row[4], row[5]];
            csvContent += rowValues.map(function(v) { return '"' + $('<div>').html(v || '').text().replace(/"/g, '""') + '"'; }).join(",") + "\n";
        });
        var link = document.createElement("a");
        link.setAttribute("href", encodeURI(csvContent));
        link.setAttribute("download", "procedures_export_" + new Date().getTime() + ".csv");
        link.click();
        showToast('Export completed successfully', 'success');
    });
</script>

<script type="text/javascript">
    $('#confirm-delete').on('show.bs.modal', function(e) {
        $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    });
</script>
