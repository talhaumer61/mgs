<?php 
//--------------- Status ------------------
$admstatus = array (
						array('id'=>1, 'name'=>'Active')		, array('id'=>2, 'name'=>'Inactive')
				   );

function get_admstatus($id) {
	$listadmstatus= array (
							'1' => '<span class="label label-primary">Active</span>', 
							'2' => '<span class="label label-warning">Inactive</span>');
	return $listadmstatus[$id];
}
//--------------- Notification Status ------------------
$status = array (
	array('id'=>1, 'name'=>'Yes'), array('id'=>2, 'name'=>'No')
);

function get_notification($id) {
	$listnote= array (
			'1' => '<span class="label label-success">Yes</span>', 
			'2' => '<span class="label label-warning">No</span>'
		);
	return $listnote[$id];
}

//--------------- Student Status ------------------
$stdstatus = array (
	array('id'=>1, 'name'=>'Active')	,
	array('id'=>2, 'name'=>'Left')		,
	array('id'=>3, 'name'=>'Expel')		,
	array('id'=>4, 'name'=>'Freeze')	,
	array('id'=>5, 'name'=>'Passed')
);

function get_stdstatus($id) {
$liststdstatus= array (
		'1' => '<span class="label label-primary">Active</span>', 
		'2' => '<span class="label label-warning">Left</span>'	, 
		'3' => '<span class="label label-danger">Expel</span>'	, 
		'4' => '<span class="label label-info">Freeze</span>', 
		'5' => '<span class="label label-success">Passed</span>'
	);
return $liststdstatus[$id];
}
//--------------- Status ------------------
$status = array (
						array('id'=>1, 'name'=>'Active'), array('id'=>2, 'name'=>'Inactive')
				   );

function get_status($id) {
	$liststatus= array (
							'1' => '<span class="label label-primary">Active</span>', 
							'2' => '<span class="label label-warning">Inactive</span>');
	return $liststatus[$id];
}
//--------------- Leave Status ------------------
$statusLeave = array (
						array('id'=>1, 'name'=>'Approved'), array('id'=>2, 'name'=>'Pending'), array('id'=>3, 'name'=>'Rejected')
				   );

function get_leave($id) {
	$liststatus= array (
							'1' => '<span class="label label-success">Approved</span>', 
							'2' => '<span class="label label-warning">Pending</span>', 
							'3' => '<span class="label label-danger">Rejected</span>');
	return $liststatus[$id];
}
//--------------- Payments Status ------------------
$payments = array (
						array('id'=>1, 'name'=>'Paid')		, 
						array('id'=>2, 'name'=>'Pending')	, 
						array('id'=>3, 'name'=>'Unpaid')	, 
						array('id'=>4, 'name'=>'Partial Paid')
				   );

function get_payments($id) {
	$listpayments = array (
							'1' => '<span class="label label-success" id="bns-status-badge">Paid</span>'		, 
							'2' => '<span class="label label-warning" id="bns-status-badge">Pending</span>'		,
							'3' => '<span class="label label-danger" id="bns-status-badge">Unpaid</span>'		,
							'4' => '<span class="label label-info" id="bns-status-badge">Partial Paid</span>'
						  );
	return $listpayments[$id];
}

function get_payments1($id) {
	$listpayments = array (
							'1' => 'Paid'		, 
							'2' => 'Pending'	,
							'3' => 'Unpaid'		,
							'4' => 'Partial Paid'
						  );
	return $listpayments[$id];
}

//-------------- Royalty Types --------------------
$rolyaltyType = array (
	array('id'=>1, 'name'=>'Regular')			, 
	array('id'=>2, 'name'=>'Irregular')
);

function get_royaltyType($id) {
	$listRoyaltyType = array (
			'1' => 'Regular'		, 
			'2' => 'Irregular'
		);
	return $listRoyaltyType[$id];
}

//-------------- Banks --------------------
$banklist = array (
	array('id'=>1, 'name'=>'Allied Bank Limited'), 
	array('id'=>2, 'name'=>'Meezan Bank Limited'), 
	array('id'=>3, 'name'=>'Faysal Bank Limited'), 
	array('id'=>4, 'name'=>'Habeeb Bank Limited'), 
	array('id'=>5, 'name'=>'Muslim Commercial Bank'), 
	array('id'=>6, 'name'=>'Bank Alfalah'), 
	array('id'=>7, 'name'=>'Bank of Punjab')
);

