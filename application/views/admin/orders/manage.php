<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-body with-border">
                <div class="col-md-6 a_page_top_title">
                    <?=$title;?>
                </div>
                <div class="col-md-6 text-right">
                    <button class="btn btn-sm btn-info">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box-body my-form-body ao_cnt_dv">
                <div class="ade_cnt">
                    <div class="row">
                        <div class="col-sm-9"></div>
                        <div class="col-sm-3">
                            <select name="aoms_kind" class="form-control">
                                <option value="1">Nursing Home</option>
                                <option value="2">Correctional Facility</option>
                                <option value="3">Home Bound</option>
                                <option value="3">Lab</option>
                            </select>
                        </div>
                    </div>
                    <div class="row ade_th_row">
                        <div class="col-sm-3">Name</div>
                        <div class="col-sm-3">Display Format</div>
                        <div class="col-sm-3">Default Value</div>
                        <div class="col-sm-3">Mandatory/Non-Mandatory</div>
                    </div>
                    <?php
                    $items = array("Last Name", "First Name", "Middle Name", "Suffix(Jr, Sr, II)", "Patient MR", "DOB (MM-DD-YYYY)", "Patient SSN", "Sex", "Ordering Facility", "Ordered By", "Address", "City", "State", "Zip", "Phone", "Fax");
                    $no = 0;
                    foreach ($items as $row) {
                        echo '<div class="row ade_td_row">
                        <div class="col-sm-3">'.$row.'</div>
                        <div class="col-sm-3">
                            <select class="g_ipt">
                                <option value="1">Text Filed</option>
                                <option value="2">Drop Down</option>
                                <option value="3">TextArea</option>
                                <option value="4">CheckBox</option>                            
                            </select>
                        </div>
                        <div class="col-sm-3"><input type="text" class="g_ipt" /></div>
                        <div class="col-sm-3">
                            <input type="checkbox" value="1" id="mn_'.(++$no).'" />
                            <label for="mn_'.($no).'">Mandatory/Non-Mandatory</label>
                        </div>
                    </div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>