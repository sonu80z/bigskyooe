
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
                <th>Create Datetime</th>
                <th>Action</th>
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
            $role = '';
            if ( $row['role'] == 2 ) {
                $role = "Admin";
            } else if ( $row['role'] == 3 ) {
                $role = "Coder";
            } else if ( $row['role'] == 4 ) {
                $role = "Dispatcher";
            } else if ( $row['role'] == 5 ) {
                $role = "Staff";
            } else if ( $row['role'] == 6 ) {
                $role = "Facility User";
            } else if ( $row['role'] == 7 ) {
                $role = "Ordering Physician";
            } else if ( $row['role'] == 8 ) {
                $role = "Technologist";
            }
            $state = '';
            if ( $row["mainstate"] == 1 ) {
              $state = 'Arizona';
            } else if ( $row["mainstate"] == 2 ) {
              $state = 'California';
            } else if ( $row["mainstate"] == 3 ) {
              $state = 'Colorado';
            } else if ( $row["mainstate"] == 4 ) {
              $state = 'Utah';
            }
            $facilities = "";
            if ($row["facility"] != ""){
                $facility_ids = explode('=', $row["facility"]);
                $facility_query = "SELECT facility_name FROM `tbl_facility` WHERE ";
                foreach ($facility_ids as $facility_id){

                    if (end($facility_ids) == $facility_id){
                        $facility_query = $facility_query . "id=" . $facility_id;
                    }else {
                        $facility_query = $facility_query . "id=" . $facility_id . " OR ";
                    }
                }

                $facility_names_array = $this->db->query($facility_query)->result_array();

                $facilities = convert_multi_array($facility_names_array);
            }



            echo '<tr id="'.$row["id"].'">
                      <td>'.$row["id"].'</td>
                      <td>'.$row["username"].'</td>
                      <td>'.$row["fullname"].'</td>
                      <td>'.$row["email"].'</td>
                      <td>'.$role.'</td>
                      <td style="width: 200px">'.$facilities.'</td>
                      <td>'.$state.'</td>
                      <td>'.$row["created_at"].'</td>
                      <td>
                         <a title="Delete" class="delete btn btn-sm btn-danger pull-right <span>" data-href="'.base_url().'admin/users/del/'.$row["id"].'" data-toggle="modal" data-target="#confirm-delete"> <i class="fa fa-trash-o"></i></a>
                         <a title="Edit" class="update btn btn-sm btn-primary pull-right" href="'.base_url().'admin/users/edit/'.$row["id"].'"> <i class="fa fa-pencil-square-o"></i></a>
                         <a title="View" class="view btn btn-sm btn-info pull-right" href="'.base_url().'admin/users/view/'.$row["id"].'"> <i class="fa fa-eye"></i></a></td>
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
      jQuery('#na_datatable').DataTable({"lengthMenu": [[10, 20, 50, 100], [10, 20, 50, 100]], responsive: true});
  </script>

  <script type="text/javascript">
      $('#confirm-delete').on('show.bs.modal', function(e) {
        $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
      });
  </script>
