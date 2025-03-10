<?php

    require_once("../include/dbsetting/lms_vars_config.php");
    require_once("../include/dbsetting/classdbconection.php");
    require_once ("../include/functions/functions.php");
    $dblms = new dblms();

	// API From MSD Items
	$content = file_get_contents("https://msd.gptech.pk/api/api.php?method=items&items=1500");
	$data = json_decode($content,true);

    $add = 0;
    $update = 0; 

    if($data['result_code'] == '000') {
        foreach($data['searched_items'] as $item) {
            $sqllmsPrd = $dblms->querylms("SELECT item_id
                                                    FROM ".INVENTORY_ITEMS."
                                                    WHERE item_code = '".$item['code']."' LIMIT 1");
            if(mysqli_num_rows($sqllmsPrd) > 0) {

                // Update
                $sqllmsUpdate  = $dblms->querylms("UPDATE ".INVENTORY_ITEMS." SET  
                                                                              item_id_msd	    = '".cleanvars($item['id'])."'
                                                                            , item_name		    = '".cleanvars($item['name'])."'
                                                                            , id_cat	        = '".cleanvars($item['cat_id'])."'
                                                                            , headoffice_price  = '".cleanvars($item['price'])."'
                                                                        WHERE item_code		    = '".cleanvars($item['code'])."'");

                if($sqllmsUpdate) { $update++; }
            } else {

                // Insert
                $sqllmsAdd  = $dblms->querylms("INSERT INTO ".INVENTORY_ITEMS."(
                                                                            item_id_msd         ,
                                                                            item_status			,
                                                                            id_cat	            ,
                                                                            item_name			,
                                                                            item_code		    ,  
                                                                            headoffice_price	,
                                                                            id_supplier         	
                                                                        ) VALUES (
                                                                            '".cleanvars($item['id'])."'	    , 
                                                                            '1'				                    , 
                                                                            '".cleanvars($item['cat_id'])."'    , 
                                                                            '".cleanvars($item['name'])."'	    , 
                                                                            '".cleanvars($item['code'])."'	    , 
                                                                            '".cleanvars($item['price'])."'	    ,
                                                                            '1'                                 		
                                                                        )" );
                if($sqllmsAdd) { $add++; }
            }
        }
    }
    // echo 'Add: '. $add .'<br> Update: '.$update;

?>