function get_banklist($id) {
	$listBank = array (
			'1' => 'Allied Bank Limited', 
			'2' => 'Meezan Bank Limited', 
			'3' => 'Faysal Bank Limited', 
			'4' => 'Habeeb Bank Limited', 
			'5' => 'Muslim Commercial Bank', 
			'6' => 'Bank Alfalah', 
			'7' => 'Bank of Punjab'
		);
	return $listBank[$id];
}

//-------------- Royalty For --------------------
$rolyaltyFor = array (
	array('id'=>1, 'name'=>'All Student')		, 
	array('id'=>2, 'name'=>'According to Class'), 
	array('id'=>3, 'name'=>'Lump Sum Amount')
);

function get_royaltyFor($id) {
	$listRoyaltyFor = array (
			'1' => 'All Student'		, 
			'2' => 'According to Class', 
			'3' => 'Lump Sum Amount'
		);
	return $listRoyaltyFor[$id];
}

//-------------- Royalty For --------------------
$rolyaltyAmount = array (
	array('id'=>1, 'name'=>'Fixed')		, 
	array('id'=>2, 'name'=>'Percentage')
);

function get_royaltyAmount($id) {
	$listRoyaltyAmount = array (
			'1' => 'Fixed'		, 
			'2' => 'Percentage'
		);
	return $listRoyaltyAmount[$id];
}

//--------------- Complaint Status ------------------
$status = array (
	array('id'=>1, 'name'=>'Resolved'), array('id'=>2, 'name'=>'Pending'), array('id'=>3, 'name'=>'Rejected')
);

function get_complaint($id) {
$listcomplaint= array (
		'1' => '<span class="label label-success">Resolved</span>', 
		'2' => '<span class="label label-warning">Pending</span>', 
		'3' => '<span class="label label-danger">Rejected</span>');
return $listcomplaint[$id];
}

function get_complaint1($id) {
$listcomplaint= array (
		'1' => 'Resolved', 
		'2' => 'Pending', 
		'3' => 'Rejected');
return $listcomplaint[$id];
}
//--------------- Delivery Status ------------------
$status = array (
						array('id'=>1, 'name'=>'Pending'), array('id'=>2, 'name'=>'Onhold'), array('id'=>3, 'name'=>'Accepted'), array('id'=>4, 'name'=>'Dispatched'), array('id'=>5, 'name'=>'Delivered'), array('id'=>6, 'name'=>'Rejected')
				   );

function get_delivery($id) {
	$listdelivery= array (
							'1' => '<span class="label label-dark">Pending</span>'	, 
							'2' => '<span class="label label-warning">Onhold</span>'	, 
							'3' => '<span class="label label-primary">Accepted</span>'	, 
							'4' => '<span class="label label-info">Dispatched</span>'	, 
							'5' => '<span class="label label-success">Delivered</span>'	, 
							'6' => '<span class="label label-danger">Rejected</span>');
	return $listdelivery[$id];
}
//--------------- Guardian ---------------
$guardian = array (
	array('id'=>1, 'name'=>'Father'),
	array('id'=>2, 'name'=>'Mother'),
	array('id'=>3, 'name'=>'Brother'),
	array('id'=>4, 'name'=>'Sister'),
	array('id'=>5, 'name'=>'Uncle'),
	array('id'=>6, 'name'=>'Other')
   );
//--------------- Admins Rights ----------
$admtypes = array (
					array('id'=>1, 'name'=>'Super Admin')	,
					array('id'=>2, 'name'=>'Campus Head')	,
					array('id'=>3, 'name'=>'Administrator')	,
					array('id'=>4, 'name'=>'Accountant')	,
					array('id'=>5, 'name'=>'Designer')		,
					array('id'=>6, 'name'=>'Simple')
				   );

function get_admtypes($id) {
	$listadmrights = array (
							'1'	=> 'Super Admin'	,
							'2'	=> 'Campus Head'	,
							'3'	=> 'Administrator'	,
							'4'	=> 'Accountant'		,
							'5'	=> 'Designer'		,
							'6'	=> 'Simple'
							);
	return $listadmrights[$id];
}
//--------------- Status Yes No ----------
$statusyesno = array (
	array('id'=>1, 'name'=>'Yes'), array('id'=>2, 'name'=>'No')
);

