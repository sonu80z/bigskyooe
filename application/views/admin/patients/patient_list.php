
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-body with-border">
                <div class="col-md-6 a_page_top_title">
                    <?=$title;?>
                </div>
                <div class="col-md-6 text-right">
                    <a href="#" onclick="import_from_xls()" class="btn btn-default">IMPORT FROM XLS</a>
                    <a href="<?= base_url('admin/patients/add'); ?>" class="btn btn-default">ADD PATIENT</a>
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
                        <th>Patient ID</th>
                        <th>SS No</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>DOB</th>
                        <th>Gender</th>
                        <th>HB Institution</th>
                        <th>NH Institution</th>
                        <th>State</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($patients as $patient ):?>
                            <tr>
                                <td><?php echo $patient["PATIENT_MRN"]?></td>
                                <td><?php echo $patient["SS_NO"]?></td>
                                <td><?php echo $patient["LAST_NAME"]?></td>
                                <td><?php echo $patient["FIRST_NAME"]?></td>
<!--                                <td>--><?php //$time = strtotime($patient["DOB"]); $newFormat = date('m-d-y', $time); echo $newFormat;?><!--</td>-->
                                <td><?php echo $patient["DOB"];?></td>
                                <td><?php echo $patient["GENDER"]?></td>
                                <td><?php if ($patient["HB_INSTITUTION"]){
                                        echo isset($facility_lookup[$patient["HB_INSTITUTION"]]) ? htmlspecialchars($facility_lookup[$patient["HB_INSTITUTION"]]['facility_name']) : "";
                                    } else{echo "";}?></td>
                                <td><?php if ($patient["NH_INSTITUTION"]){
                                        echo isset($facility_lookup[$patient["NH_INSTITUTION"]]) ? htmlspecialchars($facility_lookup[$patient["NH_INSTITUTION"]]['facility_name']) : "";
                                    } else{echo "";}?></td>
                                <td><?php echo $patient["STATE"] ?? ''?></td>
                                <td style="white-space:nowrap">
                                    <a title="Edit" class="update btn btn-sm btn-primary" href="<?php echo base_url('admin/patients/edit/').$patient["ID"];?>"><i class="fa fa-pencil-square-o"></i></a>
                                    <a title="Delete" class="delete btn btn-sm btn-danger" data-href="<?php echo base_url('admin/patients/delete/').$patient["ID"];?>" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
                                </td>
                            </tr>
                        <?php endforeach;?>
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
<div id="import-xls" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Import XLS</h4>
            </div>
            <div class="modal-body">
                <form action="<?php echo base_url('admin/patients/import_xls');?>" method="post" enctype="multipart/form-data">
                    Upload excel file :
                    <input type="file" name="uploadFile" value="" /><br><br>
                    <input type="submit" name="submit" value="Upload" />
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
    function import_from_xls() {
        $('#import-xls').modal('show');
    }
</script>
