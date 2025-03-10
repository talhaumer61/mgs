<?php 
//----------------Submit Remarks----------------------
if(isset($_POST['submit_remarks'])) { 
	$sqllms  = $dblms->querylms("INSERT INTO ".SYLLABUS_REAMRKS."(
                                                        cover_percentage					, 
                                                        reamarks                            ,
														id_session							, 
														id_syllabus							, 
														id_teacher							,
														id_campus							,
														id_added							, 
														date_added 	
													  )
	   											VALUES(
														'".cleanvars($_POST['cover_percentage'])."'	                    , 
														'".cleanvars($_POST['reamarks'])."'                     	    , 
														'".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'	,
														'".cleanvars($_POST['id_syllabus'])."'						    ,
														'".cleanvars($_POST['id_teacher'])."'						    ,
														'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'	    ,
														'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'		    ,
														NOW()
													  )"
							);
							
	//--------------------------------------
	$syllabus_id = $dblms->lastestid();
	//--------------------------------------
    if($sqllms) { 
        //--------------------------------------
        $remarks = 'Add DLP Remarks, ID: "'.cleanvars($syllabus_id).'" detail';
        $sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
                                                            id_user										, 
                                                            filename									, 
                                                            action										,
                                                            dated										,
                                                            ip											,
                                                            remarks				
                                                            )
        
                                                    VALUES(
                                                            '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'	,
                                                            '".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' , 
                                                            '1'											, 
                                                            NOW()										,
                                                            '".cleanvars($ip)."'						,
                                                            '".cleanvars($remarks)."'			
                                                            )
                                    ");
        $ref = '?id='.$_POST['id_subject'].'&class='.$_POST['id_class'].'&view=dlp';
        //--------------------------------------
        $_SESSION['msg']['title'] 	= 'Successfully';
        $_SESSION['msg']['text'] 	= 'Record Successfully Added.';
        $_SESSION['msg']['type'] 	= 'success';
        header("Location: subject.php.$ref", true, 301);
        exit();
        //--------------------------------------
    }
}
?>