function get_statusyesno($id) {

$liststatusyesno = array (
			'1' => '<span class="label label-success">Yes</span>', 
			'2' => '<span class="label label-danger">No</span>'
		);
return $liststatusyesno[$id];
}

//--------------- Hostel Type ----------
$hostelype = array (
						array('id'=>1, 'name'=>'Boys'), array('id'=>2, 'name'=>'Girls')
				   );

function get_hostelype($id) {
	
	$listhostelype = array (
								'1'	=> 'Boys',	'2'	=> 'Girls'
							 );
	return $listhostelype[$id];
}
//-------Rupees in Word-------------------------------
function convert_number_to_words($number) {

    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'Zero',
        1                   => 'One',
        2                   => 'Two',
        3                   => 'Three',
        4                   => 'Four',
        5                   => 'Five',
        6                   => 'Six',
        7                   => 'Seven',
        8                   => 'Eight',
        9                   => 'Nine',
        10                  => 'Ten',
        11                  => 'Eleven',
        12                  => 'Twelve',
        13                  => 'Thirteen',
        14                  => 'Fourteen',
        15                  => 'Fifteen',
        16                  => 'Sixteen',
        17                  => 'Seventeen',
        18                  => 'Eighteen',
        19                  => 'Nineteen',
        20                  => 'Twenty',
        30                  => 'Thirty',
        40                  => 'Fourty',
        50                  => 'Fifty',
        60                  => 'Sixty',
        70                  => 'Seventy',
        80                  => 'Eighty',
        90                  => 'Ninety',
        100                 => 'Hundred',
        1000                => 'Thousand',
        1000000             => 'Million',
        1000000000          => 'Billion',
        1000000000000       => 'Trillion',
        1000000000000000    => 'Quadrillion',
        1000000000000000000 => 'Quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}

function ordinal($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13))
        return $number. 'th';
    else
        return $number. $ends[$number % 10];
}
//--------------- Subject Type ----------
$subjecttype = array (
						array('id'=>1, 'name'=>'Optional'), array('id'=>2, 'name'=>'Mandatory')
				   );

function get_subjecttype($id) {
	
	$listsubjecttype= array (
							'1' => '<span class="label label-primary">Optional</span>', 
							'2' => '<span class="label label-warning">Mandatory</span>');
	return $listsubjecttype[$id];
}
//--------------- Employee Type ------------------
$emply_type = array (
						array('id'=>1, 'name'=>'Teaching'), array('id'=>2, 'name'=>'Non Teaching')
				   );

function get_emplytype($id) {
	$listemply= array (
							'1' => 'Teaching', 
							'2' => 'Non Teaching');
	return $listemply[$id];
}
//--------------- Inquiry Type ------------------
$inquirysrc = array (
						 array('id'=>1, 'name'=>'Online')
						,array('id'=>2, 'name'=>'Newspaper Ad')
						,array('id'=>3, 'name'=>'Social Media Ad')
						,array('id'=>4, 'name'=>'Brochures')
						,array('id'=>5, 'name'=>'Sign Board')
						,array('id'=>6, 'name'=>'Cable Ad')
						,array('id'=>7, 'name'=>'Road Banner')
						,array('id'=>8, 'name'=>'Friend')
						,array('id'=>9, 'name'=>'Family')
						);
function get_inquirysrc($id = "") {
	$lissrc= array (
						 '1' => 'Online'
						,'2' => 'Newspaper Ad'
						,'3' => 'Social Media Ad'
						,'4' => 'Brochures'
						,'5' => 'Sign Board'
						,'6' => 'Cable Ad'
						,'7' => 'Road Banner'
						,'8' => 'Friend'
						,'9' => 'Family'
				   );
	return ((!empty($id))? $lissrc[$id]: $lissrc);
}
//--------------- Transport USer Type ------------------
$type = array (
						array('id'=>1, 'name'=>'Student'), array('id'=>2, 'name'=>'Employee')
				   );

function get_usertype($id) {
	$listuser= array (
							'1' => 'Student', 
							'2' => 'Employee');
	return $listuser[$id];
}

