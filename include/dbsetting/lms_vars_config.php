<?php
error_reporting(0);
ob_start();
ob_clean();
date_default_timezone_set("Asia/Karachi");
//	$dblms->lastestid();


define('LMS_HOSTNAME'			, 'localhost');
define('LMS_NAME'				, 'gptech_mgs');
define('LMS_USERNAME'			, 'root');
define('LMS_USERPASS'			, '');


// define('LMS_HOSTNAME'			, 'localhost');
// define('LMS_NAME'				, 'neotericschools_mgs2021');
// define('LMS_USERNAME'			, 'neotericschools_mgs');
// define('LMS_USERPASS'			, 'uw#7P({9hx7z');

// DB TABLES
define('A_CALENAR'					, 'sms_academiccalendar');
define('ACADEMIC_DETAIL'			, 'sms_academiccalendar_details');
define('ACADEMIC_PARTICULARS'		, 'sms_academiccalendar_particular');
define('ACCOUNT_HEADS'				, 'sms_account_heads');
define('ACCOUNTS_LOGS'				, 'sms_accounts_log');
define('ACCOUNT_TRANS'				, 'sms_account_transactions');
define('ADMINS'						, 'sms_admins');
define('ADMIN_ROLES'				, 'sms_admins_roles');
define('ADMISSIONS_INQUIRY'			, 'sms_admissions_inquiry');
define('ADMISSIONS_INQUIRYFOLLOWUP'	, 'sms_admissions_inquiryfollowup');
define('ANNOUNCEMENT'				, 'sms_announcements');
define('ASSIGNMENT'					, 'sms_assignment');
define('BEHAVIOURS'					, 'sms_behaviour'); 
define('BEHAVIOUR_ROLES'			, 'sms_behaviour_roles'); 
define('BANKS'						, 'sms_banks'); 
define('BRANDS'						, 'sms_brands'); 
define('CALLLOG'					, 'sms_phone_calllog');
define('CAMPUS'						, 'sms_campus');
define('CAMPUS_BIOGRAPHY'			, 'sms_campus_biography');
define('CAMPUS_GROUPS'				, 'sms_campusgroups');
define('CAMPUS_LEVELS'				, 'sms_campuslevels');
define('CAMPUS_PERFORMA'			, 'sms_campusproformas');
define('CAMPUS_PERFORMA_DET'		, 'sms_campusproformadetails');
// define('CAMPUS_ROYALTY'			, 'sms_campusroyalty');
// define('CAMPUS_ROYALTY_DET'		, 'sms_campusroyalty_detail');
define('CAMPUS_UTILITIES'			, 'sms_campus_utilities');
define('CLASSES'					, 'sms_classes');
define('CLASS_SECTIONS'				, 'sms_classsections');
define('CLASS_SUBJECTS'				, 'sms_classsubjects');
define('SUBJECT_BOOKS'				, 'sms_subjectbooks');
define('COMPLAINTS'					, 'sms_complaints');
define('COMPLAINT_TYPE'				, 'sms_complainttype');
define('COMPLAINT_SOURCE'			, 'sms_complaintsource');
define('DESIGNATIONS'				, 'sms_designations');
define('DEPARTMENTS'				, 'sms_depts');
define('DATESHEET'					, 'sms_exam_timetable');
define('DATESHEET_DETAIL'			, 'sms_exam_timetabledetail');
define('DIARY'						, 'sms_diary');
define('DIGITAL_RESOURCES'			, 'sms_digital_resource');
define('DISTRICTS'					, 'sms_districts');
define('EMPLOYEES'					, 'sms_employees');
define('EMPLOYEES_BANKACCOUNTS'		, 'sms_employee_bankaccounts');
define('EMPLOYEES_ATTENDCE'			, 'sms_employee_attendance');
define('EMPLOYEES_ATTENDCE_DETAIL'	, 'sms_employee_attendancedetail');
define('EVENTS'						, 'sms_events');
define('EXAMS'						, 'sms_exams');
define('EXAM_DELIVERY'				, 'sms_exam_delivery');
define('EXAM_CALENDER'				, 'sms_examcalendar');
define('EXAM_CALENDER_DETAIL'		, 'sms_examcalendar_details');
define('EXAM_DOWNLOADS'				, 'sms_exam_downloads');
define('EXAM_TERMS'					, 'sms_exam_terms');
define('EXAM_TYPES'					, 'sms_examtypes');
define('EXPENSES'					, 'sms_expenses');
define('EXPENSESCATEGORY'			, 'sms_expensescategory');
define('EXAM_MARKS'					, 'sms_student_markregister');
define('EXAM_MARKS_DETAILS'			, 'sms_student_markregisterdetail');
define('FACILITY_CATS'				, 'sms_facilitycategory');
define('FACILITY_QESTIONS'			, 'sms_facilityquestions');
define('CHALLAN_DESCRIPTION'		, 'sms_challan_description');
define('FEES'						, 'sms_fees');
define('FEE_PARTICULARS'			, 'sms_feeparticulars');
define('FEE_PARTICULARSPAID'		, 'sms_feeparticularspaid');
define('FEE_CATEGORY'				, 'sms_feecategory');
define('FEESETUP'					, 'sms_feesetup');
define('FEESETUPDETAIL'				, 'sms_feesetupdetail');
define('GROUPS'						, 'sms_groups');
define('GRADESYSTEM'				, 'sms_examgradingsystem');
define('GUARDIANS'					, 'sms_studentguardian');
define('HOSTELS'					, 'sms_hostels');
define('HOSTEL_ROOMS'				, 'sms_hostel_rooms');
define('HOSTEL_TYPES'				, 'sms_hostel_types');
define('HOSTELS_REGISTRATION'		, 'sms_hostel_transactions');
define('INVENTORY_CATEGORY'			, 'sms_inventorycategory');
define('INVENTORY_PURCHASE'			, 'sms_inventory_purchase');
define('INVENTORY_PUR_DETAIL'		, 'sms_inventory_purchasedetail');
define('INVENTORY_ITEMS'			, 'sms_inventoryitems');
define('INVENTORY_STORES'			, 'sms_inventorystores');
define('INVENTORY_SALE'				, 'sms_inventory_sale');
define('INVENTORY_SALE_DETAIL'		, 'sms_inventory_saledetail');
define('INVENTORY_SALE_PAYABLE'		, 'sms_inventory_salepayable');
define('INVENTORY_SUPPLIERS'		, 'sms_inventorysuppliers');
define('INVESTORS'					, 'sms_investor_info');
define('INVESTOR_EDUCATION'			, 'sms_investor_educations');
define('INVESTOR_EXPERIENCE'		, 'sms_investor_experience');
define('INVESTOR_FRANCHISE'			, 'sms_investor_franchise_loc');
define('INVESTOR_VICINITY'			, 'sms_investor_same_vicinity');
define('SESSIONS'					, 'sms_sessions');
define('LOGS'						, 'sms_logfile');
define('LMS_BOOKCATEGORY'			, 'sms_lms_bookcategory');
define('LMS_BOOKS'					, 'sms_lms_books');
define('LEAVE'						, 'sms_leaves');
define('LEAVE_CATEGORY'				, 'sms_leavecategory');
define('LOGIN_HISTORY'				, 'sms_login_history');
define('MESSAGES'					, 'sms_messages');
define('MONTHLY_ASSESSMENT'			, 'sms_monthly_assessment');
define('NOTIFICATIONS'				, 'sms_notifications');
define('ONLINE_CLASSES'				, 'sms_zoom_classes');
define('PROVINCES'					, 'sms_provinces');
define('ROLES'						, 'sms_roles');
define('ROYALTY_CHALLAN_DET'		, 'sms_royaltychallan_detail');
define('ROYALTY_PARTICULARS'		, 'sms_royaltyparticulars');
define('ROYALTY_SETTING'			, 'sms_royalty_setting');
define('ROYALTY_SETTING_DET'		, 'sms_royaltysetting_detail');
define('SALARY'						, 'sms_salaries');
define('SALARY_PART'				, 'sms_salary_particulars');
define('SCHEME_OF_STUDY'			, 'sms_schemeofstudy');
define('SCHOLARSHIP'				, 'sms_scholarship');
define('SCHOLARSHIP_CAT'			, 'sms_scholarshipcategory');
define('SCHOOL_LEVEL'				, 'sms_school_level');
define('SETTINGS'					, 'sms_settings');
define('STUDENT_AWARDS'				, 'sms_student_awards');
define('STD_PROMOTE_LOG'			, 'sms_student_promote');
define('SYLLABUS'					, 'sms_syllabus_breakdown');
define('LEARNING_RESOURCES'			, 'sms_learningresources');
define('SUMMER_WORK'				, 'sms_summer_work');
define('POSTAL_DISPATCH'			, 'sms_postaldispatch');
define('POSTAL_RECEIVED'			, 'sms_postalreceived');
define('RESOURCES'					, 'sms_resources');
define('TEHSIL_CITIES'				, 'sms_tehsilscities');
define('TEACHING_GUIDES'			, 'sms_teaching_guides');
define('TIMETABLE'					, 'sms_timetable');
define('TRAINING_VIDEOS'			, 'sms_training_videos');	
define('PERIODS'					, 'sms_timetable_periods');
define('CLASS_ROOMS'				, 'sms_timetable_classrooms');
define('TIMETABEL_DETAIL'			, 'sms_timetable_details');
define('ROUTES'						, 'sms_transport_routes');
define('STUDENTS'					, 'sms_students');
define('STUDENT_ATTENDANCE'			, 'sms_student_attendance');
define('STUDENT_ATTENDANCE_DETAIL'	, 'sms_student_attendancedetail');
define('TRANSPORT_TRANSACTION'		, 'sms_transport_transactions');
define('VEHICLES'					, 'sms_transport_vehicles');
define('VIDEO_LECTURE'				, 'sms_video_lectures');
define('VISITOR_PURPOSES'			, 'sms_visitorpurposes');
define('VISITOR'					, 'sms_visitors');
define('ZONES'						, 'sms_zones');
define('DAILY_QUOTATION'			, 'sms_daily_quotation');
define('INSPECTION_SCHEDULE'		, 'sms_inspection_schedule');
define('INSPECTION_SCHEDULE_DET'	, 'sms_inspection_schedule_detail');
define('EXAM_INSTRUCTIONS'			, 'sms_exam_instructions');
define('EXAM_ATTENDANCE'			, 'sms_exam_attendance');
define('EXAM_ATTENDANCE_DETAIL'		, 'sms_exam_attendancedetail');
define('EXAM_PAPER_CHECKER'			, 'sms_exam_paper_checker');


