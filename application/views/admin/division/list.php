
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-body with-border">
                <div class="col-md-6 a_page_top_title">
                    <?=$title;?>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= base_url('admin/division/add'); ?>" class="btn btn-default">ADD DIVISION</a>
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
                        <th>Division Name</th>
                        <th>GPS location</th>
                        <th>Division Manager</th>
                        <th>Address</th>
                        <th>Phone Number</th>
                        <th>Fax</th>
                        <th>Type</th>
                        <th>Create At</th>
                        <th style="min-width: 100px;">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no = 0;
                    foreach ( $facilities  as $row )
                    {
                        $no ++;
                        $type = '<span class="label label-info">Division</span>';
                        if ( $row["type"] == "1" ) {
                            $type = '<span class="label label-primary">Subdivision</span>';
                        } else if ( $row["type"] == "2" ) {
                            $type = '<span class="label label-success">Region</span>';
                        }
                        echo '<tr did="'.$row["id"].'">
                                  <td>'.$no.'</td>
                                  <td>'.$row["name"].'</td>
                                  <td>'.$row["gps_location"].'</td>
                                  <td>'.$row["division_manager"].'</td>
                                  <td>'.$row["address"].'</td>
                                  <td>'.$row["phone"].'</td>
                                  <td>'.$row["fax"].'</td>
                                  <td>'.$type.'</td>
                                  <td>'.$row["created_at"].'</td>
                                  <td style="white-space:nowrap">
                                     <a title="Edit" class="update btn btn-sm btn-primary" href="'.base_url().'admin/division/edit/'.$row["id"].'" ><i class="fa fa-pencil-square-o"></i></a>
                                     <a title="Delete" class="delete btn btn-sm btn-danger" data-href="'.base_url().'admin/division/del_division/'.$row["id"].'" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
                                </td>
                              </tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
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
    jQuery('#na_datatable').DataTable({responsive: true});
</script>

<script type="text/javascript">
    $('#confirm-delete').on('show.bs.modal', function(e) {
        $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    });
</script>
