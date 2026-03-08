<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-body with-border">
                <div class="col-md-6 a_page_top_title">
                    <?=$title;?>
                </div>
                <div class="col-md-6 text-right">
                </div>
            </div>
        </div>
    </div>
  <div class="row">
      <div class="row">
          <div class="col-md-12">
              <div class="box-body my-form-body">
          <?php if(validation_errors() !== ''): ?>
              <div class="alert alert-warning alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <?= validation_errors();?>
              </div>
            <?php endif; ?>
           
            <?php echo form_open(base_url('users/profile/change_pwd'), 'class="form-horizontal"');  ?>
              <div class="form-group">
                <label for="password" class="col-sm-3 control-label">New Password</label>

                <div class="col-sm-8">
                  <input type="password" name="password" class="form-control" id="password" required />
                </div>
              </div>

              <div class="form-group">
                <label for="confirm_pwd" class="col-sm-3 control-label">Confirm Password</label>

                <div class="col-sm-8">
                  <input type="password" name="confirm_pwd" class="form-control" id="confirm_pwd" required />
                </div>
              </div>

              <div class="form-group">
                <div class="col-md-11">
                  <input type="submit" name="submit" value="Change Password" class="btn btn-info pull-right">
                </div>
              </div>
            <?php echo form_close( ); ?>
      </div>
    </div>
  </div>  

</section> 


 <script>
    $("#users").addClass('active');
  </script>