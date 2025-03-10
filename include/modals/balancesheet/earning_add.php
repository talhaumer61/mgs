<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('27', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '27', 'add' => '1'))) {
    echo'
    <div id="make_earning" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
        <section class="panel panel-featured panel-featured-primary">
            <form action="earning.php" class="form-horizontal validate" method="post" accept-charset="utf-8" novalidate="novalidate">
            <div class="panel-heading">
                <h4 class="panel-title"><i class="fa fa-plus-square"></i> Make Income Voucher</h4>
            </div>
            <div class="panel-body">
                <div class="form-group mt-sm">
                    <label class="col-sm-3 control-label">Title <span class="required" aria-required="true">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="trans_title" autofocus="" required="" title="Must Be Required" aria-required="true">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Head <span class="required" aria-required="true">*</span></label>
                    <div class="col-sm-9">
                        <select name="id_head" class="form-control select2-hidden-accessible" required="" data-plugin-selecttwo="" data-width="100%" data-minimum-results-for-search="Infinity" title="Must Be Required" aria-required="true" tabindex="-1" aria-hidden="true">
                            <option value="">Select Earning Head</option>';
                            $sqllmshead	= $dblms->querylms("SELECT head_id, head_name
                                                            FROM ".ACCOUNT_HEADS."
                                                            WHERE head_type = '1' AND head_status = '1' AND is_deleted != '1'
                                                            AND id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
                                                            ORDER BY head_name ASC");
                            while($value_head = mysqli_fetch_array($sqllmshead)) {
                                echo'<option value="'.$value_head['head_id'].'">'.$value_head['head_name'].'</option>';
                            }
                            echo'
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Amount <span class="required" aria-required="true">*</span></label>
                    <div class="col-sm-9">
                        <input type="number" class="form-control" name="trans_amount" required="" title="Must Be Required" aria-required="true">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Method</label>
                    <div class="col-sm-9">
                        <div class="radio-custom radio-primary radio-inline">
                            <input type="radio" value="1" id="trans_method" name="trans_method" checked="">
                            <label for="trans_method">Cash</label>
                        </div>
                        <div class="radio-custom radio-primary radio-inline">
                            <input type="radio" value="2" id="trans_method" name="trans_method">
                            <label for="trans_method">Check</label>
                        </div>
                    </div>
                </div>';
                $date = date("Ym");
                $sqllmschallan = $dblms->querylms("SELECT challan_no FROM ".FEES." 
													WHERE challan_no LIKE '".$date."%'  
													ORDER by challan_no DESC LIMIT 1 ");
				if(mysqli_num_rows($sqllmschallan) == 1){		
					$rowchallan = mysqli_fetch_array($sqllmschallan);
					$challano = ($rowchallan['challan_no'] +1);
				}else{
					$challano = $date.'00001';
				}
                echo '
                <div class="form-group">
                    <label class="col-sm-3 control-label">Challan ID <span class="required" aria-required="true">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="voucher_no" value="'.$challano.'" required="" title="Must Be Required" aria-required="true" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Ref. Number</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="bill_number" title="Must Be Required" aria-required="true">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"> Date <span class="required" aria-required="true">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" data-plugin-datepicker="" data-plugin-options="{ &quot;todayHighlight&quot; : true }" name="dated" required="" title="Must Be Required" autocomplete="off" aria-required="true">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"> Due Date <span class="required" aria-required="true">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" data-plugin-datepicker="" data-plugin-options="{ &quot;todayHighlight&quot; : true }" name="due_date" required="" title="Must Be Required" autocomplete="off" aria-required="true">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Note</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="trans_note">
                    </div>
                </div>
            </div>
            <footer class="panel-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" id="submit_earning" name="submit_earning" class="btn btn-primary">Save</button>
                        <button class="btn btn-default modal-dismiss">Cancel</button>
                    </div>
                </div>
            </footer>
            </form>
        </section>
    </div>';
}
?>