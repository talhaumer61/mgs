<?php
$sqllms	= $dblms->querylms("SELECT id_ad, id_de, building_area, building_type, covered_area, total_rooms, play_grounds, washrooms, principal_name, 
                            principal_doa, principal_phone, second_phone, principal_whastapp, principal_email, principal_edu, principal_experience,
                            primary_bank, primary_account, secondary_bank, secondary_account, mec_president, mec_president_no
                            FROM ".CAMPUS_BIOGRAPHY." 
                            WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
                            ORDER BY bio_id DESC");
if(mysqli_num_rows($sqllms)>0){
    $button = '<i class="fa fa-refresh"></i> Update ';
}else{
    $button = '<i class="fa fa-plus-square"></i> Add ';
}
$rowsvalues = mysqli_fetch_array($sqllms);
echo'
<div id="biography" class="tab-pane ">
    <form action="#" class="form-horizontal validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
        <input type="hidden" name="campus_id" id="campus_id" value="'.cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']).'">
        <fieldset class="mt-lg">
            <div class="panel-body">
                <div class="form-group">
                    <div class="col-md-6">
                        <label class="control-label">ADE <span class="required">*</span></label>
                        <select class="form-control" data-plugin-selectTwo data-width="100%" name="id_ad" disabled>
                            <option value="">Select</option>';
                            $sqllmsad = $dblms->querylms("SELECT emply_id, emply_name
                                                            FROM ".EMPLOYEES."
                                                            WHERE emply_id != '' AND emply_status = '1'
                                                            AND is_ad = '1' AND id_campus = '0'
                                                            AND is_deleted != '1'
                                                            ORDER BY emply_name ASC");
                            while($valuead = mysqli_fetch_array($sqllmsad)){
                                echo '<option value="'.$valuead['emply_id'].'" '.($valuead['emply_id'] == $rowsvalues['id_ad'] ? 'selected' : '').'>'.$valuead['emply_name'].'</option>';
                            }
                            echo'
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label">Consultant <span class="required">*</span></label>
                        <select class="form-control" data-plugin-selectTwo data-width="100%" name="id_de" disabled>
                            <option value="">Select</option>';
                            $sqllmsde = $dblms->querylms("SELECT emply_id, emply_name
                                                            FROM ".EMPLOYEES."
                                                            WHERE emply_id != '' AND emply_status = '1'
                                                            AND is_de = '1' AND id_campus = '0'
                                                            AND is_deleted != '1' 
                                                            ORDER BY emply_name ASC");
                            while($valuede = mysqli_fetch_array($sqllmsde)){
                                echo '<option value="'.$valuede['emply_id'].'" '.($valuede['emply_id'] == $rowsvalues['id_de'] ? 'selected' : '').'>'.$valuede['emply_name'].'</option>';
                            }
                            echo'
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-4">
                        <label class="control-label">Building <span class="required">*</span></label>
                        <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="building_type">
                            <option value="">Select</option>';
                            foreach($buildingtype as $building){
                                echo '<option value="'.$building['id'].'" '.($rowsvalues['building_type'] == $building['id'] ? 'selected' : '').'>'.$building['name'].'</option>';
                            }
                            echo'
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="control-label"> Building Area <span class="required">*</span></label>
                        <input type="text" class="form-control" name="building_area" id="building_area" value="'.$rowsvalues['building_area'].'" required title="Must Be Required"/>
                    </div>
                    <div class="col-md-4">
                        <label class="control-label"> Covered Area <span class="required">*</span></label>
                        <input type="text" class="form-control" name="covered_area" id="covered_area" value="'.$rowsvalues['covered_area'].'" required title="Must Be Required"/>
                    </div>
                </div>
                <div class="form-group mt-sm">
                    <div class="col-md-4">
                        <label class="control-label"> Total Rooms <span class="required">*</span></label>
                        <input type="text" class="form-control" name="total_rooms" id="total_rooms" value="'.$rowsvalues['total_rooms'].'" required title="Must Be Required"/>
                    </div>
                    <div class="col-md-4">
                        <label class="control-label"> Playgrounds <span class="required">*</span></label>
                        <input type="text" class="form-control" name="play_grounds" id="play_grounds" value="'.$rowsvalues['play_grounds'].'" required title="Must Be Required"/>
                    </div>
                    <div class="col-md-4">
                        <label class="control-label"> Washrooms <span class="required">*</span></label>
                        <input type="text" class="form-control" name="washrooms" id="washrooms" value="'.$rowsvalues['washrooms'].'" required title="Must Be Required"/>
                    </div>
                </div>
                <div class="form-group mt-sm">
                    <div class="col-md-6">
                        <label class="control-label"> Principal <span class="required">*</span></label>
                        <input type="text" class="form-control" name="principal_name" id="principal_name" value="'.$rowsvalues['principal_name'].'" required title="Must Be Required"/>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label"> DOA <span class="required">*</span></label>
                        <input type="text" class="form-control" data-plugin-datepicker value="'.date('m/d/Y', strtotime($rowsvalues['principal_doa'])).'" required title="Must Be Required" name="principal_doa" id="principal_doa" autocomplete="off"/>
                    </div>
                </div>
                <div class="form-group mt-sm">
                    <div class="col-md-4">
                        <label class="control-label"> First Phone <span class="required">*</span></label>
                        <input type="text" class="form-control" name="principal_phone" id="principal_phone" value="'.$rowsvalues['principal_phone'].'" required title="Must Be Required"/>
                    </div>
                    <div class="col-md-4">
                        <label class="control-label"> Second Phone </label>
                        <input type="text" class="form-control" name="second_phone" id="second_phone"  value="'.$rowsvalues['second_phone'].'" />
                    </div>
                    <div class="col-md-4">
                        <label class="control-label"> Whatsapp <span class="required">*</span></label>
                        <input type="text" class="form-control" name="principal_whastapp" id="principal_whastapp" value="'.$rowsvalues['principal_whastapp'].'"  required title="Must Be Required"/>
                    </div>
                </div>
                <div class="form-group mt-sm">
                    <div class="col-md-4">
                        <label class="control-label"> Education <span class="required">*</span></label>
                        <input type="text" class="form-control" name="principal_edu" id="principal_edu" value="'.$rowsvalues['principal_edu'].'" required title="Must Be Required"/>
                    </div>
                    <div class="col-md-4">
                        <label class="control-label"> Experience <span class="required">*</span></label>
                        <input type="text" class="form-control" name="principal_experience" id="principal_experience" value="'.$rowsvalues['principal_experience'].'" required title="Must Be Required"/>
                    </div>
                    <div class="col-md-4">
                        <label class="control-label"> Eamil <span class="required">*</span></label>
                        <input type="text" class="form-control" name="principal_email" id="principal_email" value="'.$rowsvalues['principal_email'].'" required title="Must Be Required"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <label class="control-label"> Bank-1 Name <span class="required">*</span></label>
                        <select class="form-control" required title="Must Be Required" required data-plugin-selectTwo data-width="100%" name="primary_bank" id="primary_bank">
                            <option value="">Select</option>';
                            foreach($banklist as $bank) {
                                echo '<option value="'.$bank['id'].'" '.($bank['id']==$rowsvalues['primary_bank'] ? 'selected' : '').'>'.$bank['name'].'</option>';
                            }
                            echo'
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label"> Bank-1 Account <span class="required">*</span></label>
                        <input type="text" class="form-control" name="primary_account" id="primary_account" value="'.$rowsvalues['primary_account'].'" required title="Must Be Required"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <label class="control-label"> Bank-2 Name <span class="required">*</span></label>
                        <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" required name="secondary_bank" id="secondary_bank">
                            <option value="">Select</option>';
                            foreach($banklist as $bank) {
                                echo'<option value="'.$bank['id'].'" '.($bank['id']==$rowsvalues['secondary_bank'] ? 'selected' : '').'>'.$bank['name'].'</option>';
                            }
                            echo'
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label">  Bank-2 Account <span class="required">*</span></label>
                        <input type="text" class="form-control" name="secondary_account" id="secondary_account" value="'.$rowsvalues['secondary_account'].'" required title="Must Be Required"/>
                    </div>
                </div>
                <div class="form-group mb-md">
                    <div class="col-md-6">
                        <label class="control-label"> MEC President Name <span class="required">*</span></label>
                        <input type="text" class="form-control" name="mec_president" id="mec_president" value="'.$rowsvalues['mec_president'].'" required title="Must Be Required"/>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label">MEC President Number <span class="required">*</span></label>
                        <input type="text" class="form-control" name="mec_president_no" id="mec_president_no" value="'.$rowsvalues['mec_president_no'].'" required title="Must Be Required"/>
                    </div>
                </div>
            </div>
        </fieldset>
        <div class="panel-footer">
            <div class="row text-center">
                <div class="col-sm-12">
                    <button type="submit" id="submit_bio" name="submit_bio" class="btn btn-primary">'.$button.' Biography</button>
                </div>
            </div>
        </div>
	</form>
</div>';
  