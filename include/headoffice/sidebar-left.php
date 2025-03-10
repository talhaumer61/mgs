<?php
echo'
<aside id="sidebar-left" class="sidebar-left" style="padding-bottom:50px;">
	<div class="sidebar-header">
		<div class="sidebar-title">Navigation</div>
		<div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
			<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
		</div>
	</div>
	<div class="nano">
		<div class="nano-content">
			<nav id="menu" class="nav-main" role="navigation">
				<ul class="nav nav-main">
					<!-- DASHBOARD -->
					<li class=" ">
						<a href="dashboard.php">
							<i class="fa fa-tachometer"></i>
							<span>Dashboard</span>
						</a>
					</li>';

					// STUDENT
					if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'view' => '1'))){ 
						echo'
						<li class="">
							<a href="students.php"><i class="fa fa-slideshare"></i><span>Students</span></a>
						</li>';
					}
					
					// echo'
					// <li class=" ">
					// 	<a href="#">
					// 		 <i class="fa fa-random"></i>
					// 		<span>Student Transfer</span>
					// 	</a>
					// </li>';

					// ACADEMIC
					if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '5', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '68', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '67', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '47', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '3', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '6', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '9', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '7', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '8', 'view' => '1'))){
						echo'
						<li class="nav-parent  ">
							<a>
								<i class="fa fa-university" aria-hidden="true"></i>
								<span>Academic</span>
							</a>

							<ul class="nav nav-children">';

								// ACADEMIC CALENDER
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '68', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '67', 'view' => '1'))){
									echo'
									<li class="nav-parent  ">
										<a>
											<i class="fa fa-calendar" aria-hidden="true"></i>
											<span>Academic Calender</span>
										</a>
										<ul class="nav nav-children">';
											if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '68', 'view' => '1'))){ 
												echo'<li class=" "><a href="academic-calender.php"><span>Academic Calender</span></a></li>';
											}
											if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '67', 'view' => '1'))){ 
												echo'<li class=" "><a href="academiccalender_particulars.php"><span>Academic Calender Particular</span></a></li>';
											}
											echo'
										</ul>
									</li>';
								}
								// CLASS
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '47', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '3', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '6', 'view' => '1'))){ 
									echo'
									<li class="nav-parent  ">
										<a>
											<i class="fa fa-group" aria-hidden="true"></i>
											<span>Class</span>
										</a>
										<ul class="nav nav-children">';
											if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '47', 'view' => '1'))){ 
												echo'<li class=" "><a href="class.php"><span>Control Classes</span></a></li>';
											}
											if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '3', 'view' => '1'))){ 
												echo'<li class=" "><a href="class-groups.php"><span>Control Class Groups</span></a></li>';
											}
											if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '6', 'view' => '1'))){ 
												echo'<li class=" "><a href="classsections.php"><span>Control Sections</span></a></li>';
											}
											echo'
										</ul>
									</li>';
								}
								// TIMETABLE
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '9', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '7', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '8', 'view' => '1'))){ 
									echo'
									<li class="nav-parent  ">
										<a><i class="fa fa-clock-o" aria-hidden="true"></i> Timetable</a>
										<ul class="nav nav-children">';
											if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '9', 'view' => '1'))){ 
												echo'
												<li class="">
													<a href="timetable.php">
														<span>Daily Class Routine</span>
													</a>
												</li>';
											}
											if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '7', 'view' => '1'))){ 
												echo'
												<li class="">
													<a href="timetable_classrooms.php">
														<span>Class Rooms</span>
													</a>
												</li>';
											}
											if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '8', 'view' => '1'))){ 
												echo'						
												<li class="">
													<a href="timetable_period.php">
														<span>Periods</span>
													</a>
												</li>';
											}
											echo'
										</ul>
									</li>';
								}
								//  <!-- SUBJECT -->
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '5', 'view' => '1'))){ 
									echo'
									<li class=" ">
										<a href="classsubjects.php">
											<i class="fa fa-book"></i>
											<span>Subject</span>
										</a>
									</li>';
								}
								echo'
							</ul>
						</li>';
					}

					// RESOURCE PACK
					if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '63', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '62', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '61', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '60', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '64', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '66', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '59', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '58', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '57', 'view' => '1'))){
						echo'
						<li class="">
							<a href="resource_pack.php"><i class="fa fa-book"></i><span>Resource Pack</span></a>
						</li>';
					}

					// if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('type	' => '2'))){ 
					// 	echo'
					// 	<li class="nav-parent  ">
					// 		<a>
					// 			<i class="fa fa-book" aria-hidden="true"></i>
					// 			<span>Resource Pack</span>
					// 		</a>

					// 		<ul class="nav nav-children">';
							
					// 			// <!-- SYLLABUS -->
					// 			if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '57', 'view' => '1'))){ 
					// 				echo'
					// 				<li class=" ">
					// 					<a href="syllabus_breakdown.php">
					// 						<i class="fa fa-tasks"></i>
					// 						<span>Syllabus Break-Down</span>
					// 					</a>
					// 				</li>';
					// 			}

					// 			// <!-- LEARNING RESOURCES -->
					// 			if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '58', 'view' => '1'))){ 
					// 				echo'
					// 				<li class=" ">
					// 					<a href="learning_resources.php">
					// 						<i class="fa fa-tasks"></i>
					// 						<span>Students Learning Resources</span>
					// 					</a>
					// 				</li>';
					// 			}
								
					// 			// <!-- DLP -->
					// 			if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '59', 'view' => '1'))){ 
					// 				echo'
					// 				<li class=" ">
					// 					<a href="syllabus_dlp.php">
					// 						<i class="fa fa-tasks"></i>
					// 						<span>Syllabus DLP</span>
					// 					</a>
					// 				</li>';
					// 			}

					// 			// <!-- SCHEME OF STUDY -->
					// 			if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '66', 'view' => '1'))){ 
					// 				echo'
					// 				<li class=" ">
					// 					<a href="scheme_of_study.php">
					// 						<i class="fa fa-tasks"></i>
					// 						<span>Scheme of Study</span>
					// 					</a>
					// 				</li>';
					// 			}
								
					// 			// <!-- TEACHING GUIDES -->
					// 			if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '64', 'view' => '1'))){ 
					// 				echo'
					// 				<li class=" ">
					// 					<a href="teaching_guide.php">
					// 						<i class="fa fa-tasks"></i>
					// 						<span>Teaching Guides</span>
					// 					</a>
					// 				</li>';
					// 			}

					// 			// <!-- WORK SHEET -->
					// 			if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '60', 'view' => '1'))){ 
					// 				echo'
					// 				<li class=" ">
					// 					<a href="syllabus_worksheet.php">
					// 						<i class="fa fa-file-o"></i>
					// 						<span>Syllabus Work Sheet</span>
					// 					</a>
					// 				</li>';
					// 			}

					// 			// <!-- Monthly Assessment -->
					// 			if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '61', 'view' => '1'))){ 
					// 			echo'
					// 			<li class=" ">
					// 				<a href="monthly_assessment.php">
					// 					<i class="fa fa-file-o"></i>
					// 					<span>Monthly Assessment</span>
					// 				</a>
					// 			</li>';
					// 			}
								
					// 			// <!-- SUMMER WORK -->
					// 			if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '62', 'view' => '1'))){ 
					// 				echo'
					// 				<li class=" ">
					// 					<a href="summer-work.php">
					// 						<i class="fa fa-file-o"></i>
					// 						<span>Vacational Engagement Tasks</span>
					// 					</a>
					// 				</li>';
					// 			}

					// 			// <!-- VIDEO LECTURES -->
					// 			if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '63', 'view' => '1'))){ 
					// 				echo'
					// 				<li class=" ">
					// 					<a href="video-lecture.php">
					// 						<i class="fa fa-video-camera"></i>
					// 						<span>Video Lectures</span>
					// 					</a>
					// 				</li>';
					// 			}
					// 			echo'
					// 		</ul>
					// 	</li>
					// 	<!-- RESOURCE PACK END-->';
					// }			

					// FINANCE CONTROL
					if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('type' => '8'))){
						echo'
						<li class="nav-parent  ">
							<a>
								<i class="fa fa-cc-visa"></i>
								<span>Finance Control</span>
							</a>
							<ul class="nav nav-children">';
								// FEES
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '69', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '70', 'view' => '1'))){ 
									echo'
									<li class="nav-parent  ">
										<a><i class="fa fa-genderless" aria-hidden="true"></i> Fees</a>
										<ul class="nav nav-children">';
											if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '70', 'view' => '1'))){ 
												echo'<li class=" "><a href="feesetup.php">Fees Structure</a></li>';
											}
											if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '69', 'view' => '1'))){ 
												echo'<li class=" "><a href="fee-category.php"></i>Fee Category</a></li>';
											}
											echo'
										</ul>
									</li>';
								}
								// SCHOLARSHIP
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '73', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '72', 'view' => '1'))){ 
									echo'
									<li class="nav-parent  ">
										<a><i class="fa fa-genderless" aria-hidden="true"></i> Scholarship</a>
										<ul class="nav nav-children">';
											if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '72', 'view' => '1'))){ 
												echo'<li><a href="scholarship.php">Scholarships</a></li>';
											}
											if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '73', 'view' => '1'))){ 
												echo'<li><a href="scholarship_category.php">Scholarship Categories</a></li>';
											}
											echo'
										</ul>
									</li>';
								}
								// FINE
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '76', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '77', 'view' => '1'))){ 
									echo'
									<li class="nav-parent  ">
										<a><i class="fa fa-genderless" aria-hidden="true"></i> Fine</a>
										<ul class="nav nav-children">';
											if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '77', 'view' => '1'))){ 
												echo'<li><a href="fine.php">Fines</a></li>';
											}
											if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '76', 'view' => '1'))){ 
												echo'<li><a href="fine_category.php">Fine Categories</a></li>';
											}
											echo'
										</ul>
									</li>';
								}
								// ROYALTY
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '69', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'view' => '1'))){ 
									echo'
									<li class="nav-parent  ">
										<a><i class="fa fa-genderless" aria-hidden="true"></i> Royalty</a>
										<ul class="nav nav-children">';
											if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'view' => '1'))){ 
												echo'<li class=" "><a href="royaltyChallans.php">Royalty Challans</a></li>';
											}
											if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '69', 'view' => '1'))){ 
												echo'<li class=" "><a href="royaltyParticulars.php">Royalty Paticulars</a></li>';
											}
											echo'
										</ul>
									</li>';
								}
								echo'
								<!--
								<li class="nav-parent  ">
									<a><i class="fa fa-genderless" aria-hidden="true"></i> Balance Sheet</a>
									<ul class="nav nav-children">
										<li class=" "><a href="#">Costing Sheet</a></li>
										<li class=" "><a href="#">Costing Category</a></li>
										<li class=" "><a href="#">Earning Sheet</a></li>
										<li class=" "><a href="#">Earning Category</a></li>
									</ul>
								</li>
								-->
							</ul>
						</li>';
					}
					// EMPLOYEE
					if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '10', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '15', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '16', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '16', 'add' => '1'))){ 
						echo'
						<li class="nav-parent  ">
							<a><i class="fa fa-users"></i><span>Employees </span></a>
							<ul class="nav nav-children">';
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '16', 'add' => '1'))){ 
									echo'<li class=" "><a href="employee.php?view=add"><span><i class="fa fa-genderless" aria-hidden="true"></i> Add Employee </span></a></li>';
								}
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '16', 'view' => '1'))){ 
									echo'<li class=" "><a href="employee.php"><span><i class="fa fa-genderless" aria-hidden="true"></i> Employees List</span></a></li>
									<li class=" "><a href="employee.php?view=campus"><span><i class="fa fa-genderless" aria-hidden="true"></i> Campus Employee </span></a></li>';
								}
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '15', 'view' => '1'))){ 
									echo'<li class=" "><a href="designation.php"><span><i class="fa fa-genderless" aria-hidden="true"></i> Designation List</span></a></li>';
								}
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '10', 'view' => '1'))){ 
									echo'<li class=" "><a href="department.php"><span><i class="fa fa-genderless" aria-hidden="true"></i> Department List</span></a></li>';
								}
								echo'
							</ul>
						</li>';
					}
					// EXAMS
					if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '81', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '80', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '79', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '12', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '11', 'view' => '1'))){ 
						echo'
						<li class="nav-parent  ">
							<a>
								<i class="fa fa-graduation-cap" aria-hidden="true"></i>
								<span>Exam</span>
							</a>

							<ul class="nav nav-children">';

								// EXAM CALENDER, EXAM TYPES
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '12', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '11', 'view' => '1'))){ 
									echo'
									<!-- Calender -->
									<li class="nav-parent">
										<a>
											<i class="fa fa-calendar" aria-hidden="true"></i>
											<span>Exam Calender</span>
										</a>
										<ul class="nav nav-children">';
											if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '12', 'view' => '1'))){ 
												echo'<li class=" "><a href="exam_calender.php"><span>Exam Calender</span></a></li>';
											}
											if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '11', 'view' => '1'))){ 
												// echo'<li class=" "><a href="exam_types.php"><span>Exam Types</span></a></li>';
											}
											echo'
										</ul>
									</li>';
								}
								// DOWNLOADS
								// if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '81', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '80', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '79', 'view' => '1'))){ 
								// 	echo'
								// 	<li class="nav-parent">
								// 		<a>
								// 			<i class="fa fa-download" aria-hidden="true"></i>
								// 			<span>Downloads</span>
								// 		</a>
								// 		<ul class="nav nav-children">';
								// 			if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '81', 'view' => '1'))){ 
								// 				echo'<li class=" "><a href="exam_scheme.php"><span>Exam Scheme</span></a></li>';
								// 			}
								// 			if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '80', 'view' => '1'))){ 
								// 				echo'<li class=" "><a href="exam_policy.php"><span>Exam policy</span></a></li>';
								// 			}
								// 			if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '79', 'view' => '1'))){ 
								// 				echo'<li class=" "><a href="exam_manual.php"><span>Exam Manual</span></a></li>';
								// 			}
								// 			echo'
								// 		</ul>
								// 	</li>';
								// }
								// DATESHEET
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '12', 'view' => '1'))){ 
									echo'
									<li class="nav-parent">
										<a>
											<i class="fa fa-clock-o" aria-hidden="true"></i>
											<span>Datesheet</span>
										</a>
										<ul class="nav nav-children">
											<li class=" "><a href="exam_datesheet.php"><span><i class="fa fa-genderless" aria-hidden="true"></i> List</span></a></li>
											<li class=" "><a href="exam_datesheet.php?view=routine"><span><i class="fa fa-genderless" aria-hidden="true"></i> View By Type </span></a></li>
										</ul>
									</li>';
								}
								echo'
								<!--
								<li class="nav-parent  ">
									<ul class="nav nav-parent">
										<li class=" ">
											<a href="examss.php">
												<span><i class="fa fa-genderless" aria-hidden="true"></i> Exam List</span>
											</a>
										</li>
										<li class=" ">
											<a href="exam_term.php">
												<span><i class="fa fa-genderless" aria-hidden="true"></i> Set Exam Term</span>
											</a>
										</li>
										<li class=" ">
											<a href="#">
												<span><i class="fa fa-genderless" aria-hidden="true"></i> Exam Attendance</span>
											</a>
										</li>
										<li class=" ">
											<a href="#">
												<span><i class="fa fa-genderless" aria-hidden="true"></i> Set Attendance</span>
											</a>
										</li>
									</ul>
								</li> -->
							</ul>
						</li>';
					}
					// STATIONARY
					if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('type' => '13'))){ 
						echo'
						<li class="nav-parent  ">
							<a>
								<i class="fa fa-paperclip"></i>
								<span>Statianory</span>
							</a>
							<ul class="nav nav-children">';
								// STATIONARY STOCK
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '35', 'view' => '1'))){ 
									echo'
									<li class="">
										<a href="stationary_stock.php">
											<span><i class="fa fa-genderless"></i> Statianory Stock</span>
										</a>
									</li>';
								}
								// STATIONARY PURCHASE
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '52', 'view' => '1'))){ 
									echo'
									<li class="">
										<a href="stationary_purchase.php">
											<span><i class="fa fa-genderless"></i> Statianory Purchase</span>
										</a>
									</li>';
								}
								// STATIONARY REQUEST
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '53', 'view' => '1'))){ 
								echo'
									<li class="">
										<a href="stationary_request.php">
											<span><i class="fa fa-genderless"></i> Statianory Request</span>
										</a>
									</li>';
								}
								// STATIONARY SALE
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '54', 'view' => '1'))){ 
									echo'
									<li class="">
										<a href="stationary_sale.php">
											<span><i class="fa fa-genderless"></i> Statianory Sales</span>
										</a>
									</li>';
								}
								echo'					
							</ul>
						</li>';
					}
					// COMPLAINT AND SUGGESTION
					if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('type' => '7'))){ 
						echo'
						<li class="nav-parent  ">
							<a>
								<i class="fa fa-lightbulb-o"></i>
								<span>Compaint/Suggestion</span>
							</a>
							<ul class="nav nav-children">';
								// COMPLAINT AND SUGGESTION LIST
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '24', 'view' => '1'))){ 
									echo'
									<li class="">
										<a href="complaint_suggestion.php">
											<span><i class="fa fa-genderless"></i> List</span>
										</a>
									</li>';
								}
								// COMPLAINT AND SUGGESTION TYPES
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '23', 'view' => '1'))){ 
									echo'
									<li class="">
										<a href="complainttype.php">
											<span><i class="fa fa-genderless"></i> Types</span>
										</a>
									</li>';
								}
								echo'
							</ul>
						</li>';
					}

					// echo'
					// <li class=" ">
					// 	<a href="#">
					// 		<i class="fa fa-file-picture-o"></i>
					// 		<span>Media Gallery</span>
					// 	</a>
					// </li>';

					// DAILY QUOTATIONS
					if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '86', 'view' => '1'))){ 
						echo'
						<li class=" ">
							<a href="daily_quotation.php">
								<i class="fa fa-quote-left"></i>
								<span>Daily Quotation</span>
							</a>
						</li>';
					}

					// EVENTS
					if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '42', 'view' => '1'))){ 
						echo'
						<li class=" ">
							<a href="event.php">
								<i class="fa fa-file-text-o"></i>
								<span>Events</span>
							</a>
						</li>';
					}

					// echo'
					// <li class=" ">
					// 	<a href="awards.php">
					// 		<i class="fa fa-trophy"></i>
					// 		<span>Awards</span>
					// 	</a>
					// </li>';

					// SMS AND EMAIL
					if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '38', 'view' => '1'))){ 
						echo'
						<li class="nav-parent">
							<a>
								<i class="glyphicon glyphicon-comment"></i>
								<span>Bulk Email / SMS</span>
							</a>
							<ul class="nav nav-children">
								<li class="">
									<a href="#">
										<span><i class="fa fa-genderless" aria-hidden="true"></i> Template</span>
									</a>
								</li>
								<li class="">
									<a href="#">
										<span><i class="fa fa-genderless" aria-hidden="true"></i> Message</span>
									</a>
								</li>
								<li class="">
									<a href="#">
										<span><i class="fa fa-genderless" aria-hidden="true"></i> Alert SMS</span>
									</a>
								</li>
							</ul>
						</li>';
					}

					// echo'
					// <li class="nav-parent  ">
					// 	<a>
					// 		<i class="fa fa-users"></i>
					// 		<span>Visitors</span>
					// 	</a>
					// 	<ul class="nav nav-children">
					// 		<li class="">
					// 			<a href="visitor_purposes.php">
					// 				<span><i class="fa fa-genderless" aria-hidden="true"></i> Purposes</span>
					// 			</a>
					// 		</li>
					// 		<li class="">
					// 			<a href="visitors.php">
					// 				<span><i class="fa fa-genderless" aria-hidden="true"></i> Visitors</span>
					// 			</a>
					// 		</li>
					// 	</ul>
					// </li>';

					// REPORTS
					if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '29', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '27', 'view' => '1'))){ 
						echo'
						<li class="nav-parent  ">
							<a>
								<i class="fa fa-pie-chart"></i>
								<span>Report</span>
							</a>
							<ul class="nav nav-children">';
								// INCOME EXPENSE REPORT
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '29', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '27', 'view' => '1'))){ 
									echo'
									<li class="">
										<a href="incomeexpense_report.php">
											<span><i class="fa fa-genderless" aria-hidden="true"></i> Income & Expense</span>
										</a>
									</li>';
								}
								// ADMISSION REPORT
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'view' => '1'))){ 
									echo'
									<li class="">
										<a href="admission_report.php">
											<span><i class="fa fa-genderless" aria-hidden="true"></i> Admission Report</span>
										</a>
									</li>';
								}
								echo'
							</ul>
						</li>';
					}
					// NOTIFICATIONS
					if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '78', 'view' => '1'))){ 
						echo'
						<li class=" ">
							<a href="notifications.php"><i class="fa fa-bell"></i><span>Notifications</span></a>
						</li>';
					}
					// MONTHLY PERFORMA
					if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '82')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '83')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '84'))){
						echo'
						<li class="nav-parent  ">
							<a>
								<i class="fa fa-file-text"></i>
								<span>Facility Performa</span>
							</a>
							<ul class="nav nav-children">';
								// INSPECTION PERFOMA
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '84', 'view' => '1'))){ 
									echo'
									<li class="">
										<a href="performa.php">
											<span><i class="fa fa-genderless" aria-hidden="true"></i> Inspection Performa</span>
										</a>
									</li>';
								}
								// FACILITY QUESTIONS
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '83', 'view' => '1'))){ 
									echo'
									<li class="">
										<a href="facility_question.php">
											<span><i class="fa fa-genderless" aria-hidden="true"></i> Facility Questions</span>
										</a>
									</li>';
								}
								// FAVILITY CATEGOIES
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '82', 'view' => '1'))){ 
									echo'
									<li class="">
										<a href="facility_cat.php">
											<span><i class="fa fa-genderless" aria-hidden="true"></i> Facility Category</span>
										</a>
									</li>';
								}
								echo'
							</ul>
						</li>';
					}
					// INSPECTION SCHEDULE
					if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || (arrayKeyValueSearch($_SESSION['userroles'], 'right_name', '85'))) {
						echo'
						<li class=" ">
							<a href="inspectionSchedule.php"><i class="fa fa-tripadvisor"></i><span>Inscpection Schedule</span></a>
						</li>';
					}
					// TRAINING VIDEOS
					if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '65', 'view' => '1'))){ 
						echo'
						<li class=" ">
							<a href="training_videos.php">
								<i class="fa fa-video-camera"></i>
								<span>Training Videos</span>
							</a>
						</li>';
					}
					// FRENCHIES
					if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '20', 'view' => '1'))){ 
						echo'
						<li class="nav-parent">
							<a><i class="fa fa-university"></i><span>Campus</span></a>
							<ul class="nav nav-children" style="overflow: auto;">';
								// CAMPUS
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '20', 'view' => '1'))){ 
									echo'<li><a href="campuses.php"><span><i class="fa fa-university" aria-hidden="true"></i> Campus</span></a></li>';
								}
								// CAMPUS LOGIN
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '20', 'view' => '1'))){ 
									echo'<li><a href="campuslogin.php"><span><i class="fa fa-user" aria-hidden="true"></i> Campus Login</span></a></li>';
								}
								echo'
								<li class=""><a href="brand.php"><i class="fa fa-genderless" aria-hidden="true"></i>Brands</a></li>
								<li class=""><a href="campus_group.php"><i class="fa fa-genderless" aria-hidden="true"></i>Campus Groups</a></li>
								<li class=""><a href="campus_level.php"><i class="fa fa-genderless" aria-hidden="true"></i>Campus Level</a></li>
							</ul>
						</li>';
					}
					// SETTINGS
					if(cleanvars($_SESSION['userlogininfo']['LOGINAFOR']) == 1){
						echo'
						<li class="nav-parent">
							<a><i class="fa fa-cogs"></i><span>Setting</span></a>
							<ul class="nav nav-children">
								<li class="nav-parent">
									<a>
										<i class="fa fa-cogs" aria-hidden="true"></i>
										<span>Area Settings</span>
									</a>
									<ul class="nav nav-children">
										<li class=" "><a href="province.php"><span>Province</span></a></li>
										<li class=" "><a href="zone.php"><span>Zone</span></a></li>
										<li class=" "><a href="district.php"><span>District</span></a></li>
										<li class=" "><a href="city.php"><span>Area</span></a></li>
									</ul>
								</li>';
								// HEAD OFFICE LOGINS
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '45', 'view' => '1'))){ 
									echo'
									<li><a href="admins.php"><span><i class="fa fa-user" aria-hidden="true"></i> Head Office Login</span></a></li>
									<li><a href="addeLogin.php"><span><i class="fa fa-user" aria-hidden="true"></i> ADE / DDE Login</span></a></li>';
								}
								// SESSION AND SESSION SETTING
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '51', 'view' => '1'))){ 
									echo'
									<li><a href="settings.php"><span><i class="fa fa-cogs" aria-hidden="true"></i> Settings</span></a></li>
									<li><a href="sessions.php"><span><i class="fa fa-calendar-check-o" aria-hidden="true"></i> Sessions</span></a></li>';
								}
								echo'
							</ul>
						</li>';
					}
					// PROFILE
					echo'
					<li class=" ">
						<a href="profile.php"><i class="fa fa-lock"></i><span>My Profile</span></a>
					</li>
				</ul>
			</nav>
		</div>
		<script>
			// Maintain Scroll Position
			if (typeof localStorage !== "undefined") {
				if (localStorage.getItem("sidebar-left-position") !== null) {
					var initialPosition = localStorage.getItem("sidebar-left-position"),
					sidebarLeft = document.querySelector("#sidebar-left .nano-content");
					sidebarLeft.scrollTop = initialPosition;
				}
			}
		</script>
	</div>
</aside>';