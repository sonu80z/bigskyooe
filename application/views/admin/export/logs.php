<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-body with-border">
                <div class="col-md-6 a_page_top_title">
                    <?=$title;?>
                </div>
                <div class="col-md-6 text-right">
                    <button class="btn btn-primary" id="a_export_logs">Export CSV</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box-body table-responsive">
                <div class="a_logs_saerch_dv row">
                    <div class="col-md-4">
                        <input type="radio" name="log_type" value="0" id="a_log_type1" <?php if($actor_type=="0") echo "checked";?> /><label for="a_log_type1">&nbsp;Bakcend&nbsp;&nbsp;&nbsp;</label>
                    </div>
                    <div class="col-md-8 g_txt_right">
                        <select name="log_year" class="g_ipt_o">
                        <?php
                        for ( $i = intval(date("Y")); $i > intval(date("Y")) - 10; $i -- )
                            echo '<option value="'.$i.'" '.(($year==$i)?"selected":"").'>'.$i.'</option>';
                        ?>
                        </select> Year &nbsp;&nbsp;
                        <select name="logs_month" class="g_ipt_o">
                        <?php
                        for ( $i = 0; $i < 13; $i ++ )
                            echo '<option value="'.$i.'" '.(($month==$i)?"selected":"").'>'.(($i==0)?"All":$i).'</option>';
                        ?>
                        </select> Month &nbsp;&nbsp;
                        <select name="logs_day" class="g_ipt_o">
                        <?php
                        for ( $i = 0; $i < 32; $i ++ )
                            echo '<option value="'.$i.'" '.(($day==$i)?"selected":"").'>'.(($i==0)?"All":$i).'</option>';
                        ?>
                        </select> Day &nbsp;&nbsp;
                        <input type="text" name="logs_actor_username" value="<?php if(isset($actor_username)) echo $actor_username;?>" class="g_ipt_o" placeholder="Actor Username" />
                        <input type="text" name="logs_action_tag" value="<?php if(isset($action_tag)) echo $action_tag;?>" class="g_ipt_o" placeholder="Action Tag" />
                        <input type="text" name="logs_to" value="<?php if(isset($to)) echo $to;?>" class="g_ipt_o" placeholder="To" />
                        <input type="button" name="logs_search_button" class="g_ipt_o" value="Search" />
                    </div>
                </div>
                <table class="table table-bordered table-striped" id="a_logs_list_tb">
                    <thead class="a_n_thead">
                    <tr>
                        <th>NO</th>
                        <th>Datetime</th>
                        <th>Actor Username</th>
<!--                        <th>Action Type</th>-->
                        <th>Action Tag</th>
                        <th>Action Comment</th>
                        <th>To</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no = 0; foreach( $logs as $r ):
                        $no ++;
                        $logs_commentes = unserialize(LOGAS_COMMENTS);
                    ?>
                        <tr did="<?=$r['id'];?>">
                            <td><?=$no;?></td>
                            <td><?=$r['logtime']?></td>
                            <td><?=$r['actor_username']?></td>
<!--                            <td>--><?//=$action_type;?><!--</td>-->
                            <td><?=$r['action_tag']?></td>
                            <td><?=$logs_commentes[$r['action_tag']];?></td>
                            <td><?=$r['to']?></td>
                        </tr>
                    <?php endforeach;
                    if ( count($logs) < 1 ) {
                        echo '<tr><td class="g_txt_center" colspan="7">There are no logs</td></tr>';
                    }?>
                    </tbody>
                </table>
                <div class="a_logs_pagination_dv row">
                    <?php
                    $tpage = intval(intval($cnt) / $limit ); if ( $cnt - $limit * $tpage > 0 ) $tpage ++;
                    if ( $tpage == 0 ) $tpage ++;
                    ?>
                    <div class="col-md-4">Showing <?= ($tpage-1)*$limit;?> to <?= $tpage*$limit;?> of <?= $cnt;?> entries</div>
                    <div class="col-md-8 g_txt_right">
                        <input type="button" name="logs_previous_button" class="g_ipt_o" value="Previous" <?php if($page=="1") echo "disabled";?> />
                        <select name="logs_page_num" class="g_ipt_o">
                        <?php
                        for ( $i = 1; $i < $tpage + 1; $i ++ )
                            echo '<option value="'.$i.'" '.(($page==$i)?"selected":"").'>'.$i.'</option>';
                        ?>
                        <input type="button" name="logs_next_button" class="g_ipt_o" value="Next" <?php if($page==$tpage) echo "disabled";?> />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo form_open(base_url('admin/export/logs'), 'class="form-horizontal"');  ?>
        <input type="hidden" name="al_actor_type" value="<?php if(isset($actor_type)) echo $actor_type;?>" />
        <input type="hidden" name="al_year" value="<?php if(isset($year)) echo $year;?>" />
        <input type="hidden" name="al_month" value="<?php if(isset($month)) echo $month;?>" />
        <input type="hidden" name="al_day" value="<?php if(isset($day)) echo $day;?>" />
        <input type="hidden" name="al_actor_username" value="<?php if(isset($actor_username)) echo $actor_username;?>" />
        <input type="hidden" name="al_action_tag" value="<?php if(isset($action_tag)) echo $action_tag;?>" />
        <input type="hidden" name="al_to" value="<?php if(isset($to)) echo $to;?>" />
        <input type="hidden" name="al_page" value="<?php if(isset($page)) echo $page;?>" />
        <input type="submit" name="submit" class="g_none_dis" />
    <?php echo form_close( ); ?>
</section>

<!-- DataTables loaded in layout.php -->


