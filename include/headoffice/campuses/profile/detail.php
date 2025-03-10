<?php
$sqllms	= $dblms->querylms("SELECT c.campus_id, c.id_type, c.parent_campus, c.campus_status, c.campus_regno, c.govt_regno, c.bise_affiliation, c.established_date, c.campus_name, c.campus_code, c.campus_address, c.campus_email, c.campus_phone, c.campus_head, c.is_tvi, c.campus_website, c.campus_logo,
								b.brand_name, ci.city_name
								FROM ".CAMPUS." c  
								LEFT JOIN ".BRANDS." b ON b.brand_id = c.id_brand
								LEFT JOIN ".TEHSIL_CITIES." ci ON ci.city_id = c.id_city
								WHERE c.campus_id = '".cleanvars($_GET['id'])."' LIMIT 1");
$rowsvalues = mysqli_fetch_array($sqllms);
if($rowsvalues['campus_logo']) { 
	$photo = "uploads/images/campus/".$rowsvalues['campus_logo']." ";
} else{
	$photo = "uploads/logo.png";
}

if($rowsvalues['parent_campus'] != '0'){
	$sqlParentCampus	= $dblms->querylms("SELECT campus_name FROM ".CAMPUS." WHERE campus_id = '".cleanvars($rowsvalues['parent_campus'])."' LIMIT 1");
	$valParentCampus 	= mysqli_fetch_array($sqlParentCampus);
	$parent_campus		= $valParentCampus['campus_name'];
}else{
	$parent_campus		= '';
}
echo'	
<div class="col-md-4">
	<section class="panel">
		<div class="panel-body">
			<div class="thumb-info mb-md">
    			<img src="'.$photo.'" class="rounded img-responsive">
				<div class="thumb-info-title">
					<span class="thumb-info-inner">'.$rowsvalues['campus_name'].'</span>
					<span>'.get_status($rowsvalues['campus_status']).'</span>
				</div>
			</div>	
			<div class="widget-toggle-expand mb-xs">
				<div class="widget-content-expanded">
					<table class="table table-striped table-condensed mb-none">
						<tr>
							<td>Type</td>
							<td align="right">'.get_campus_type($rowsvalues['id_type']).'</td>
						</tr>';
						if(!empty($parent_campus)){
							echo'
							<tr>
								<td>Parent Campus</td>
								<td align="right">'.$parent_campus.'</td>
							</tr>';
						}
						echo'
						<tr>
							<td>Brand</td>
							<td align="right">'.$rowsvalues['brand_name'].'</td>
						</tr>
						<tr>
							<td>Name</td>
							<td align="right">'.$rowsvalues['campus_name'].'</td>
						</tr>
						<tr>
							<td>Code</td>
							<td align="right">'.$rowsvalues['campus_code'].'</td>
						</tr>
						<tr>
							<td>IMS Regno#</td>
							<td align="right">'.$rowsvalues['campus_regno'].'</td>
						</tr>
						<tr>
							<td>Govt. Regno#</td>
							<td align="right">'.$rowsvalues['govt_regno'].'</td>
						</tr>
						<tr>
							<td>BISE Affiliation</td>
							<td align="right">'.$rowsvalues['bise_affiliation'].'</td>
						</tr>
						<tr>
							<td>Established Date</td>
							<td align="right">'.$rowsvalues['established_date'].'</td>
						</tr>
						<tr>
							<td>Eamil</td>
							<td align="right">'.$rowsvalues['campus_email'].'</td>
						</tr>
						<tr>
							<td>Phone</td>
							<td align="right">'.$rowsvalues['campus_phone'].'</td>
						</tr>
						<tr>
							<td>Principal</td>
							<td align="right">'.$rowsvalues['campus_head'].'</td>
						</tr>
						<tr>
							<td>TVI</td>
							<td align="right">'. get_statusyesno($rowsvalues['is_tvi']).'</td>
						</tr>
						<tr>
							<td>Website</td>
							<td align="right">'.$rowsvalues['campus_website'].'</td>
						</tr>
						<tr>
							<td>Area</td>
							<td align="right">'.$rowsvalues['city_name'].'</td>
						</tr>
						<tr>
							<td>Address</td>
							<td align="right">'.$rowsvalues['campus_address'].'</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</section>
</div>';
?>