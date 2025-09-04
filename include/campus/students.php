<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('1', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'view' => '1'))) {
	include_once("students/query_students.php");
	echo '
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
	<link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet"/>
	<script src="https://unpkg.com/cropperjs"></script>
	
	<title>Student Panel | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>'.(isset($_GET['id']) ? ' Student Profile' : ' Student Panel').'</h2>
		</header>
		<div class="row">
			<div class="col-md-12">';
				if($view == 'add') {
					include_once("students/student_add.php");
				} elseif(isset($_GET['inquiry'])) {
					include_once("students/inquiry_add.php");
				} elseif(isset($_GET['id'])) {
					include_once("students/student_edit.php");
				} else {
					if ($view === 'import_admissions') {
						include_once("students/import_admissions.php");
					} else {
						include_once("students/list_students.php");
					}
				}
				echo '
			</div>
		</div>		
	</section>';
	?>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script>
		let nicValid = true;
		let rollValid = true;
		function checkStudent() {
			let nic = $("#std_cnic").length ? $("#std_cnic").val().trim() : "";
let roll = $("#std_rollno").length ? $("#std_rollno").val().trim() : "";

			// if both are empty → clear errors
			if (nic === "" && roll === "") {
				$("#cnic_status").text("");
				$("#roll_status").text("");
				nicValid = rollValid = true;
				return;
			}
			$.ajax({
				url: "include/ajax/check_student.php",
				type: "POST",
				dataType: "json",
				data: {
					id_campus: "<?php echo $_SESSION['userlogininfo']['LOGINCAMPUS']; ?>",
					std_nic: $("#std_cnic").val(),
					std_rollno: $("#std_rollno").val()
				},
				success: function(res) {
					// NIC check
					if (nic !== "") {
						if (res.nic === "exists") {
							$("#cnic_status").text("⚠ CNIC already exists!").css("color", "red");
							nicValid = false;
						} else {
							$("#cnic_status").text("✔ Available").css("color", "green");
							nicValid = true;
						}
					} else {
						// field empty → clear error/status
						$("#cnic_status").text("");
						nicValid = true;
					}

					// Roll check
					if (roll !== "") {
						if (res.roll === "exists") {
							$("#roll_status").text("⚠ Roll No already exists!").css("color", "red");
							rollValid = false;
						} else {
							$("#roll_status").text("✔ Available").css("color", "green");
							rollValid = true;
						}
					} else {
						// field empty → clear error/status
						$("#roll_status").text("");
						rollValid = true;
					}
				}

			});
		}

		// Trigger check when user leaves input field
		$("#std_cnic, #std_rollno").on("keyup blur", checkStudent);

		// Prevent form submission if invalid
		$("#studentForm").on("submit", function(e) {
			if (!nicValid || !rollValid) {
				e.preventDefault();
				// alert("Fix the errors before submitting!");
			}
		});
	</script>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			<?php 
			if(isset($_SESSION['msg'])) { 
				echo 'new PNotify({
					title	: "'.$_SESSION['msg']['title'].'"	,
					text	: "'.$_SESSION['msg']['text'].'"	,
					type	: "'.$_SESSION['msg']['type'].'"	,
					hide	: true	,
					buttons: {
						closer	: true	,
						sticker	: false
					}
				});';
			unset($_SESSION['msg']);
		}
			?>	
			var datatable = $('#table_export').dataTable({
				bAutoWidth : false,
				ordering: false,
				sDom: "<'text-right mb-md'T>" + $.fn.dataTable.defaults.sDom,
				oTableTools: {
					sSwfPath: 'assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf',
					aButtons: [
						{
							sExtends: 'pdf',
							sButtonText: 'PDF',
							mColumns: [0,1,2,3,4]
						},
						{
							sExtends: 'csv',
							sButtonText: 'CSV',
							mColumns: [0,1,2,3,4]
						},
						{
							sExtends: 'xls',
							sButtonText: 'Excel',
							mColumns:[0,1,2,3,4]
						},
						{
							sExtends: 'print',
							sButtonText: 'Print',
							sInfo: '',
							fnClick: function (nButton, oConfig) {

								datatable.fnSetColumnVis(11, false); // Hide the 4th column (index 3)
								datatable.fnSetColumnVis(12, false); // Hide the 5th column (index 4)
								datatable.fnSetColumnVis(13, false); // Hide the 6th column (index 5)
								
								this.fnPrint( true, oConfig );
								
								window.print();
								
								$(window).keyup(function(e) {
									if (e.which == 27) {
										datatable.fnSetColumnVis(11, true); // Show the 4th column
										datatable.fnSetColumnVis(12, true); // Show the 5th column
										datatable.fnSetColumnVis(13, true); // Show the 6th column
									}
								});
							}
						}
					]
				}
			});
		});

		function get_formno(form_no) {  
			$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
			$.ajax({  
				type: "POST",  
				url: "include/ajax/get_admissiondetail.php",  
				data: "form_no="+form_no,  
				success: function(msg){  
					$("#getadmissiondetail").html(msg); 
					$("#loading").html(''); 
				}
			});  
		}
		function get_section(id_class) {  
			$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');
			var id_campus = $("#id_campus").val(); 
			$.ajax({  
				type: "POST",  
				url: "include/ajax/get_section.php",  
				data: {
					 'id_class'		: id_class
					,'id_campus'	: id_campus
				},  
				success: function(msg){  
					$("#id_section").html(msg); 
					$("#loading").html(''); 
				}
			});  
		}
		function get_class(id_campus) {  
			$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
			$.ajax({  
				type: "POST",  
				url: "include/ajax/get_class.php",  
				data: {
					'id_campus'	: id_campus
				},
				success: function(msg){  
					$("#id_class").html(msg); 
					$("#loading").html(''); 
				}
			});
		}
		function get_guardian_form(id_guardian) {  
			$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
			$.ajax({  
				type: "POST",  
				url: "include/ajax/get_guardian_form.php",  
				data: {
					'id_guardian'	: id_guardian
				},
				success: function(msg){  
					$("#guardian_form").html(msg); 
					$("#loading").html(''); 
				}
			});
		}

		// Crop Image
		function getCropped() {
            var cropModal = $('#modal');
            var image = document.getElementById('sample_image');
            var cropper;

            $('#upload_image').change(function(event) {
                var files = event.target.files;

                var done = function(url) {
                    image.src = url;
                    $('#crop').attr('disabled', false).html('Crop');
                    cropModal.modal('show');
                };

                if (files && files.length > 0) {
                    var reader = new FileReader();
                    reader.onload = function(event) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(files[0]);
                }
            });

            cropModal.on('shown.bs.modal', function() {
                cropper = new Cropper(image, {
                    aspectRatio: 1,
                    viewMode: 3,
                    preview: '.preview'
                });
            }).on('hidden.bs.modal', function() {
                cropper.destroy();
                cropper = null;
            });

            $('#crop').click(function() {
                $('#crop').attr('disabled', true).html('<i class="fa fa-circle-o-notch fa-spin"></i> Wait...');

                var canvas = cropper.getCroppedCanvas({
                    width: 400,
                    height: 400
                });

                canvas.toBlob(function(blob) {
                    var url = URL.createObjectURL(blob);
                    var reader = new FileReader();
                    reader.readAsDataURL(blob);
                    reader.onloadend = function() {
                        var base64data = reader.result;
                        //console.log(base64data);
                        cropModal.modal('hide');

                        $('#uploaded_image').attr('src', base64data);
                        $('#std_photo').val(base64data);
                    };
                });
            });
        }

		$(document).ready(function() {
            getCropped();
        });

	</script>
	<?php 
	echo '
	<!-- INCLUDES MODAL -->
	<script type="text/javascript">
		function showAjaxModalZoom( url ) {
			// PRELODER SHOW ENABLE / DISABLE
			jQuery( \'#show_modal\' ).html( \'<div style="text-align:center; "><img src="assets/images/preloader.gif" /></div>\' );
			// SHOW AJAX RESPONSE ON REQUEST SUCCESS
			$.ajax( {
				url: url,
				success: function ( response ) {
					jQuery( \'#show_modal\' ).html( response );
				}
			} );
		}
	</script>
	<!-- (STYLE AJAX MODAL)-->
	<div id="show_modal" class="mfp-with-anim modal-block modal-block-primary mfp-hide"></div>

	<!-- Image Crop Model Start -->
	<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg panel panel-featured panel-featured-primary" role="document">
			<div class="panel-heading">
				<h4 class="panel-title"><i class="fa fa-crop"></i> Crop Image Before Upload
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">�</span>
					</button>
				</h4>
			</div>
			<div class="panel-body">
				<div class="img-container">
					<div class="row">
						<div class="col-md-8">
							<img src="" id="sample_image" width="100%"/>
						</div>
						<div class="col-md-4">
							<div class="preview"></div>
						</div>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="button" id="crop" class="btn btn-primary">Crop</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					</div>
				</div>
			</footer>
		</div>
	</div>

	<!-- Image Crop Model End -->
	<script type="text/javascript">
		function confirm_modal( delete_url ) {
			swal( {
				title: "Are you sure?",
				text: "Are you sure that you want to delete this information?",
				type: "warning",
				showCancelButton: true,
				showLoaderOnConfirm: true,
				closeOnConfirm: false,
				confirmButtonText: "Yes, delete it!",
				cancelButtonText: "Cancel",
				confirmButtonColor: "#ec6c62"
			}, function () {
				// location.reload();
				window.location.href = delete_url;
			} );
		}
	</script>';
}else{
	header("location: dashboard.php");
}
?>
