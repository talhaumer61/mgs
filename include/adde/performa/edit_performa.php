<?php
//---------------------------------------------------------
$sqlPerforma = $dblms->querylms("SELECT *
										FROM ".CAMPUS_PERFORMA." a 
										WHERE a.id = '".$_GET['id']."' LIMIT 1");			   
$valuePerforma = mysqli_fetch_array($sqlPerforma);
//---------------------------------------------------------
if($valuePerforma['edit_count'] >= 1){
	$dis = "disabled";
}
else{
	$dis = "";
}
	echo '
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
				<h2 class="panel-title"><i class="fa fa-plus-square"></i>	Edit Campus Performa of <b>'.get_monthtypes($valuePerforma['visit_month']).'</b></h2>
			</header>
			<div class="panel-body">
				<div class="form-group">
				<input type="hidden" name="edit_count" value="'.$valuePerforma['edit_count'].'">
					<div class="col-md-4 col-md-offset-4">
						<label class="control-label">Campus <span class="required">*</span></label>
						<input type="hidden" name="id_campus" value="'.$valuePerforma['id_campus'].'">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_campus" disabled>
							<option value="">Select</option>';
							$sqllmscls	= $dblms->querylms("SELECT campus_id, campus_name 
														FROM ".CAMPUS."
														WHERE campus_id != '' 
														ORDER BY campus_id DESC");
							while($valuecls = mysqli_fetch_array($sqllmscls)) {
							echo '  <option value="'.$valuecls['campus_id'].'" 
										'.(($valuePerforma['id_campus'] == $valuecls['campus_id'] ) ? 'selected' : '' ).' 
									>
										'.$valuecls['campus_name'].'
									</option>';
							}
						echo '
						</select>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-4 col-md-offset-4">
						<label class="control-label">Attach File </label>
						<input type="hidden" name="db_attach_file" value="'.$valuePerforma['attach_file'].'">
						<input type="file" accept=".jpg, .jpeg, .gif, .png, .psd, .docx" class="form-control" name="attach_file">
					</div>
				</div>
				';
				$sqllmsCat	= $dblms->querylms("SELECT cat_name,cat_id
													FROM ".FACILITY_CATS."	 
													WHERE cat_status = '1' 
													ORDER BY cat_ordering ASC");
				$catOrder = 0 ;
				$catNum = 0; // For fixing array insert issue
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
							<th class="">Question</th>
							<th class="" style="width:140px;">Rating</th>
							<th class="" style="width:180px;"></th>
						</tr>
					</thead>
					<tbody>';
						//-----------------------------------------------------
						$sqllms	= $dblms->querylms("SELECT q.question_name, q.question_id, 
														q.id_cat,d.rating,d.is_applicable
														FROM ".FACILITY_QESTIONS." q
														INNER JOIN 	".CAMPUS_PERFORMA_DET." d ON d.id_question = q.question_id 
														WHERE q.question_status = '1'
														AND d.id_setup = '".cleanvars($_GET['id'])."' 
														AND q.id_cat = '".cleanvars($rowcat['cat_id'])."' 
														ORDER BY q.question_id ASC");
						$srno = 0;
						
						$srnum = 1 * $catNum;
						//-----------------------------------------------------
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
									<input type="hidden"  name="question_id['.$srnum.']" value="'.$rowsvalues['question_id'].'" '.$dis.'>
								</td>
								<td class="center">
									<div class="form-group mb-md">
										<div class="col-md-6">
											<input type="number" class="form-control text-center" name="rating['.$srnum.']" id="rating['.$srnum.']" value="'.$rowsvalues['rating'].'" required title="Must Be Required" min="0" max="5" '.$dis.'>
										</div>
									</div>	
									<!-- <div class="star-rating">
										<span class="fa fa-star-o" data-rating="1"></span>
										<span class="fa fa-star-o" data-rating="2"></span>
										<span class="fa fa-star-o" data-rating="3"></span>
										<span class="fa fa-star-o" data-rating="4"></span>
										<span class="fa fa-star-o" data-rating="5"></span>
										<input type="hidden" name="rating['.$srnum.']" value="'.$rowsvalues['rating'].'" required class="rating-value" value="">
									</div> -->
								</td>
								<td>
								<div class="form-group">
										<div class="col-md-12">
											<div class="checkbox-custom checkbox-inline">
												<input type="checkbox" name="is_applicable['.$srnum.']" ';if($rowsvalues['is_applicable']== 2){echo ' checked';} echo' '.$dis.'>
												<label for="checkboxExample&quot;">Not Applicable</label>
											</div>
										</div>
									</div>
								</td>
							</tr>';
						}
						echo '
						</tbody>
					</table>';
				}
				echo'
			</div>';
			if($valuePerforma['edit_count'] < 1 && $valuePerforma['status'] != 1 ){
				echo'
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<input type="hidden" name="performa_id" value="'.$_GET['id'].'">
							<button type="submit" class="btn btn-primary" id="update_performa" name="update_performa">Save</button>
							<a class="btn btn-default" href="performa.php">Cancel</a>
						</div>
					</div>
				</footer>';
			}
			echo'
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


	$(document).disy(function() {
		SetRatingStar();
	});
		
	</script>';
?>