//--------------- Attendce Keywords ----------
$attendtype = array (
					array('id'=>1, 'name'=>'Present'),
					array('id'=>2, 'name'=>'Absent'),
					array('id'=>3, 'name'=>'Leave'),
					array('id'=>4, 'name'=>'Late')
				   );

function get_attendtype($id) {
	$attendcetype = array (
							'1'	=> '<span class="label label-success">P</span>', 
							'2'	=> '<span class="label label-danger">A</span>', 
							'3'	=> '<span class="label label-primary">LV</span>', 
							'4'	=> '<span class="label label-warning">LT</span>'
							);
	return $attendcetype[$id];
}

function get_attendtype1($id) {
	$attendcetype1 = array (
								'1'	=> '<span class="label label-success">Present</span>', 
								'2'	=> '<span class="label label-danger">Absent</span>', 
								'3'	=> '<span class="label label-primary">Leave</span>', 
								'4'	=> '<span class="label label-warning">Late</span>'
						  );
	return $attendcetype1[$id];
}

//------------- Digital Resources ----------
function get_digitalresource($id) {
	$listdigitalresource = array (
							'1' => 'youtube'	, 
							'2' => 'website'	,
							'3' => 'ebook'		
						  );
	return $listdigitalresource[$id];
}

//------------- Exam Terms ---------------
$termrtypes = array (
					array('id'=>1, 'name'=>'First Term')	,
					array('id'=>2, 'name'=>'Second Term')	,
					array('id'=>3, 'name'=>'Third Term')
				   );

function get_term($id) {
	$listterm = array (
						'1' => 'First Term'		, 
						'2' => 'Second Term'	, 
						'3' => 'Third Term'			
						);
	return $listterm[$id];
}

//------------- Exam Assessments ---------------
function get_assessment($id) {
	$listassessment = array (
						'1' => 'Assessment Manual'	, 
						'2' => 'Assessment Policy'	, 
						'3' => 'Assessment Scheme'		
						);
	return $listassessment[$id];
}

//--------------- Months Keywords ----------
$monthtypes = array (
					array('id'=>1, 'name'=>'January'),
					array('id'=>2, 'name'=>'February'),
					array('id'=>3, 'name'=>'March'),
					array('id'=>4, 'name'=>'April'),
					array('id'=>5, 'name'=>'May'),
					array('id'=>6, 'name'=>'June'),
					array('id'=>7, 'name'=>'July'),
					array('id'=>8, 'name'=>'August'),
					array('id'=>9, 'name'=>'September'),
					array('id'=>10, 'name'=>'October'),
					array('id'=>11, 'name'=>'November'),
					array('id'=>12, 'name'=>'December')
				   );

$summermonth = array (
					array('id'=>3, 'name'=>'March'),
					array('id'=>4, 'name'=>'April'),
					array('id'=>5, 'name'=>'May')
					);

function get_monthtypes($id) {
	$month = array (
							'1'		=> 'January',
							'2'		=> 'February',
							'3'		=> 'March',
							'4'		=> 'April',
							'5'		=> 'May',
							'6'		=> 'June',
							'7'		=> 'July',
							'8'		=> 'August',
							'9'		=> 'September',
							'10'	=> 'October',
							'11'	=> 'November',
							'12'	=> 'December'
							);
	return $month[$id];
}
//--------------- Month Weeks ----------
$weeks = array (
	array('id'=>1, 'name'=>'Week 1'),
	array('id'=>2, 'name'=>'Week 2'),
	array('id'=>3, 'name'=>'Week 3'),
	array('id'=>4, 'name'=>'Week 4')
   );

function get_week($id) {
$week = array (
			'1'		=> 'Week 1',
			'2'		=> 'Week 2',
			'3'		=> 'Week 3',
			'4'		=> 'Week 4'
			);
return $week[$id];
}
//--------------- Days Keywords ----------
$daytypes = array (
					array('id'=>1, 'name'=>'Monday')	,
					array('id'=>2, 'name'=>'Tuesday')	,
					array('id'=>3, 'name'=>'Wednesday')	,
					array('id'=>4, 'name'=>'Thursday')	,
					array('id'=>5, 'name'=>'Friday')	,
					array('id'=>6, 'name'=>'Saturday')	,
					array('id'=>7, 'name'=>'Sunday')
				   );

