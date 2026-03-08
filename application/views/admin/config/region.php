<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-body with-border">
                <div class="col-md-6">
                    <h4><i class="fa fa-plus"></i> &nbsp; <?=$title;?></h4>
                </div>
                <div class="col-md-6 text-right">
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
                <!-- form start -->
                <div class="box-body my-form-body">
                    <div class="row a_c_r_row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
                            <div>
                                <input class="g_ipt" placeholder="Country" />
                                <a title="Add" id="a_c_r_country_add" class="update btn btn-sm btn-primary " href="javascript: void(0);"> <i class="fa fa-plus"></i></a>
                            </div>
                            <div class="a_c_r_country_list a_c_r_list">
                                <?php
                                foreach ( $countries as $r ) {
                                    $clicked = ""; if ( $r["id"] == $country ) $clicked = "a_c_r_county_clicked";
                                    echo '<div class="'.$clicked.'" did="'.$r["id"].'">
                                            <a title="Select Country" href="'.base_url().'admin/config/region/'.$r["id"].'">'.$r["title"].'</a>
                                            <a title="Delete" class="delete btn btn-sm btn-danger" data-href="'.base_url().'admin/config/region_delete_country/'.$r["id"].'" data-toggle="modal" data-target="#confirm-delete"> <i class="fa fa-trash-o"></i></a>
                                        </div>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
                            <div>
                                <?php
                                    $disabled = "disabled";
                                    if ( isset( $country) && $country != "" ) $disabled = "";
                                ?>
                                <input class="g_ipt" placeholder="State" <?=$disabled;?> />
                                <a title="Add" id="a_c_r_city_add" class="update btn btn-sm btn-primary " href="javascript: void(0);"> <i class="fa fa-plus"></i></a>
                            </div>
                            <div class="a_c_r_city_list a_c_r_list">
                                <?php
                                if ( isset( $country) && $country != "" ) {
                                    foreach ( $cities as $r ) {
                                        $clicked = ""; if ( $r["id"] == $city ) $clicked = "a_c_r_city_clicked";
                                        echo '<div class="'.$clicked.'" did="'.$r["id"].'">
                                            <a title="Select Country" href="'.base_url().'admin/config/region/'.$country.'/'.$r["id"].'">'.$r["title"].'</a>
                                            <a title="Delete" class="delete btn btn-sm btn-danger" data-href="'.base_url().'admin/config/region_delete_city/'.$country.'/'.$r["id"].'" data-toggle="modal" data-target="#confirm-delete"> <i class="fa fa-trash-o"></i></a>
                                        </div>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
                            <div>
                                <?php
                                $disabled = "disabled";
                                if ( isset( $city ) && $country != "" ) $disabled = "";
                                ?>
                                <input class="g_ipt" placeholder="City" <?=$disabled;?> />
                                <a title="Add" id="a_c_r_area_add" class="update btn btn-sm btn-primary " href="javascript: void(0);"> <i class="fa fa-plus"></i></a>
                            </div>
                            <div class="a_c_r_area_list a_c_r_list">
                                <?php
                                if ( isset( $city ) && $country != "" ) {
                                    foreach ( $areas as $r ) {
                                        echo '<div class="'.$clicked.'" did="'.$r["id"].'">
                                            <a title="Select Country" href="javascript:void(0);">'.$r["title"].'</a>
                                            <a title="Delete" class="delete btn btn-sm btn-danger" data-href="'.base_url().'admin/config/region_delete_area/'.$country.'/'.$city.'/'.$r["id"].'" data-toggle="modal" data-target="#confirm-delete"> <i class="fa fa-trash-o"></i></a>
                                        </div>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>

</section>

<!-- Detail View Modal -->
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
    $(function () {
        $('#confirm-delete').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
        });

    });
</script>