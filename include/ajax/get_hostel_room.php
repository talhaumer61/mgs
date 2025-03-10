<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if(isset($_POST['id_hostel'])){
    $sqllmscheck = array ( 
                            'select' 	=> '
                                                  '.HOSTEL_ROOMS.'.room_id
                                                , '.HOSTEL_ROOMS.'.room_name
                                                , '.HOSTEL_ROOMS.'.room_beds
                                                , '.HOSTEL_ROOMS.'.room_bedfee
                                            ',
                            'where' 	=> array( 
                                                      HOSTEL_ROOMS.'.is_deleted'  => '0'
                                                    , HOSTEL_ROOMS.'.room_status' => '1'
                                                    , HOSTEL_ROOMS.'.id_hostel'   => cleanvars($_POST['id_hostel'])
                                                    , HOSTEL_ROOMS.'.id_campus'   => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                ),
                            'return_type' 	=> 'all' 
                        ); 
	$rowsQueryCheck  	= $dblms->getRows(HOSTEL_ROOMS, $sqllmscheck);
    echo'<option value="">Select</option>';
    foreach ($rowsQueryCheck as $key => $val):
        $sqllmsRoomFree = array ( 
                                'select' 	=> '
                                                      '.HOSTELS_REGISTRATION.'.id
                                                    , '.HOSTELS_REGISTRATION.'.id_user
                                                ',
                                'where' 	=> array( 
                                                          HOSTELS_REGISTRATION.'.is_deleted'    => '0'
                                                        , HOSTELS_REGISTRATION.'.status'        => '1'
                                                        , HOSTELS_REGISTRATION.'.id_campus'     => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                        , HOSTELS_REGISTRATION.'.id_hostel'     => cleanvars($_POST['id_hostel'])
                                                        , HOSTELS_REGISTRATION.'.id_room'       => cleanvars($val['room_id'])
                                                    ),
                                'return_type' 	=> 'count' 
                            ); 
        $rowsQueryCheckRoomFree  	= $dblms->getRows(HOSTELS_REGISTRATION, $sqllmsRoomFree);	
        if ($val['room_beds'] > $rowsQueryCheckRoomFree) {
            echo '<option value="'.$val['room_id'].'|'.$val['room_beds'].'|'.$val['room_bedfee'].'">( '.abs($rowsQueryCheckRoomFree-$val['room_beds']).' ) Beds Free In Room ( '.$val['room_name'].' )</option>';
        }
    endforeach;
}
?>