function get_daytypes($id) {
	$day = array (
							'1'		=> 'Monday'		,
							'2'		=> 'Tuesday'	,
							'3'		=> 'Wednesday'	,
							'4'		=> 'Thursday'	,
							'5'		=> 'Friday'		,
							'6'		=> 'Saturday'	,
							'7'		=> 'Sunday'
							);
	return $day[$id];
} 

//--------------- Qualifications ----------
$qualtypes = array (
					array('id'=>1, 'name'=>'Bachelors')	,
					array('id'=>2, 'name'=>'Master')	,
					array('id'=>3, 'name'=>'Docrate')	,
					array('id'=>4, 'name'=>'Others')	
				   );

function get_qualtypes($id) {
	$qual = array (
							'1'		=> 'Bachelors'	,
							'2'		=> 'Master'		,
							'3'		=> 'Docrate'	,
							'4'		=> 'Others'
							);
	return $qual[$id];
} 
//--------------- Building ----------
$buildings = array (
					array('id'=>1, 'name'=>'Owned')				,
					array('id'=>2, 'name'=>'Rented')			,
					array('id'=>3, 'name'=>'To be arranged')	
					);
function get_buildings($id) {
	$build = array (
							'1'		=> 'Owned'				,
							'2'		=> 'Rented'				,
							'3'		=> 'To be arranged'		
							);
	return $build[$id];
} 
//--------------- Building Type ----------
$buildingtypes = array (
					array('id'=>1, 'name'=>'Resdential') ,
					array('id'=>2, 'name'=>'Commercial')		
				   );

function get_buildingtypes($id) {
	$building = array (
							'1'		=> 'Resdential'	,
							'2'		=> 'Commercial'		
							);
	return $building[$id];
} 
//--------------- Mediums ----------
$mediumtypes = array (
					array('id'=>1, 'name'=>'Resdential') ,
					array('id'=>2, 'name'=>'Commercial')		
				   );

function get_mediumtypes($id) {
	$medium = array (
							'1'		=> 'English'	,
							'2'		=> 'Urdu'		
							);
	return $medium[$id];
} 
//--------------- Investment Type ----------
$investypes = array (
					array('id'=>1, 'name'=>'Personal') 	  ,
					array('id'=>2, 'name'=>'Partnership') ,
					array('id'=>3, 'name'=>'Bank loan') 		
				   );

function get_investypes($id) {
	$investment = array (
							'1'		=> 'Personal'		,
							'2'		=> 'Partnership'	,
							'3'		=> 'Bank loan'		
							);
	return $investment[$id];
} 
//--------------- Calls ----------
$calltypes = array (
					array('id'=>1, 'name'=>'Incoming') ,
					array('id'=>2, 'name'=>'Out Going')		
				   );

function get_calltypes($id) {
	$calls = array (
							'1'		=> 'Incoming'	,
							'2'		=> 'Out Going'		
							);
	return $calls[$id];
} 

//--------------- Campus Building ----------
$buildingtype = array (
	array('id'=>1,  'name'=>'MES Owned in MQI Building')		,
	array('id'=>2,  'name'=>'MES Owned in Rented Building')		,
	array('id'=>3,  'name'=>'MES Owned in Free Building')		,
	array('id'=>3,  'name'=>'MES Franchised in MQI Building')	,
	array('id'=>3,  'name'=>'MES Franchised')					,
	array('id'=>3,  'name'=>'Affiliated with MES')		
);
function get_building($id) {
	$buildingtype = array (
							'1'		=> 'MES Owned in MQI Building'		,
							'2'		=> 'MES Owned in Rented Building'	,
							'3'		=> 'MES Owned in Free Building'		,
							'3'		=> 'MES Franchised in MQI Building' ,
							'3'		=> 'MES Franchised'					,
							'3'		=> 'Affiliated with MES'
							);
	return $buildingtype[$id];
}

//--------------- Campus For ----------
$campusfor = array (
	array('id'=>1, 'name'=>'Boys'), array('id'=>2, 'name'=>'Girls') , array('id'=>3, 'name'=>'Both')
);

function get_campusfor($id) {

$listcampusfor = array (
			'1'	=> 'Boys',	'2'	=> 'Girls',	'3'	=> 'Both'
		 );
return $listcampusfor[$id];
}

