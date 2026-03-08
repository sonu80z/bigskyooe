<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-body with-border">
                <div class="col-md-6 a_page_top_title">
                    <?= $title; ?>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= base_url('admin/division/lists'); ?>" class="btn btn-default">DIVISION LIST</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box-body my-form-body">
                <?php echo form_open(base_url('admin/division/edit/' . $division["id"]), 'class="form-horizontal"'); ?>
                <div class="ade_stlt"><?= $division["name"]; ?>
                    <button type="submit" name="submit" value="1" class="btn btn-xs btn-success pull-right">Save
                    </button>
                </div>
                <div class="ade_cnt">
                    <div class="row">
                        <div class="col-sm-2">Manager</div>
                        <div class="col-sm-4"><input type="text" name="division_manager" class="g_ipt_full"
                                                     value="<?= $division["division_manager"]; ?>" required/></div>
                        <div class="col-sm-2">GPS</div>
                        <div class="col-sm-4"><input type="text" name="gps_location" class="g_ipt_full"
                                                     value="<?= $division["gps_location"]; ?>" required/></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">Address</div>
                        <div class="col-sm-4"><input type="text" name="address" class="g_ipt_full"
                                                     value="<?= $division["address"]; ?>" required/></div>
                        <div class="col-sm-2">Fax</div>
                        <div class="col-sm-4"><input type="text" name="fax" class="g_ipt_full"
                                                     value="<?= $division["fax"]; ?>" required/></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">Phone</div>
                        <div class="col-sm-4"><input type="text" name="phone" class="g_ipt_full" id="division_phone"
                                                     value="<?= $division["phone"]; ?>" required/></div>
                    </div>
                </div>
                <?php echo form_close(); ?><br><br><br>
                <div class="ade_stlt">Subdivision List
                    <button class="btn btn-xs btn-info pull-right ade_subd_add" data-toggle="modal"
                            data-target="#ade_subd_add">Add
                    </button>
                </div>
                <div class="ade_cnt">
                    <table id="ade_subd_tbl" class="table table-bordered table-hover table-striped a_user_list_tb"
                           width="100%">
                        <thead>
                        <tr>
                            <th>NO</th>
                            <th>Name</th>
                            <th>Manager</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Fax</th>
                            <th style="min-width: 100px;">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $no = 1;
                        foreach ($subdivisions as $row): ?>
                            <tr>
                                <td><?php echo $no++?></td>
                                <td><?php echo $row["name"]?></td>
                                <td><?php echo $row["division_manager"]?></td>
                                <td><?php echo $row["address"]?></td>
                                <td><?php echo $row["phone"]?></td>
                                <td><?php echo $row["fax"]?></td>
                                <td>
                                    <?php $total = "'".$row["id"].'-'.$row["name"].'-'.$row["division_manager"].'-'.$row["address"].'-'.$row["phone"].'-'.$row["fax"]."'"?>
                                    <button title="Edit" class="edit btn btn-xs btn-info pull-right"
                                       onclick="edit_sub_division(<?php echo $total;?>)"><i class="fa fa-edit"></i></button>
                                    <a title="Delete" class="delete btn btn-xs btn-danger pull-right"
                                       data-href="<?php echo site_url('admin/division/del_division/'.$row["id"].'/'.$division["id"])?>" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>
                <br><br><br>
                <div class="ade_stlt">Region List
                    <button class="btn btn-xs btn-info pull-right ade_subd_add" data-toggle="modal"
                            data-target="#ade_region_add">Add
                    </button>
                </div>
                <div class="ade_cnt">
                    <table id="ade_regi_tbl" class="table table-bordered table-hover table-striped a_user_list_tb"
                           width="100%">
                        <thead>
                        <tr>
                            <th>NO</th>
                            <th>Name</th>
                            <th>Subdivision</th>
                            <th>Manager</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Fax</th>
                            <th style="min-width: 100px;">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $no = 1;
                        foreach ($regions as $row):?>
                            <tr>
                                  <td><?php echo $no++?></td>
                                  <td><?php echo $row["name"] ?></td>
                                  <td><?php echo $row["sub_name"] ?></td>
                                  <td><?php echo $row["division_manager"] ?></td>
                                  <td><?php echo $row["address"] ?></td>
                                  <td><?php echo $row["phone"] ?></td>
                                  <td><?php echo $row["fax"] ?></td>
                                  <td>
                                      <?php $total = "'".$row["id"].'-'.$row["name"].'-'.$row["division_manager"].'-'.$row["address"].'-'.$row["phone"].'-'.$row["fax"].'-'.$row["sub_id"]."'"?>
                                      <button title="Edit" class="edit btn btn-xs btn-info pull-right"
                                              onclick="edit_region(<?php echo $total;?>)"><i class="fa fa-edit"></i></button>
                                      <a title="Delete" class="delete btn btn-xs btn-danger pull-right"
                                         data-href="<?php echo site_url('admin/division/del_division/'.$row["id"].'/'.$division["id"])?>" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
                                  </td>
                            </tr>
                        <?php endforeach?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sub Division -->
