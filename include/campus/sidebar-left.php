<?php
echo'
<aside id="sidebar-left" class="sidebar-left" style="margin-bottom:50px;">
	<div class="sidebar-header">
		<div class="sidebar-title">
			Navigation
		</div>
		<div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
			<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
		</div>
	</div>
	<div class="nano">
		<div class="nano-content">
			<nav id="menu" class="nav-main" role="navigation">
				<ul class="nav nav-main">';
					if($_SESSION['userlogininfo']['CAMPUSTYPE'] == '1'){
						echo '
						<li class="nav-parent">
							<a><i class="fa fa-tachometer"></i><span>Dashboard</span></a>
							<ul class="nav nav-children">
								<li><a href="dashboard.php"><span><i class="fa fa-circle-o" aria-hidden="true"></i> Dashobard</span></a></li>';
								if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){								
									$subcampArray = array();
									$sqlSubCampus = $dblms->querylms("SELECT campus_id, campus_name
																		FROM ".CAMPUS." 
																		WHERE campus_id IN (".$_SESSION['userlogininfo']['SUBCAMPUSES'].")
																		AND campus_status	= '1'
																		AND is_deleted		= '0'
																		ORDER BY campus_id ASC");
									while($valSubCampus = mysqli_fetch_array($sqlSubCampus)) {
										array_push($subcampArray, cleanvars($valSubCampus['campus_id']));
										echo'<li><a href="dashboard.php?id='.$valSubCampus['campus_id'].'&name='.$valSubCampus['campus_name'].'"><span><i class="fa fa-circle-o" aria-hidden="true"></i> '.$valSubCampus['campus_name'].' Dashobard</span></a></li>';
									}
									$sub_campuses = implode(',',$subcampArray);
								}
								echo'<li><a href="dashboard.php?id='.$_SESSION['userlogininfo']['LOGINCAMPUS'].','.$sub_campuses.'&name=All Campus"><span><i class="fa fa-circle-o" aria-hidden="true"></i> All Campus Dashobard</span></a></li>';
								echo'
							</ul>
						</li>';
					}else{
						echo'
						<li>
							<a href="dashboard.php">
								<i class="fa fa-tachometer"></i>
								<span>Dashboard</span>
							</a>
						</li>';
					}

					// SHOW FROM MODEL TEST
					// echo'<li><a href="show_from_modal.php"><i class="fa fa-slideshare"></i><span>Show From Modal</span></a></li>';
			
					// ADMISSIONS
					$admissionCheck = array('49', '1');
					foreach($admissionCheck as $admission){
						if(in_array($admission, $_SESSION['userlogininfo']['PERMISSIONS'])){
							echo'
							<li class="nav-parent">
								<a><i class="fa fa-slideshare"></i><span>Admission</span></a>
								<ul class="nav nav-children">';
									if(in_array('49', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
										echo'<li><a href="admission_inquiry.php"><span><i class="fa fa-circle-o" aria-hidden="true"></i> Admission Inquiry</span></a></li>';
									}
									if(in_array('1', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
										echo'<li><a href="students.php?view=add"><span><i class="fa fa-circle-o" aria-hidden="true"></i> Make Admission</span></a></li>';
									}
									if(in_array('1', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
										echo'
										<li><a href="students.php"><span><i class="fa fa-circle-o" aria-hidden="true"></i> Student Details</span></a></li>
										<li><a href="admission_report.php"><span><i class="fa fa-circle-o" aria-hidden="true"></i> Report</span></a></li>';
									}
									echo'
								</ul>
							</li>';
							break;
						}
					}

					// PROMOTE STUDENT
					$promoteCheck = array('1');
					foreach($promoteCheck as $promote){
						if(in_array($promote, $_SESSION['userlogininfo']['PERMISSIONS'])){
							echo'
							<li class="nav-parent  ">
								<a><i class="fa fa-file-text-o"></i><span>Promote</span></a>
								<ul class="nav nav-children">';
									if(in_array('1', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
										echo'
										<li><a href="students_promote.php"><span><i class="fa fa-circle-o" aria-hidden="true"></i> Student Promote</span></a></li>
										<li><a href="student_promote_report.php"><span><i class="fa fa-circle-o" aria-hidden="true"></i> Report</span></a></li>';
									}
									echo'
								</ul>
							</li>';
							break;
						}
					}
			
					// ACADEMICS
					$academicsCheck = array('68', '47', '3', '6', '9', '5');
					foreach($academicsCheck as $academics){
						if(in_array($academics, $_SESSION['userlogininfo']['PERMISSIONS'])){
							echo'
							<li class="nav-parent">
								<a><i class="fa fa-university" aria-hidden="true"></i><span>Academic</span></a>
								<ul class="nav nav-children">';
									if(in_array('68', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
										echo'<li><a href="academic-calender.php"><i class="fa fa-tasks"></i><span>Academic Calendar</span></a></li>';
									}
									$classCheck = array('47', '3', '6');
									foreach($classCheck as $class){
										if(in_array($class, $_SESSION['userlogininfo']['PERMISSIONS'])){
											echo'
											<li class="nav-parent">
												<a><i class="fa fa-tasks" aria-hidden="true"></i><span>Class</span></a>
												<ul class="nav nav-children">';
													if(in_array('47', $_SESSION['userlogininfo']['PERMISSIONS'])){
														echo'<li class=" "><a href="class.php"><span>Control Classes</span></a></li>';
													}
													if(in_array('3', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
														echo'<li class=" "><a href="class-groups.php"><span>Control Class Groups</span></a></li>';
													}
													if(in_array('6', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
														echo'<li class=" "><a href="classsections.php"><span>Control Sections</span></a></li>';
													}
													echo'
												</ul>
											</li>';
											break;
										}
									}
									$timetableCheck = array('9', '7', '8');
									foreach($timetableCheck as $timetable){
										if(in_array($timetable, $_SESSION['userlogininfo']['PERMISSIONS'])){
											echo'
											<li class="nav-parent">
												<a><i class="fa fa-clock-o" aria-hidden="true"></i> Timetable</a>
												<ul class="nav nav-children">';
													if(in_array('9', $_SESSION['userlogininfo']['PERMISSIONS'])){
														echo'
														<li><a href="timetable.php"><span>Set Class Routine</span></a></li>
														<li><a href="timetable.php?view=class"><span>Daily Class Routine</span></a></li>
														<li><a href="timetable.php?view=teacher"><span>Daily Teacher Routine</span></a></li>
														<li><a href="timetable.php?view=campus"><span>Daily Campus Routine</span></a></li>';
													}
													if(in_array('7', $_SESSION['userlogininfo']['PERMISSIONS'])){
														echo'<li><a href="timetable_classrooms.php"><span>Class Rooms</span></a></li>';
													}
													if(in_array('8', $_SESSION['userlogininfo']['PERMISSIONS'])){
														echo'<li><a href="timetable_period.php"><span>Periods</span></a></li>';
													}
													echo'
												</ul>
											</li>';
											break;
										}
									}
									if(in_array('5', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
										echo'<li><a href="classsubjects.php"><i class="fa fa-book"></i><span>Subject</span></a></li>';
									}
									echo'
								</ul>
							</li>';
							break;
						}
					}					

					// RESOURCE PACK
					$resourceCheck = array('63', '62', '61', '60', '64', '66', '59', '58', '57');
					foreach($resourceCheck as $resource){
						if(in_array($resource, $_SESSION['userlogininfo']['PERMISSIONS'])){
							echo'<li><a href="resource_pack.php"><i class="fa fa-book"></i><span>Resource Pack</span></a></li>';
							break;
						}
					}

					// FINANCE
					$financeCheck = array('27', '29', '69', '70', '71', '72', '73', '74', '75', '76', '77');
					foreach($financeCheck as $finance){
						if(in_array($finance, $_SESSION['userlogininfo']['PERMISSIONS'])){
							echo'
							<li class="nav-parent  ">
								<a><i class="fa fa-cc-visa"></i><span>Finance Control</span></a>
								<ul class="nav nav-children">';							
									$feesCheck = array('69', '70', '71');
									foreach($feesCheck as $fees){
										if(in_array($fees, $_SESSION['userlogininfo']['PERMISSIONS'])){
											echo'
											<li class="nav-parent  ">
												<a><i class="fa fa-circle-o" aria-hidden="true"></i> Fees</a>
												<ul class="nav nav-children">';
													if(in_array('71', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
														echo'
														<li><a href="challan_description.php">Challan Description (Late Fee)</a></li>';
														if($_SESSION['userlogininfo']['CAMPUSTYPE'] == '1'){
															echo '<li><a href="fee_challans.php?view=bulk">Make Fee Challans</a></li>';
														}
														echo '
														<li><a href="fee_challans.php">Fee Challans List</a></li>
														<li><a href="feedefaulterlist.php">Fee Defaulters  List</a></li>';
													}
													if(in_array('70', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
														echo'<li><a href="feesetup.php">Fees Structure</a></li>';
													}
													if(in_array('69', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
														echo'<li><a href="fee-category.php"></i>Fee Category</a></li>';
													}
													echo'
												</ul>
											</li>';
											break;
										}
									}
									$balanceCheck = array('27', '29');
									foreach($balanceCheck as $balance){
										if(in_array($balance, $_SESSION['userlogininfo']['PERMISSIONS'])){
											echo'
											<li class="nav-parent  ">
												<a><i class="fa fa-circle-o" aria-hidden="true"></i> Balance Sheet</a>
												<ul class="nav nav-children">';
													if(in_array('27', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
														echo'<li><a href="earning.php">Income</a></li>';
													}
													if(in_array('29', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
														echo'<li><a href="costing.php">Expense</a></li>';
													}
													if(in_array('27', $_SESSION['userlogininfo']['PERMISSIONS']) || in_array('29', $_SESSION['userlogininfo']['PERMISSIONS'])){
														echo'
														<li><a href="incomeexpense_report.php">Income & Expense</a></li>
														<li><a href="comp_trialbalance.php">Trial Balance Sheet</a></li>
														<li><a href="comp_trialbalance_summary.php">Trial Balance Sheet Summary</a></li>
														<li><a href="campus_trialbalance_summary.php">Campus Wise Trial Balance Summary</a></li>';
													}
													echo'
												</ul>
											</li>';
											break;
										}
									}								
									$scholarshipCheck = array('72', '73');
									foreach($scholarshipCheck as $scholarship){
										if(in_array($scholarship, $_SESSION['userlogininfo']['PERMISSIONS'])){
											echo'
											<li class="nav-parent">
												<a><i class="fa fa-circle-o" aria-hidden="true"></i> Scholarship</a>
												<ul class="nav nav-children">';
													if(in_array('73', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
														echo'<li><a href="scholarship.php">Scholarships</a></li>';
													}
													if(in_array('72', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
														echo'<li><a href="scholarship_category.php">Scholarship Categories</a></li>';
													}
													echo'
												</ul>
											</li>';
											break;
										}
									}
									$concessionCheck = array('74', '75');
									foreach($concessionCheck as $concession){
										if(in_array($concession, $_SESSION['userlogininfo']['PERMISSIONS'])){
											echo'
											<li class="nav-parent  ">
												<a><i class="fa fa-circle-o" aria-hidden="true"></i> Concession</a>
												<ul class="nav nav-children">';
													if(in_array('75', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
														echo'<li><a href="feeconcession.php"> Fee Concessions</a></li>';
													}
													if(in_array('74', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
														echo'<li><a href="feeconcession_cat.php">Fee Concessions Categories</a></li>';
													}
													echo'
												</ul>
											</li>';
											break;
										}
									}
									$fineCheck = array('76', '77');
									foreach($fineCheck as $fine){
										if(in_array($fine, $_SESSION['userlogininfo']['PERMISSIONS'])){
											echo'
											<li class="nav-parent">
												<a><i class="fa fa-circle-o" aria-hidden="true"></i> Fine</a>
												<ul class="nav nav-children">';
													if(in_array('77', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
														echo'<li><a href="fine.php">Fines</a></li>';
													}
													if(in_array('76', $_SESSION['userlogininfo']['PERMISSIONS'])){
														echo'<li><a href="fine_category.php">Fine Categories</a></li>';
													}
													echo'
												</ul>
											</li>';
											break;
										}
									}
									if(in_array('71', $_SESSION['userlogininfo']['PERMISSIONS'])){
										echo'<li><a href="royaltyChallans.php"><i class="fa fa-circle-o" aria-hidden="true"></i> Royalty Challans</a></li>';
									}
									if(in_array('71', $_SESSION['userlogininfo']['PERMISSIONS'])){
										echo'
										<li class="nav-parent">
											<a><i class="fa fa-circle-o" aria-hidden="true"></i> Reports</a>
											<ul class="nav nav-children">
												<li><a href="feestatusreport.php">Fee Staus Report</a></li>
												<li><a href="fee_defaulter_report.php">Fee Defaulters Report</a></li>
												<li><a href="head_wise_collection.php">Head Collection Report</a></li>
												<li><a href="head_wise_collection_hostel.php">Head Collection Report Hostel</a></li>
												<li><a href="student_fee_detail_report.php">Student Fee Report</a></li>
											</ul>
										</li>';
									}
									echo'
								</ul>
							</li>';
							break;
						}
					}

					// ADMINISTRATION
					$administrationCheck = array('55', '56');
					foreach($administrationCheck as $administration){
						if(in_array($administration, $_SESSION['userlogininfo']['PERMISSIONS'])){
							echo'
							<li class="nav-parent">
								<a><i class="fa fa-bar-chart-o" aria-hidden="true"></i><span>Administration</span></a>
								<ul class="nav nav-children">';
									$attendanceCheck = array('55', '56');
									foreach($attendanceCheck as $attendance){
										if(in_array($attendance, $_SESSION['userlogininfo']['PERMISSIONS'])){
											echo'
											<li class="nav-parent">
												<a><i class="fa fa-line-chart"></i><span> Attendance</span></a>
												<ul class="nav nav-children">';
													if(in_array('56', $_SESSION['userlogininfo']['PERMISSIONS'])){
														echo'
														<!--
														<li><a href="attendance_students.php"><span><i class="fa fa-genderless" aria-hidden="true"></i>Mark Student Attendance</span></a></li>
														-->
														<li><a href="attendance_studentsreport.php"><span><i class="fa fa-genderless" aria-hidden="true"></i>Student Attendance</span></a></li>';
													}
													if(in_array('55', $_SESSION['userlogininfo']['PERMISSIONS'])){
														echo'									
														<li><a href="attendance_employees.php"><span><i class="fa fa-genderless" aria-hidden="true"></i>Mark Employees Attendance</span></a></li>
														<li><a href="attendance_employeesreport.php"><span><i class="fa fa-genderless" aria-hidden="true"></i>View Employees Attendance</span></a></li>';
													}
													echo'
												</ul>
											</li>';
											break;
										}
									}
									echo'
								</ul>
							</li>';
							break;
						}
					}

					// HR AND PAYROLL					
					if(in_array('16', $_SESSION['userlogininfo']['PERMISSIONS'])){
						echo'
						<li class="nav-parent">
							<a><i class="glyphicon glyphicon-retweet"></i><span>HRM / Payroll</span></a>
							<ul class="nav nav-children">';
								if(in_array('16', $_SESSION['userlogininfo']['PERMISSIONS'])){
									echo'
									<li class="nav-parent">
										<a><i class="fa fa-usd" aria-hidden="true"></i><span>Payroll</span></a>
										<ul class="nav nav-children">';
											if(in_array('16', $_SESSION['userlogininfo']['PERMISSIONS'])){
												echo'
												<li><a href="salarycontrol.php"><span>Salary Control</span></a></li>
												<li><a href="salary.php"><span>Employee Salary</span></a></li>
												<li><a href="salarycreated.php"><span>Generated Payslips</span></a></li>
												<li><a href="salary_report.php"><span>Salaries Report</span></a></li>';
											}
											echo'
										</ul>
									</li>';
								}
								echo'
							</ul>
						</li>';
					}
					
					// COMPAIN AND SUGGESTION
					if(in_array('24', $_SESSION['userlogininfo']['PERMISSIONS'])){
						echo'<li><a href="complaint_suggestion.php"><i class="fa fa-lightbulb-o"></i><span>Complaint/Suggestion</span></a></li>';
					}

					// EMPLOYEES
					$employeesCheck = array('10', '15', '16');
					foreach($employeesCheck as $employees){
						if(in_array($employees, $_SESSION['userlogininfo']['PERMISSIONS'])){
							echo'
							<li class="nav-parent">
								<a><i class="fa fa-users"></i><span>Employees </span></a>
								<ul class="nav nav-children">';
									if(in_array('16', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
										echo'
										<li><a href="employee.php?view=add"><span><i class="fa fa-circle-o" aria-hidden="true"></i> Add Employee </span></a></li>
										<li><a href="employee.php"><span><i class="fa fa-circle-o" aria-hidden="true"></i> Employees List</span></a></li>';
									}
									if(in_array('15', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
										echo'<li><a href="designation.php"><span><i class="fa fa-circle-o" aria-hidden="true"></i> Designation List</span></a></li>';
									}
									if(in_array('10', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
										echo'<li><a href="department.php"><span><i class="fa fa-circle-o" aria-hidden="true"></i> Department List</span></a></li>';
									}
									echo'
								</ul>
							</li>';
							break;
						}
					}

					// EXAM
					$examCheck = array('12', '79', '80', '81', '14');
					foreach($examCheck as $exam){
						if(in_array($exam, $_SESSION['userlogininfo']['PERMISSIONS'])){
							echo'
							<li class="nav-parent  ">
								<a><i class="fa fa-graduation-cap" aria-hidden="true"></i><span>Exam</span></a>
								<ul class="nav nav-children">';
									$examCalTypeCheck = array('11', '12');
									foreach($examCalTypeCheck as $examCalType){
										if(in_array($examCalType, $_SESSION['userlogininfo']['PERMISSIONS'])){
											echo'
											<li class="nav-parent">
												<a><i class="fa fa-calendar" aria-hidden="true"></i><span>Exam Calender</span></a>
												<ul class="nav nav-children">';
													if(in_array('11', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
														echo'<li><a href="exam_types.php"><i class="fa fa-genderless" aria-hidden="true"></i><span>Exam Types</span></a></li>';
													}
													if(in_array('12', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
														echo'<li><a href="exam_calender.php"><i class="fa fa-genderless" aria-hidden="true"></i><span>Exam Calender</span></a></li>';
													}
													echo'
												</ul>
											</li>';
											break;
										}
									}
									$downloadsCheck = array('79', '80', '81');
									foreach($downloadsCheck as $downloads){
										if(in_array($downloads, $_SESSION['userlogininfo']['PERMISSIONS'])){
											echo'
											<li class="nav-parent">
												<a><i class="fa fa-download" aria-hidden="true"></i><span>Downloads</span></a>
												<ul class="nav nav-children">';
													if(in_array('81', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
														echo'<li><a href="exam_scheme.php"><span><i class="fa fa-genderless" aria-hidden="true"></i> Exam Scheme</span></a></li>';
													}
													if(in_array('80', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
														echo'<li><a href="exam_policy.php"><span><i class="fa fa-genderless" aria-hidden="true"></i> Exam policy</span></a></li>';
													}
													if(in_array('79', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
														echo'<li><a href="exam_manual.php"><span><i class="fa fa-genderless" aria-hidden="true"></i> Exam Manual</span></a></li>';
													}
													echo'
												</ul>
											</li>';
											break;
										}
									}
									if(in_array('12', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
										echo'
										<li class="nav-parent">
											<a><i class="fa fa-clock-o" aria-hidden="true"></i><span>Datesheet</span></a>
											<ul class="nav nav-children">
												<li class=" "><a href="exam_datesheet.php?view=instructions"><i class="fa fa-paste" aria-hidden="true"></i><span>Exam Instructions</span></a></li>
												<li><a href="exam_datesheet.php"><span><i class="fa fa-genderless" aria-hidden="true"></i> List</span></a></li>
												<li><a href="exam_datesheet.php?view=routine"><span><i class="fa fa-genderless" aria-hidden="true"></i> Print Date Sheet</span></a></li>
											</ul>
										</li>';
									}
									if(in_array('12', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
										echo'<li><a href="exam_rollnoslips.php"><span><i class="fa fa-genderless" aria-hidden="true"></i> Roll No Slips</span></a></li>';
									}
									if(in_array('12', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
										echo'<li><a href="exam_attendance.php"><span><i class="fa fa-calendar-o" aria-hidden="true"></i> Exam Attendance</span></a></li>';
									}
									if(in_array('12', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
										echo'<li><a href="exam_attendancesheet.php"><span><i class="fa fa-genderless" aria-hidden="true"></i> Exam Attendance Sheet</span></a></li>';
									}
									if(in_array('12', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
										echo'<li><a href="exam_paper_checker.php"><span><i class="fa fa-genderless" aria-hidden="true"></i> Exam Paper Checker</span></a></li>';
									}
									if(in_array('12', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
										echo'<li><a href="exam_paper_checker.php?view=report"><span><i class="fa fa-genderless" aria-hidden="true"></i> Paper Checker Report</span></a></li>';
									}
									if(in_array('12', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
										echo'<li><a href="exam_marks.php"><span><i class="fa fa-genderless" aria-hidden="true"></i> Exam Marks</span></a></li>';
									}
									if(in_array('12', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
										echo'<li><a href="exam_award_list.php"><span><i class="fa fa-genderless" aria-hidden="true"></i> Exam Award List</span></a></li>';
									}									
									if(in_array('12', $_SESSION['userlogininfo']['PERMISSIONS'])){
										echo'
										<li class="nav-parent">
											<a><i class="fa fa-line-chart" aria-hidden="true"></i><span>Exam Result</span></a>
											<ul class="nav nav-children">
												<li><a href="exam_result.php?view=student"><span><i class="fa fa-genderless" aria-hidden="true"></i> Student Result</span></a></li>
												<li><a href="exam_result.php?view=student_comprehensive"><span><i class="fa fa-genderless" aria-hidden="true"></i> Student Comprehensive Result</span></a></li>
												<li><a href="exam_result.php?view=class"><span><i class="fa fa-genderless" aria-hidden="true"></i> Class Result</span></a></li>
												<li><a href="exam_result.php?view=class_comprehensive"><span><i class="fa fa-genderless" aria-hidden="true"></i> Class Comprehensive Result</span></a></li>
											</ul>
										</li>';
									}
									if(in_array('14', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
										echo'<li><a href="exam_grades.php"><span><i class="fa fa-line-chart" aria-hidden="true"></i> Grades Range</span></a></li>';
									}
									echo'
								</ul>
							</li>';
							break;
						}
					}

					// FRONT OFFICE
					$frontCheck = array('43', '44');
					foreach($frontCheck as $front){
						if(in_array($front, $_SESSION['userlogininfo']['PERMISSIONS'])){
							echo'
							<li class="nav-parent">
								<a><i class="fa fa-building-o"></i><span>Front Office</span></a>
								<ul class="nav nav-children">';
									$visitorCheck = array('43', '44');
									foreach($visitorCheck as $visitor){
										if(in_array($visitor, $_SESSION['userlogininfo']['PERMISSIONS'])){
											echo'
											<li class="nav-parent">
												<a><i class="fa fa-tasks" aria-hidden="true"></i><span>Visitors</span></a>
												<ul class="nav nav-children">';
													if(in_array('43', $_SESSION['userlogininfo']['PERMISSIONS'])){
														echo'<li><a href="visitors.php"><span>Control Visitors</span></a></li>';
													}
													if(in_array('44', $_SESSION['userlogininfo']['PERMISSIONS'])){
														echo'<li><a href="visitor_purposes.php"><span> Purposes</span></a></li>';
													}
													echo'
												</ul>
											</li>';
											break;
										}
									}
									echo'
									<li><a href="#"><span><i class="fa fa-phone" aria-hidden="true"></i> Calls</span></a></li>
									<li><a href="#"><span><i class="fa fa-envelope-o" aria-hidden="true"></i> Messages</span></a></li>
								</ul>
							</li>';
							break;
						}
					}

					// STATIONARY
					$stationaryCheck = array('34', '52', '54');
					foreach($stationaryCheck as $stationary){
						if(in_array($stationary, $_SESSION['userlogininfo']['PERMISSIONS'])){
							echo'
							<li class="nav-parent">
								<a><i class="fa fa-paperclip"></i><span>Statianory</span></a>
								<ul class="nav nav-children">';
									if(in_array('34', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
										echo'<li><a href="stationary_item.php"><span><i class="fa fa-circle-o"></i> Statianory Items</span></a></li>';
									}
									if(in_array('54', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
										echo'<li><a href="stationary_sale.php"><span><i class="fa fa-circle-o"></i> Statianory Sales</span></a></li>';
									}
									if(in_array('52', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
										echo'<li><a href="stationary_purchase.php"><span><i class="fa fa-circle-o"></i> Statianory Purchase</span></a></li>';
									}
									echo'
									<li><a href="stationary_stock.php"><span><i class="fa fa-circle-o"></i> Statianory Stock</span></a></li>
								</ul>
							</li>';
							break;
						}
					}

					// EVENTS
					if(in_array('42', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
						echo'<li><a href="event.php"><i class="fa fa-file-text-o"></i><span>Events</span></a></li>';
					}
					// EVENTS
					if(in_array('42', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
						echo'<li><a href="campus_announcements.php"><i class="fa fa-file-text-o"></i><span>Announcements</span></a></li>';
					}

					// LIBRARY
					$libraryCheck = array('39', '40');
					foreach($libraryCheck as $library){
						if(in_array($library, $_SESSION['userlogininfo']['PERMISSIONS'])){
							echo'
							<li class="nav-parent">
								<a><i class="fa fa-fax"></i><span>Library</span></a>
								<ul class="nav nav-children">';
									if(in_array('40', $_SESSION['userlogininfo']['PERMISSIONS'])){
										echo'<li><a href="lms_books.php"><span><i class="fa fa-circle-o"></i> Books Stock</span></a></li>';
									}
									if(in_array('39', $_SESSION['userlogininfo']['PERMISSIONS'])){
										echo'<li><a href="lms_bookcategory.php"><span><i class="fa fa-circle-o"></i> Books Category</span></a></li>';
									}
									echo'
									<li><a href="#"><span><i class="fa fa-circle-o"></i> Books Maintain</span></a></li>					
								</ul>
							</li>';
							break;
						}	
					}
					
					// HOSTEL
					$hostelCheck = array('31', '32','87');
					foreach($hostelCheck as $hostel){
						if(in_array($hostel, $_SESSION['userlogininfo']['PERMISSIONS'])){
							echo'
							<li class="nav-parent">
								<a><i class="fa fa-sitemap"></i><span>Hostel</span></a>
								<ul class="nav nav-children">';
									if(in_array('87', $_SESSION['userlogininfo']['PERMISSIONS'])){
										echo'<li><a href="hostel_students.php"><span><i class="fa fa-genderless" aria-hidden="true"></i> Hostel Users</span></a></li>';
									}
									if(in_array('32', $_SESSION['userlogininfo']['PERMISSIONS'])){
										echo'<li><a href="hostelrooms.php"><span><i class="fa fa-genderless" aria-hidden="true"></i> Room Control</span></a></li>';
									}
									if(in_array('31', $_SESSION['userlogininfo']['PERMISSIONS'])){
										echo'<li><a href="hostels.php"><span><i class="fa fa-genderless" aria-hidden="true"></i> Hostel Control</span></a></li>';
									}									
									// if(in_array('30', $_SESSION['userlogininfo']['PERMISSIONS'])){
									// 	echo'<li><a href="hostels-type.php"><span><i class="fa fa-genderless" aria-hidden="true"></i> Hostel Type</span></a></li>';
									// }
									echo'
								</ul>
							</li>';
							break;
						}
					}

					// NOTIFICATION
					if(in_array('88', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
						echo'<li><a href="notifications.php"><i class="fa fa-bell"></i><span>Notifications</span></a></li>';
					}

					// TRAINING VIDEOS
					if(in_array('65', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
						echo'<li><a href="training_videos.php"><i class="fa fa-video-camera"></i><span>Training Videos</span></a></li>';
					}

					// USER LOGIN
					$loginCheck = array('1', '16');
					foreach($loginCheck as $login){
						if(in_array($login, $_SESSION['userlogininfo']['PERMISSIONS'])){
							echo'
							<li class="nav-parent">
								<a><i class="fa fa-user"></i><span>User Login</span></a>
								<ul class="nav nav-children">';
									if(in_array('16', $_SESSION['userlogininfo']['PERMISSIONS'])){
										echo'<li><a href="teacherlogin.php"><span><i class="fa fa-circle-o" aria-hidden="true"></i> Teacher Login</span></a></li>';
									}
									if(in_array('1', $_SESSION['userlogininfo']['PERMISSIONS'])){
										echo'<li><a href="parentlogin.php"><span><i class="fa fa-circle-o" aria-hidden="true"></i> Parent Login</span></a></li>';
									}
									echo'
								</ul>
							</li>';
							break;
						}
					}

					// ADMINS
					if($_SESSION['userlogininfo']['CAMPUSTYPE'] == '1' && $_SESSION['userlogininfo']['LOGINTYPE'] != '6'){
						echo'<li><a href="admins.php"><i class="fa fa-user"></i><span>Admins</span></a></li>';
					}

					// SETTINGS
					$settingsCheck = array('26', '28', '51');
					foreach($settingsCheck as $settings){
						if(in_array($settings, $_SESSION['userlogininfo']['PERMISSIONS'])){
							echo'
							<li class="nav-parent">
								<a><i class="fa fa-cogs"></i><span>Settings</span></a>
								<ul class="nav nav-children">';
									if(in_array('26', $_SESSION['userlogininfo']['PERMISSIONS'])){
										echo'<li><a href="earninghead.php"><span><i class="fa fa-circle-o" aria-hidden="true"></i> Income Head</span></a></li>';
									}
									if(in_array('28', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
										echo'<li><a href="costinghead.php"><span><i class="fa fa-circle-o" aria-hidden="true"></i> Expense Head</span></a></li>';
									}
									if(in_array('51', $_SESSION['userlogininfo']['PERMISSIONS'])){ 
										echo'<li><a href="settings.php"><span><i class="fa fa-cogs" aria-hidden="true"></i> Settings</span></a></li>';
									}
									echo'
								</ul>
							</li>';break;
						}
					}

					// PROFILE
					echo'
					<li class="nav-parent">
						<a><i class="fa fa-lock"></i><span>Profile</span></a>
						<ul class="nav nav-children">
							<li><a href="profile.php"><span><i class="fa fa-circle-o" aria-hidden="true"></i> My Profile</span></a></li>';
							if($_SESSION['userlogininfo']['LOGINTYPE']  == 1){
								echo'<li><a href="profile.php?view=campus"><span><i class="fa fa-circle-o" aria-hidden="true"></i> Campus Profile</span></a></li>';
							}
							echo'
						</ul>
					</li>
				</ul>
			</nav>
		</div>
	</div>
</aside>
<script>
if (typeof localStorage !== "undefined") {
	if (localStorage.getItem("sidebar-left-position") !== null) {
		var initialPosition = localStorage.getItem("sidebar-left-position"),
		sidebarLeft = document.querySelector("#sidebar-left .nano-content");
		sidebarLeft.scrollTop = initialPosition;
	}
}
</script>';