// API TEST
define('FEES_LIVE'					, 'sms_fees_live');
define('FEE_PARTICULARSPAID_LIVE'	, 'sms_feeparticularspaid_live');

// VARS
$ip	  	= (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '') ? $_SERVER['REMOTE_ADDR'] : '';
$do	  	= (isset($_REQUEST['do']) && $_REQUEST['do'] != '') ? $_REQUEST['do'] : '';
$view 	= (isset($_REQUEST['view']) && $_REQUEST['view'] != '') ? $_REQUEST['view'] : '';
$page	= (isset($_REQUEST['page']) && $_REQUEST['page'] != '') ? $_REQUEST['page'] : '1';
$Limit	= (isset($_REQUEST['Limit']) && $_REQUEST['Limit'] != '') ? $_REQUEST['Limit'] : '';

define('LMS_IP'				, $ip);
define('LMS_DO'				, $do);
define('LMS_EPOCH'			, date("U"));
define('LMS_VIEW'			, $view);
define('HOST_URL'			, 'https://msd.gptech.pk/login.php');
define("BASE_URL"			,"https://mgs.gptech.pk");
define('TITLE_HEADER'		, 'School Management System');
define("SITE_NAME"			, "School Management System");
define("SCHOOL_NAME"		, 'Minhaj Grammer School');
define("SCHOOL_SHORT"		, 'MGS');
define("LATEFEE"			, 0);
define("STD_PREFIX"			, 3);
define("EMP_PREFIX"			, 2);
define("SITE_ADDRESS"		, "");
define("COPY_RIGHTS"		, "GPTech | Green Professional Technologies");
define("COPY_RIGHTS_ORG"	, "&copy; ".date("Y")." - All Rights Reserved.");
define("COPY_RIGHTS_URL"	, "https://gptech.pk/");
?>