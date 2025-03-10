<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '84', 'add' => '1'))){   
	$month = date("n");
	echo'
	  <style>
		.star-rating {
			line-height:32px;
			font-size:1.25em;
		}
	  	.star-rating .fa-star{color: yellow;} 
	  </style>
	  <section class="panel panel-featured panel-featured-primary">
	  
			  <form action="performa.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8" >
				  <header class="panel-heading">
					  <h2 class="panel-title"><i class="fa fa-plus-square"></i>	Make Campus Performa of <b>'.get_monthtypes($month).'</b></h2>
				  </header>
					<div class="panel-body">
						<input type="hidden" name="visit_month" value="'.$month.'">
						<div class="form-group">
							<div class="col-md-4 col-md-offset-4">
								<label class="control-label">Campus <span class="required">*</span></label>
								<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_campus">
									<option value="">Select</option>';
									$sqllmsCamp	= $dblms->querylms("SELECT c.campus_id, c.campus_name, b.id_ad, b.id_de
																FROM ".CAMPUS." c
																LEFT JOIN ".CAMPUS_BIOGRAPHY." b ON b.id_campus = c.campus_id 
																WHERE c.campus_id != '' AND c.campus_status = '1' AND c.is_deleted != '1' 
																ORDER BY c.campus_name");
									while($valueCamp = mysqli_fetch_array($sqllmsCamp)) {
										echo'<option value="'.$valueCamp['campus_id'].'|'.$valueCamp['id_ad'].'|'.$valueCamp['id_de'].'">'.$valueCamp['campus_name'].'</option>';
									}
								echo '
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-4 col-md-offset-4">
								<label class="control-label">Attach File </label>
								<input type="file" accept=".jpg, .jpeg, .gif, .png, .psd, .docx" class="form-control" name="attach_file">
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-4 col-md-offset-4">
								<label class="control-label">Status <span class="required" aria-required="true">*</span></label>';
								foreach($statusLeave as $status){
									echo'
									<div class="radio-custom radio-inline">
										<input type="radio" id="status" name="status" value="'.$status['id'].'">
										<label for="radioExample1">'.$status['name'].'</label>
									</div>';
								}
								echo'
							</div>
						</div>';
					$sqllmsCat	= $dblms->querylms("SELECT cat_name,cat_id
														FROM ".FACILITY_CATS."	 
														WHERE cat_status = '1' 
														ORDER BY cat_ordering ASC");
					$catOrder = 0 ;
					$catNum = 0; // For fixing array insert issue
					if(mysqli_num_rows($sqllmsCat) == 0){
						echo '
						<section class="panel panel-featured panel-featured-primary 
								appear-animation mt-sm fadeInRight appear-animation-visible" 
								data-appear-animation="fadeInRight" data-appear-animation-delay="100" >
							<h2 class="panel-body text-center font-bold text text-danger">
								Please Add Facility Questions First
							</h2>
						</section>';
					} else {
						while($rowcat = mysqli_fetch_array($sqllmsCat)) {
							$catOrder++;
							$catNum +=1000;		
							echo'
							<h2 class="panel-title text-muted text-center mt-xl mb-none">
								'.$rowcat['cat_name'].'
							</h2>
	  
							<table class="table table-striped table-condensed">
								<thead>
									<tr>
										<th class="" style="width:30px;">Srno.</th>
										<th class="">Inspection Statement</th>
										<th class="" style="width:140px;">Rating (0 to 5)</th>
										<th class="" style="width:180px;"></th>
									</tr>
								</thead>
								<tbody>';
								  //-----------------------------------------------------
								  $sqllms	= $dblms->querylms("SELECT question_name,question_id,id_cat
																	  FROM ".FACILITY_QESTIONS."	 
																	  WHERE question_status = '1'
																	  AND id_cat = '".$rowcat['cat_id']."' 
																	  ORDER BY question_id ASC");
								  $srno = 0;
								  
								  $srnum = 1 * $catNum;
								  //-----------------------------------------------------
									if(mysqli_num_rows($sqllms) == 0){
										echo '
										<section class="panel panel-featured panel-featured-primary 
												appear-animation mt-sm fadeInRight appear-animation-visible" 
												data-appear-animation="fadeInRight" data-appear-animation-delay="100" >
											<h2 class="panel-body text-center font-bold text text-danger">
												No Questions found under '.$rowcat['cat_name'].'
											</h2>
										</section>';
									} else { //Endif numrows condition
								  
										while($rowsvalues = mysqli_fetch_array($sqllms)) {
									//-----------------------------------------------------
										$srno++;
										$srnum++;
									//-----------------------------------------------------
									echo '
									<input type="hidden" name="id_cat['.$srnum.']" value="'.$rowsvalues['id_cat'].'">
										<tr>
										<td>'.$catOrder.'.'.$srno.'</td>
										<td>
											'.$rowsvalues['question_name'].'
											<input type="hidden"  name="question_id['.$srnum.']" value="'.$rowsvalues['question_id'].'">
											</td>
											<td class="center">
												<div class="form-group mb-md">
													<div class="col-md-6">
														<input type="number" class="form-control" name="rating['.$srnum.']" id="rating['.$srnum.']" required title="Must Be Required" min="0" max="5">
													</div>
												</div>	
												<!--
												<div class="star-rating">
												<span class="fa fa-star-o" data-rating="1"></span>
												<span class="fa fa-star-o" data-rating="2"></span>
												<span class="fa fa-star-o" data-rating="3"></span>
												<span class="fa fa-star-o" data-rating="4"></span>
												<span class="fa fa-star-o" data-rating="5"></span>
												<input type="text" name="rating['.$srnum.']" required class="rating-value" value="">-->
											</div>
											</td>
											<td>
											<div class="form-group">
												<div class="col-md-12">
													<div class="checkbox-custom checkbox-inline">
														<input type="checkbox" name="is_applicable['.$srnum.']">
														<label for="checkboxExample&quot;">Not Applicable</label>
													</div>
												</div>
											</div>
											</td>
										</tr>';
			//-----------------------------------------------------
										}
									} // End else num_rows 
		//-----------------------------------------------------
								echo '
								  </tbody>
						  </table>';
						} // End While
	  //-----------------------------------------------------

					} //End else num rows 
					

				  echo'
				  </div>
				  <footer class="panel-footer">
					  <div class="row">
						  <div class="col-md-12 text-right">
							  <button type="submit" class="btn btn-primary" id="submit_performa" name="submit_performa">Save</button>
							  <a class="btn btn-default" href="performa.php">Cancel</a>
						  </div>
					  </div>
				  </footer>
		  		</div>
			</form>
	  </section>
	 
	  <script>
	  var $star_rating = $(\'.star-rating\');

		var SetRatingStar = function() {  
			$star_rating.each(setStars);
		};


		function setStars() {
		
		var ratingVal = parseInt( $(this).find(\'input.rating-value\').val());
			$(this).children().removeClass(\'fa-star\').addClass(\'fa-star-o\');
			for(var i = 0; i < ratingVal; i ++)
			$(this).children().eq(i).removeClass(\'fa-star-o\').addClass(\'fa-star\');  
		}

		$star_rating.on(\'click\', \'.fa\', function() {  
			$(this).siblings(\'input.rating-value\').val($(this).index() + 1);
			setStars.call($(this).parent());
		});


		$(document).ready(function() {
			SetRatingStar();
		});
		 
	  </script>';
}
else{
	header("Location: academic-calender.php");
}
?>
