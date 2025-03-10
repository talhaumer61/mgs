<?php 
// INSERT HOSTEL REGISTRATION
if(isset($_POST['submit_hostel_registration'])) { 
	$id_campus = (!empty($_POST['id_campus']) ? cleanvars($_POST['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']));
	
	$sqllmscheck = array ( 
							'select' 	=> '
												id 
											',
							'where' 	=> array( 
													  'is_deleted'  => '0'
													, 'status'    	=> '1'
													, 'id_campus'   => cleanvars($id_campus)
													, 'id_user'    	=> cleanvars($_POST['id_user'])
												),
							'return_type' 	=> 'count' 
						); 
	$rowsQueryCheck  = $dblms->getRows(HOSTELS_REGISTRATION, $sqllmscheck);	
	if($rowsQueryCheck) {
		sessionMsg("Error", "Record Already Exists.", "error");
		header("Location: hostel_students.php", true, 301);
		exit();
	} else { 
		$id_roms_array 	= explode('|', cleanvars($_POST['id_room']));
		$id_room 		= cleanvars($id_roms_array[0]);
		$room_size 		= cleanvars($id_roms_array[1]);
		$id_hostel		= cleanvars($_POST['id_hostel']);
		$id_class		= cleanvars($_POST['id_class']);
		$date_start 	= date("Y-m-d",strtotime(cleanvars($_POST['date_start'])));
		$date_end 		= date("Y-m-d",strtotime(cleanvars($_POST['date_end'])));
		$id_user_array 	= explode('|', cleanvars($_POST['id_user']));
		$id_std			= cleanvars($id_user_array[0]);
		$is_hostel		= ($_POST['reg_status'] == '1' ? '1' : '2');

		$values = array (
							  "status"		=>	cleanvars($_POST['reg_status'])
							, "id_hostel"	=>	cleanvars($id_hostel)
							, "id_room"		=>	cleanvars($id_room)
							, "id_class"	=>	cleanvars($id_class)
							, "id_user"		=>	cleanvars($id_std)
							, "date_start"	=>	cleanvars($date_start)
							, "date_end"	=>	cleanvars($date_end)
							, "id_campus"	=>	cleanvars($id_campus)
							, "id_added"	=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							, "date_added"	=>	date('Y-m-d h:i:s')
						);
		$sqllms = $dblms->insert(HOSTELS_REGISTRATION , $values);
		if ($sqllms) {
			// UPDATE STUDENT
			$values = array (
								"is_hostel"		=>	cleanvars($is_hostel)
							);
			$sqllms = $dblms->Update(STUDENTS , $values , "WHERE std_id = '".cleanvars($id_std)."'");

			sessionMsg("Success", "Record Added Successfully.", "success");
			header("Location: hostel_students.php", true, 301);
			exit();
		} else {
			sessionMsg("Error", "Record Not Added.", "error");
			header("Location: hostel_students.php", true, 301);
			exit();
		}
	}
}

// UPDATE HOSTEL REGISTRATION
if(isset($_POST['edit_hostel_registration'])) { 
	$id_campus = (!empty($_POST['id_campus']) ? cleanvars($_POST['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']));

	$id = cleanvars($_POST['id']);
	$sqllmscheck = array ( 
							'select' 	=> '
												id 
											',
							'where' 	=> array( 
													  'is_deleted'  => '0'
													, 'status'    	=> '1'
													, 'id_campus'   => cleanvars($id_campus)
													, 'id_user'    	=> cleanvars($_POST['id_user'])
												),
							'not_equal' => array( 
													'id'			=>	$id 	
												),
							'return_type' 	=> 'count' 
						); 
	$rowsQueryCount  = $dblms->getRows(HOSTELS_REGISTRATION, $sqllmscheck);	
	if($rowsQueryCount) {
		sessionMsg("Error", "Record Already Exists.", "error");
		header("Location: hostel_students.php", true, 301);
		exit();
	} else { 
		$id_roms_array 	= explode('|', cleanvars($_POST['id_room']));
		$id_room 		= cleanvars($id_roms_array[0]);
		$room_size 		= cleanvars($id_roms_array[1]);
		$id_hostel		= cleanvars($_POST['id_hostel']);
		$id_class		= cleanvars($_POST['id_class']);
		$date_start 	= date("Y-m-d",strtotime(cleanvars($_POST['date_start'])));
		$date_end 		= date("Y-m-d",strtotime(cleanvars($_POST['date_end'])));
		$id_std			= cleanvars($_POST['id_std_edit']);
		$id_std_edit	= cleanvars($_POST['id_std_edit']);
		$is_hostel		= ($_POST['reg_status'] == '1' ? '1' : '2');
		
		$values = array (
							  "status"		=>	cleanvars($_POST['reg_status'])
							, "id_hostel"	=>	$id_hostel
							, "id_room"		=>	$id_room
							, "date_start"	=>	cleanvars($date_start)
							, "date_end"	=>	cleanvars($date_end)
							, "id_campus"	=>	cleanvars($id_campus)
							, "id_added"	=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							, "date_added"	=>	date('Y-m-d h:i:s')
						);		
		$sqllms = $dblms->Update(HOSTELS_REGISTRATION , $values , "WHERE id = '".cleanvars($id)."'");
		if ($sqllms) {			
			// UPDATE STUDENT
			$values = array (
								"is_hostel"		=>	cleanvars($is_hostel)
							);
			$sqllms = $dblms->Update(STUDENTS , $values , "WHERE std_id = '".cleanvars($id_std)."'");

			sessionMsg("Success", "Record Updated Successfully.", "info");
			header("Location: hostel_students.php", true, 301);
			exit();
		} else {
			sessionMsg("Error", "Record Not Updated.", "error");
			header("Location: hostel_students.php", true, 301);
			exit();
		}
	}
}

// DELETE HOSTEL REGISTRATION
if(isset($_GET['deleteid']) && isset($_GET['id_std'])){
	$values = array (
						 "is_deleted"	=>	'1'
						,"id_deleted"	=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,"ip_deleted"	=>	cleanvars($ip)
						,"date_deleted"	=>	date('Y-m-d h:i:s')
					);		
	$sqllms = $dblms->Update(HOSTELS_REGISTRATION , $values , "WHERE id = '".cleanvars($_GET['deleteid'])."'");
	if($sqllms) { 
		// UPDATE STUDENT
		$values = array (
							"is_hostel"		=>	'2'
						);		
		$sqllms = $dblms->Update(STUDENTS , $values , "WHERE std_id = '".cleanvars($_GET['id_std'])."'");

		sendRemark("Hostel Registration Deleted ID: ".$_GET['deleteid']." Detail", '3');
		sessionMsg("Success", "Record Deleted.", "success");
		header("Location: hostel_students.php", true, 301);
		exit();
	}
}
?>