<div id="ade_subd_add" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Subdivision</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open(base_url('admin/division/create/1'), 'class="form-horizontal"'); ?>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <label class="control-label">Subdivision Name * </label>
                        <input type="text" name="name" class="form-control" required/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <label class="control-label">Sub-Division manager * </label>
                        <input type="text" name="division_manager" class="form-control" required/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <label class="control-label">Address </label>
                        <input type="text" name="address" class="form-control"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <label class="control-label">Phone Number </label>
                        <input type="text" name="phone" class="form-control"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <label class="control-label">Fax </label>
                        <input type="text" name="fax" class="form-control"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3 g_txt_right">
                        <input type="hidden" name="parent" class="form-control" value="<?= $division["id"]; ?>"/>
                        <input type="submit" name="submit" value="Add Subdivision" class="btn btn-info">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>

    </div>
</div>
<div id="ade_subd_edit" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Subdivision</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open(base_url('admin/division/edit_others/1'), 'class="form-horizontal"'); ?>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <label class="control-label">Subdivision Name * </label>
                        <input type="text" name="name" id="sub_division_name" class="form-control" required/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <label class="control-label">Sub-Division manager * </label>
                        <input type="text" name="division_manager" id="sub_division_manager" class="form-control" required/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <label class="control-label">Address </label>
                        <input type="text" name="address" id="sub_division_address" class="form-control"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <label class="control-label">Phone Number </label>
                        <input type="text" name="phone" id="sub_division_phone" class="form-control"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <label class="control-label">Fax </label>
                        <input type="text" name="fax" id="sub_division_fax" class="form-control"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3 g_txt_right">
                        <input type="hidden" name="parent" class="form-control" value="<?= $division["id"]; ?>"/>
                        <input type="hidden" id="sub_division_id" name="id" class="form-control" value=""/>
                        <input type="submit" name="submit" value="Edit Subdivision" class="btn btn-info">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>

    </div>
</div>