//--------------- Roles For ----------
$rolefor = array (
	array('id'=>1,  'name'=>'Head Office')	,
	array('id'=>2,  'name'=>'Campus')		,
	array('id'=>3,  'name'=>'Both')		
);
function get_rolefor($id) {
	$role = array (
							'1'		=> 'Head Office'	,
							'2'		=> 'Campus'			,
							'3'		=> 'Both'		
							);
	return $role[$id];
}

// Campus Type
function get_campus_type($id = '') {
	$campus_type	=	array (
								 '1' => 'Main Campus'
								,'2' => 'Sub Campus'
							);
	if(!empty($id)){
		$campus_type= array (
								 '1' => '<span class="label label-primary">Main Campus</span>'
								,'2' => '<span class="label label-warning">Sub Campus</span>'
							);
		return $campus_type[$id];
	}else{
		return $campus_type;
	}
}

//--------------- Roles ----------
$roletypes = array (
					array('id'=>1,  'name'=>'Admission')		,
					array('id'=>2,  'name'=>'Academic')			,
					array('id'=>3,  'name'=>'Attendance')		,
					array('id'=>4,  'name'=>'Exams')			,
					array('id'=>5,  'name'=>'HR')				,
					array('id'=>6,  'name'=>'Frenchies')		,
					array('id'=>7,  'name'=>'Complaints')		,
					array('id'=>8,  'name'=>'Accounts')			,
					array('id'=>9,  'name'=>'HR')				,
					array('id'=>10, 'name'=>'Frenchies')		,
					array('id'=>11, 'name'=>'Accounts')			,
					array('id'=>12, 'name'=>'Hostel')			,
					array('id'=>13, 'name'=>'Stationary')		,
					array('id'=>14, 'name'=>'Front Office')		,
					array('id'=>15, 'name'=>'Library')			,
					array('id'=>16, 'name'=>'Awards')			,
					array('id'=>17, 'name'=>'Events')			,
					array('id'=>18, 'name'=>'Admins')			,
					array('id'=>19, 'name'=>'Syllabus')			,
					array('id'=>20, 'name'=>'Hadith & Quotes')	,
					array('id'=>21, 'name'=>'Notifications')	,
				   );

function get_roletypes($id) {
	$role = array (
							'1'		=> 'Admission'			,
							'2'		=> 'Academic'			,
							'3'		=> 'Attendance'			,
							'4'		=> 'Exams'				,
							'5'		=> 'HR'					,
							'6'		=> 'Frenchies'			,
							'7'		=> 'Complaints' 		,
							'8'		=> 'Accounts'			,
							'9'		=> 'HR'					,
							'10'	=> 'Frenchies'			,
							'11'	=> 'Accounts'			,
							'12'	=> 'Hostel'				,
							'13'	=> 'Stationary'			,
							'14'	=> 'Front Office'		,
							'15'	=> 'Library'			,
							'16'	=> 'Awards'				,
							'17'	=> 'Events'				,
							'18'	=> 'Admins'				,
							'19'	=> 'Syllabus'			,
							'20'	=> 'Hadith & Quotes'	,
							'21'	=> 'Notifications'		,
							);
	return $role[$id];
}

//--------------- Transcation Type ----------
$transtype = array (
						array('id'=>1, 'name'=>'Credit'), array('id'=>2, 'name'=>'Debit')
				   );

function get_transtype($id) {
	
	$listtranstype = array (
								'1'	=> 'Credit',	'2'	=> 'Debit'
							 );
	return $listtranstype[$id];
}
//--------------- Transcation Method ------------------
$paymethod = array (
						array('id'=>1, 'name'=>'Cash')		, array('id'=>2, 'name'=>'Check')       , array('id'=>3, 'name'=>'Online')
				   );

