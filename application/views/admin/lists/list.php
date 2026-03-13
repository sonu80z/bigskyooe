
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-body with-border">
                <div class="col-md-4 a_page_top_title">
                    <?=$title;?>
                </div>
                <div class="col-md-4">
                    <label>Filter by Category:</label>
                    <select id="category-filter" class="form-control" style="margin-top: 5px;">
                        <option value="">All Categories</option>
                        <option value="division">Division</option>
                        <option value="exception">Exception</option>
                        <option value="icd">ICD Code 10</option>
                        <option value="insurance_company">Insurance Company</option>
                        <option value="insurance_type">Insurance Type</option>
                        <option value="modality">Modality</option>
                        <option value="pcategory">Procedure Category</option>
                        <option value="radiologist">Radiologist</option>
                        <option value="relationship">Relationship</option>
                        <option value="Lab">Lab</option>
                    </select>
                </div>
                <div class="col-md-4 text-right" style="padding-top: 25px;">
                    <button class="btn btn-success" id="list_export_btn" type="button"><i class="fa fa-download"></i> Export Results</button>
                    <a href="<?= base_url('admin/listitem/add'); ?>" class="btn btn-default">ADD LIST/ITEM</a>
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
                        <th>Category</th>
                        <th>Value</th>
                        <th>Code</th>
                        <th>Created Datetime</th>
                        <th style="min-width: 100px;">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
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
    var listTable = jQuery('#na_datatable').DataTable({
        "processing": true,
        "serverSide": false,
        "ajax": {
            "url": "<?= base_url('admin/listitem/get_lists_ajax'); ?>",
            "type": "POST",
            "dataSrc": "data"
        },
        "columns": [
            { "data": null, "render": function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }},
            { "data": "name" },
            { "data": "value" },
            { "data": "code" },
            { "data": "created_at" },
            { "data": "id", "orderable": false, "render": function(data, type, row) {
                return '<a title="View" class="btn btn-sm btn-info" href="<?= base_url("admin/listitem/edit/"); ?>' + data + '"> <i class="fa fa-eye"></i></a> ' +
                       '<a title="Edit" class="btn btn-sm btn-primary" href="<?= base_url("admin/listitem/edit/"); ?>' + data + '"> <i class="fa fa-pencil-square-o"></i></a> ' +
                       '<a title="Delete" class="btn btn-sm btn-danger" data-href="<?= base_url("admin/listitem/delete/"); ?>' + data + '" data-toggle="modal" data-target="#confirm-delete"> <i class="fa fa-trash-o"></i></a>';
            }}
        ],
        "order": [[1, 'asc']],
        "responsive": true,
        "deferRender": true
    });
    
    // Category filter functionality
    jQuery('#category-filter').on('change', function() {
        var category = this.value;
        if(category === '') {
            listTable.column(1).search('').draw();
        } else {
            listTable.column(1).search('^' + category + '$', true, false).draw();
        }
    });
</script>

<script type="text/javascript">
    $('#confirm-delete').on('show.bs.modal', function(e) {
        $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    });

    $('#list_export_btn').on('click', function(e) {
        e.preventDefault();
        var csvContent = "data:text/csv;charset=utf-8,";
        var headers = ["NO", "Category", "Value", "Code", "Created Datetime"];
        csvContent += headers.map(function(h) { return '"' + h.replace(/"/g, '""') + '"'; }).join(",") + "\n";
        var rowNum = 0;
        listTable.rows({ search: 'applied' }).every(function() {
            rowNum++;
            var d = this.data();
            var rowValues = [rowNum, d.name, d.value, d.code, d.created_at];
            csvContent += rowValues.map(function(v) { return '"' + $('<div>').html(v || '').text().replace(/"/g, '""') + '"'; }).join(",") + "\n";
        });
        var link = document.createElement("a");
        link.setAttribute("href", encodeURI(csvContent));
        link.setAttribute("download", "lists_export_" + new Date().getTime() + ".csv");
        link.click();
        showToast('Export completed successfully', 'success');
    });
</script>