<!-- Region -->
<div id="ade_region_add" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Region</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open(base_url('admin/division/create/2'), 'class="form-horizontal"'); ?>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <label class="control-label">Sub Division</label>
                        <select name="sparent" class="form-control" required>
                            <?php
                            foreach ($subdivisions as $row) {
                                echo '<option value="' . $row["id"] . '">' . $row["name"] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <label class="control-label">Region Name * </label>
                        <input type="text" name="name" class="form-control" required/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <label class="control-label">Region manager * </label>
                        <input type="text" name="division_manager" class="form-control" required/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <label class="control-label">Address </label>
                        <input type="text" name="address" class="form-control"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <label class="control-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <label class="control-label">Fax </label>
                        <input type="text" name="fax" class="form-control"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3 g_txt_right">
                        <input type="hidden" name="parent" class="form-control" value="<?= $division["id"]; ?>"/>
                        <input type="submit" name="submit" value="Add Subdivision" class="btn btn-info">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>

    </div>
</div>
<div id="ade_region_edit" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Region</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open(base_url('admin/division/create/2'), 'class="form-horizontal"'); ?>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <label class="control-label">Sub Division</label>
                        <select name="sparent" id="region_sub_name" class="form-control" required>
                            <?php foreach ($subdivisions as $row):

//                            {
//                                echo '<option value="' . $row["id"] . '">' . $row["name"] . '</option>';
//                            }
                            ?>
                                <option value="<?php echo $row["id"]?>" <?php if ($row["id"] == "")?>><?php echo $row['name']?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <label class="control-label">Region Name * </label>
                        <input type="text" id="region_name" name="name" class="form-control" required/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <label class="control-label">Region manager * </label>
                        <input type="text" id="region_manager" name="division_manager" class="form-control" required/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <label class="control-label">Address </label>
                        <input type="text" id="region_address" name="address" class="form-control"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <label class="control-label">Phone Number</label>
                        <input type="text" id="region_phone" name="phone" class="form-control"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <label class="control-label">Fax </label>
                        <input type="text" id="region_fax" name="fax" class="form-control"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3 g_txt_right">
                        <input type="hidden" name="parent" class="form-control" value="<?= $division["id"]; ?>"/>
                        <input type="hidden" name="id" id="region_id" class="form-control" value=""/>
                        <input type="submit" name="submit" value="Edit Region" class="btn btn-info">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
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
<script type="text/javascript">
    //---------------------------------------------------
    jQuery('#ade_subd_tbl').DataTable({
        "lengthMenu": [[100], [100]],
        bPaginate: false,
        ordering: false,
        info: false,
        responsive: true
    });

    function edit_sub_division(value) {
        console.log('this func is called: ', value  );
        var parsed_array = value.split("-");
        var id = parsed_array[0];
        var name = parsed_array[1];
        var division_manager = parsed_array[2];
        console.log('Division Manger: ', division_manager);
        var address = parsed_array[3];
        var phone = parsed_array[4];
        var fax = parsed_array[5];
        $('#sub_division_id').val(id);
        $('#sub_division_name').val(name);
        $('#sub_division_manager').val(division_manager);
        $('#sub_division_address').val(address);
        $('#sub_division_phone').val(phone);
        $('#sub_division_fax').val(fax);
        $('#ade_subd_edit').modal('show');
    }

    function edit_region(value) {
        console.log('this func is called: ', value  );
        var parsed_array = value.split("-");
        var id = parsed_array[0];
        var name = parsed_array[1];
        var division_manager = parsed_array[2];
        var address = parsed_array[3];
        var phone = parsed_array[4];
        var fax = parsed_array[5];
        var sub_name = parsed_array[6];
        $('#region_id').val(id);
        $('#region_name').val(name);
        $('#region_manager').val(division_manager);
        $('#region_address').val(address);
        $('#region_phone').val(phone);
        $('#region_fax').val(fax);
        $('#region_sub_name').val(sub_name);
        $('#ade_region_edit').modal('show');
    }

    jQuery('#ade_regi_tbl').DataTable({
        "lengthMenu": [[100], [100]],
        bPaginate: false,
        ordering: false,
        info: false,
        responsive: true
    });

    jQuery('#confirm-delete').on('show.bs.modal', function (e) {
        jQuery(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    });
    $(window).load(function()
    {
        var phones = [{ "mask": "(###) ###-####"}];
        $('#sub_division_phone').inputmask({
            mask: phones,
            greedy: false,
            definitions: { '#': { validator: "[0-9]", cardinality: 1}} });
        $('#division_phone').inputmask({
            mask: phones,
            greedy: false,
            definitions: { '#': { validator: "[0-9]", cardinality: 1}} });
        $('#region_phone').inputmask({
            mask: phones,
            greedy: false,
            definitions: { '#': { validator: "[0-9]", cardinality: 1}} });
    });
</script>
