<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-body">
                <div class="col-md-6">
                    <h4><i class="fa fa-pencil"></i> &nbsp; <?=$title;?></h4>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= base_url('users/profile'); ?>" class="btn btn-success"><i class="fa fa-pencil-square-o"></i> &nbsp;Manager Profile</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                </div>
                <!-- /.box-header -->
                <!-- add form start -->
                <div class="box-body my-form-body g_border_dv">
                    <input type="checkbox" id="a_security_question_is_enable" <?php if ( $user_info["is_2fa"] == "1") echo "checked";?> disabled /> <label for="a_security_question_is_enable"> Enable security question when login</label>
                </div> <br><br><br>

                <div class="box-body my-form-body g_border_dv">
                    <?php if(validation_errors() !== ''): ?>
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                            <?= validation_errors();?>
                        </div>
                    <?php endif; ?>
                    <?php echo form_open(base_url('users/profile/security_question'), 'class="form-horizontal"');  ?>
                    <div class="form-group">
                        <div class="col-sm-8">
                            <label for="password" class="control-label">Security Question</label>
                            <select name="question" class="form-control">
                                <option disabled selected>Please select question</option>
                                <?php
                                foreach ( $questions as $r )
                                {
                                    echo '<option value="'.$r["id"].'">'.$r["question"].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label for="confirm_pwd" class="control-label">Answer</label>
                            <input type="text" name="answer" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="submit" name="submit" value="Add Question" class="btn btn-info pull-right">
                        </div>
                    </div>
                    <?php echo form_close( ); ?>
                </div>

                <!-- added quesiton list -->
                <div class="box-body my-form-body">
                    <div class="a_p_sq_stlt">Security questioin list</div>
                    <input type="hidden" value="<?=count($own_questions);?>" id="a_s_q_count" />
                    <?php $no = 0; foreach ($own_questions as $q ) { $no ++; ?>
                    <div class="row">
                        <div class="col-sm-1"><?=$no;?></div>
                        <div class="col-sm-6">
<!--                            <select class="form-control">-->
<!--                                --><?php
//                                foreach ( $questions as $r ) {
//                                    if ( $r["id"] == $q["question"] ) echo '<option value="'.$r["id"].'" selected>'.$r["question"].'</option>';
//                                    else echo '<option value="'.$r["id"].'">'.$r["question"].'</option>';
//                                }
//                                ?>
<!--                            </select>-->
                            <?=$q["question_title"];?>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" value="<?=$q["answer"];?>" />
                        </div>
                        <div class="col-sm-2">
                            <a title="Save" class="update btn btn-sm btn-primary a_security_question_update_btn" did="<?=$q["id"];?>"> <i class="fa fa-pencil-square-o"></i></a>
                            <a title="Delete" class="update btn btn-sm btn-danger" data-href="<?=base_url();?>users/profile/security_question_del/<?=$q["id"];?>"  data-toggle="modal" data-target="#confirm-delete"> <i class="fa fa-trash-o"></i></a>
                        </div>
                    </div><br>
                    <?php } ?>
                </div><br><br><br><br><br><br>
                <!-- /.box-body -->
            </div>
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

<script>
    $("#users").addClass('active');
    $('#confirm-delete').on('show.bs.modal', function(e) {
        $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    });
</script>