<?php

    require_once("../include/dbsetting/lms_vars_config.php");
    require_once("../include/dbsetting/classdbconection.php");
    require_once ("../include/functions/functions.php");
    $dblms = new dblms();

	// API From MSD Cats
	$content = file_get_contents("https://msd.gptech.pk/api/api.php?method=cats&items=200");
	$data = json_decode($content,true);

        $add = 0;
        $update = 0; 
    
        if($data['result_code'] == '000') {
            foreach($data['searched_cats'] as $cat) {

                $sqllmsCats = $dblms->querylms("SELECT cat_id
                                                        FROM ".INVENTORY_CATEGORY."
                                                        WHERE cat_id_msd = '".$cat['id']."' LIMIT 1");
                if(mysqli_num_rows($sqllmsCats) > 0) {
                    
                    // Update
                    $sqllmsUpdate  = $dblms->querylms("UPDATE ".INVENTORY_CATEGORY." SET  
                                                                                  cat_id_msd 	= '".cleanvars($cat['id'])."'
                                                                                , cat_name		= '".cleanvars($cat['name'])."'
                                                                                , cat_detail    = '".cleanvars($cat['details'])."'
                                                                            WHERE cat_id_msd	= '".cleanvars($cat['id'])."'");

                    if($sqllmsUpdate) { $update++; }
                } else {
                    
                    // Insert
                    $sqllmsAdd  = $dblms->querylms("INSERT INTO ".INVENTORY_CATEGORY."(
                                                                                cat_id_msd          ,
                                                                                cat_status			,
                                                                                cat_name	        ,
                                                                                cat_detail			
                                                                            ) VALUES (
                                                                                '".cleanvars($cat['id'])."'	        , 
                                                                                '1'				                    , 
                                                                                '".cleanvars($cat['name'])."'       , 
                                                                                '".cleanvars($cat['details'])."'   	
                                                                            )" );

                if($sqllmsAdd) { $add++; }
                }
            }
        }


        // echo 'Add: '. $add .'<br> Update: '.$update;
?>