function get_paymethod($id) {
	$listpaymethod= array (
							'1' => '<span class="label label-primary">Cash</span>', 
							'2' => '<span class="label label-warning">Check</span>', 
							'3' => '<span class="label label-warning">Online</span>');
	return $listpaymethod[$id];
}
$country = array('Bangladaish', 'China', 'India', 'Iran', 'Pakistan');
//--------------- Fee Duration ----------
$feeduration = array('Yearly', 'Half', 'Quatar', 'Monthly');
//--------------- Fee Type ----------
$feetype = array('Refundable', 'Nonrefundable');
//--------------- Gender ----------
$gender = array('Female', 'Male');
//--------------- Religion ----------
$religion = array('Islam', 'Christan', 'Hindu', 'Sikeh', 'Any other');
//--------------- Marital Status ----------
$marital = array('Married', 'Single');
//----------------Blood Groups------------------------------
$bloodgroup = array('A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-');
//---------------------------------------
/*function cleanvars($str) {
		$str = trim($str);
		$str = mysql_escape_string($str);

	return($str);
}
*/
function cleanvars($str){ 
	return is_array($str) ? array_map('cleanvars', $str) : str_replace("\\", "\\\\", htmlspecialchars( stripslashes($str), ENT_QUOTES)); 
}
//----------------------------------------
function to_seo_url($str){
   // if($str !== mb_convert_encoding( mb_convert_encoding($str, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32') )
      //  $str = mb_convert_encoding($str, 'UTF-8', mb_detect_encoding($str));
    $str = htmlentities($str, ENT_NOQUOTES, 'UTF-8');
    $str = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $str);
    $str = html_entity_decode($str, ENT_NOQUOTES, 'UTF-8');
    $str = preg_replace(array('`[^a-z0-9]`i','`[-]+`'), '-', $str);
    $str = trim($str, '-');
    return $str;
}
//--------------- Login Types ------------------
$logintypes = array (
	array('id'=>1, 'name'=>'headoffice')	,
	array('id'=>2, 'name'=>'campus')		,
	array('id'=>3, 'name'=>'teacher')		,
	array('id'=>4, 'name'=>'parent')		,
	array('id'=>5, 'name'=>'student')		,
	array('id'=>6, 'name'=>'AD / DE')
   );

function get_logintypes($id) {
$listlogintypes = array (

			'1'	=> 'headoffice'				,
			'2'	=> 'campus'					,
			'3'	=> 'teacher'				,
			'4'	=> 'parent'					,
			'5'	=> 'student'				,
			'6'	=> 'adde'
			);
	return $listlogintypes[$id];
}
//--------------- Log File Action----------
function get_logfile($id) {

	$listlogfile = array (
							'1' => 'Add'		, 
							'2' => 'Update'		, 
							'3' => 'Delete'		,
							'4' => 'Login'	
						  );
	return $listlogfile[$id];

}

//--------------- Arrary Search ------------------
function arrayKeyValueSearch($array, $key, $value)
{
    $results = array();
    if (is_array($array)) {
        if (isset($array[$key]) && $array[$key] == $value) {
            $results[] = $array;
        }
        foreach ($array as $subArray) {
            $results = array_merge($results, arrayKeyValueSearch($subArray, $key, $value));
        }
    }
    return $results;
}

//----------Get Current Url------------------------------
function curPageURL() {
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
 return $pageURL;
}
//----------Days Name------------------------------
$days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'); 

function get_Quotation($id = "") {
	$listQuotation= array (
							  '1' => 'Verse' 
							, '2' => 'Hadith'
							, '3' => 'Quotes'
						);
	return (!empty($id)? $listQuotation[$id]: $listQuotation);
}

function get_QuoteStatus($id) {
	$listQuoteStatus= array (
							'1' => '<span class="label label-info">Live</span>', 
							'0' => '<span class="label label-warning">Waiting</span>');
	return $listQuoteStatus[$id];
}

