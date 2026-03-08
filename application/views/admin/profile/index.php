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
    <div class="col-md-12">
        <div class="box-body my-form-body">
            <div class="alert alert-warning alert-dismissible <?php if ( !isset($msg)) echo "g_none_dis";?>" id="a_admin_add_alert">
                <button type="button" class="close" id="a_add_admin_alert_close_btn" aria-hidden="true">×</button>
                <?php if ( isset($msg)) echo $msg;?>
                <div></div>
            </div>
            <?php echo form_open(base_url('admin/profile'), 'class="form-horizontal"' )?>
              <div class="form-group">
                <label for="username" class="col-sm-2 control-label">User Name *</label>

                <div class="col-sm-9">
                  <input type="text" value="<?= $admin['username']; ?>" class="form-control" id="username" disabled />
                    <input type="hidden" name="username" value="<?= $admin['username']; ?>" class="g_none_dis" />
                </div>
              </div>
              <div class="form-group">
                <label for="firstname" class="col-sm-2 control-label">First Name *</label>

                <div class="col-sm-9">
                  <input type="text" name="firstname" value="<?= $admin['firstname']; ?>" class="form-control" id="firstname" required />
                </div>
              </div>

              <div class="form-group">
                <label for="lastname" class="col-sm-2 control-label">Last Name *</label>

                <div class="col-sm-9">
                  <input type="text" name="lastname" value="<?= $admin['lastname']; ?>" class="form-control" id="lastname" required />
                </div>
              </div>

              <div class="form-group">
                <label for="email" class="col-sm-2 control-label">Email *</label>

                <div class="col-sm-9">
                  <input type="email" name="email" value="<?= $admin['email']; ?>" class="form-control" id="email" required />
                </div>
              </div>
              <div class="form-group">
                <label for="mobile_no" class="col-sm-2 control-label">Mobile No *</label>

                <div class="col-sm-9">
                  <input type="number" name="mobile_no" value="<?= $admin['phone']; ?>" class="form-control" id="mobile_no" required />
                </div>
              </div>
            <div class="form-group">
                <label for="mobile_no" class="col-sm-2 control-label">Profile Image</label>

                <div class="col-sm-9">
                    <div class="p_a_g_p_div" photo="">
                        <?php
                        $profile_image = base_url()."public/dist/img/user2-160x160.jpg";
                        if ( $admin['profile_image'] != "" ) $profile_image = base_url()."uploads/profiles/".$admin['profile_image'];
                        ?>
                        <img src="<?= $profile_image;?>" id="c_t_p_add_gallery_image" />
                    </div>
                </div>
            </div>
              <div class="form-group">
                <div class="col-md-11">
                    <input type="hidden" id="c_t_p_profile_image" name="profile_image" value="<?= $admin['profile_image']; ?>" />
                  <input type="submit" name="submit" value="Update Profile" class="btn btn-info pull-right">
                    <input type="text" name="ap_2fa" class="form-control"  id="a_profile_2fa_ipt" placeholder="2FA CODE" required />
                </div>
              </div>
            <?php echo form_close(); ?>
      </div>
    </div>
  </div>  

</section>
<?php echo form_open_multipart('admin/profile/user_photo_upload');?>
<input type="file" name="image" size="20" id="c_t_p_photo_upload_ipt" class="g_none_dis" />
<input type="submit" value="upload" id="c_t_p_photos_submit" is_gallery="" class="g_none_dis" />
</form>



 <script>
    $("#users").addClass('active');
  </script>