function sendRemark($remarks = "", $action = "") {
	if (!empty($remarks) && !empty($action)) {
		require_once("include/dbsetting/lms_vars_config.php");
		require_once("include/dbsetting/classdbconection.php");
		$dblms = new dblms();
		$values = array (
							 'id_user'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'filename'		=>	strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
							,'action'		=>	cleanvars($action)
							,'dated'		=>	date('Y-m-d G:i:s')
							,'ip'			=>	cleanvars(LMS_IP)
							,'remarks'		=>	cleanvars($remarks)
							,'id_campus'	=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
						);						
		if($action == '3'){
			$values['id_deleted']	= cleanvars($_SESSION['userlogininfo']['LOGINIDA']);
			$values['ip_deleted']	= cleanvars(LMS_IP);
			$values['date_deleted']	= date('Y-m-d G:i:s');
		}

		$sqlRemarks = $dblms->insert(LOGS, $values);
		if ($sqlRemarks) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}
function sessionMsg($title = "", $msg = "", $color = "") {
	if (!empty($title) && !empty($msg)&& !empty($color)){
		$_SESSION['msg']['title'] 	= ''.$title.'';
		$_SESSION['msg']['text'] 	= ''.$msg.'';
		$_SESSION['msg']['type'] 	= ''.$color.'';
		if (!empty($_SESSION['msg']['title']) && !empty($_SESSION['msg']['text'])&& !empty($_SESSION['msg']['info'])){
			return true;
		}else{
			return false;
		}	
	}else{
		return false;
	}
}
function get_PrintType($id = "") {
	$type = array (
					  '1'		=> 'Bank'
					, '2'		=> 'Account'		
					, '3'		=> 'Student'		
				);
	return ((!empty($id))? $type[$id]: $type);
}
function get_HostelStatus($id = "") {
	$type = array (
					  '1'		=> '<span class="label label-primary">Active</span>'
					, '2'		=> '<span class="label label-warning">Inactive</span>'		
					, '3'		=> '<span class="label label-info">Left</span>'
					, '4'		=> '<span class="label label-danger">Suspend</span>'
				);
	return ((!empty($id))? $type[$id]: $type);
}
function get_latefee_type($id = '') {
	$latefee_type = array (
						 '1' => 'Certain'
						,'2' => 'Slot'
					  );
			
	if(!empty($id)){
		return $latefee_type[$id];
	}else{
		return $latefee_type;
	}
}
function get_feecat_for($id = "") {
	$feecat_for = array (
					  '1'		=> '<span class="label label-success">All</span>'
					, '2'		=> '<span class="label label-info">Hostel</span>'
				);
	return ((!empty($id))? $feecat_for[$id]: $feecat_for);
}
function get_firstLetterCap($str) {
	return ucwords(strtolower($str));
}
function get_increasing_type($id = '') {
	$increasing_types	=	array	(
										 '1' => 'LUMPSUM'
										,'2' => 'Percentage'
									);
	
	if(!empty($id)){
		return $increasing_types[$id];
	}else{
		return $increasing_types;
	}
}
function moduleName($flag = true) {
	$fileName = strstr(basename($_SERVER['REQUEST_URI']), '.php', true);
	if (gettype($flag) == 'string') {		
		$flag = str_replace('_',' ',$flag);
		$flag = str_replace('-',' ',$flag);
		$flag = ucwords(strtolower($flag));
		return $flag;
	}
	if ($flag) {
		return strtolower($fileName);
	} else {
		$fileName = str_replace('_',' ',$fileName);
		$fileName = str_replace('-',' ',$fileName);
		return ucwords(strtolower($fileName));
	}
}
function get_formatCNIC($cnic) {
    $cnic = str_replace(['-', ' '], '', $cnic);
    if (strlen($cnic) !== 13 || !is_numeric($cnic)) {
        return "";
    }
    $formattedCnic = substr($cnic, 0, 5) . '-' . substr($cnic, 5, 7) . '-' . substr($cnic, 12, 1);
    return $formattedCnic;
}
function get_OrderBy($id = "") {
	$list 	= array (
				' ASC '		=> 'Ascending',
				' DESC '	=> 'Descending',
				' RAND() '	=> 'Random',
	);
	return ((!empty($id))? $list[$id]: $list);
}

function get_AttendenceType($id = '') {
	$list = array (
							'1'	=> 'Present', 
							'2'	=> 'Absent', 
							'3'	=> 'Leave', 
							'4'	=> 'Late',
	);
	return (!empty($id)?$list[$id]:$list);
}

function get_dataHashingOnlyExp($str = '', $flag = true) {
    if (!empty($str)) {
        $e_key     = "m^@c$&d#~l";
        $e_chiper  = "AES-128-CTR";
        $e_iv      = "4327890237234803";
        $e_option  = 0;

        if ($flag) {
            // Encrypt and then encode to base64
            $encrypted = openssl_encrypt($str, $e_chiper, $e_key, $e_option, $e_iv);
            return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($encrypted));
        } else {
            // Decode from base64 and then decrypt
            $decoded = base64_decode(str_replace(['-', '_'], ['+', '/'], $str));
            return openssl_decrypt($decoded, $e_chiper, $e_key, $e_option, $e_iv);
        }
    } else {
        return false;
    }
}
?>