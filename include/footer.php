<?php 
echo'
</div>
<div class="social__icon text-center">
	<a href="#"><i class="fa fa-facebook-f"></i></a>
	<a href="#"><i class="fa fa-instagram"></i></a>
	<a href="#"><i class="fa fa-twitter"></i></a>
	<a href="#"><i class="fa fa-youtube"></i></a>
	<a href="#"><i class="fa fa-linkedin"></i></a>
</div>
</section>
<!-- VENDOR -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>
<script src="assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
<script src="assets/vendor/nanoscroller/nanoscroller.js"></script>
<script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="assets/vendor/magnific-popup/jquery.magnific-popup.js"></script>
<script src="assets/vendor/jquery-placeholder/jquery-placeholder.js"></script>
<script src="assets/vendor/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script src="assets/vendor/jquery-ui/jquery-ui.js"></script>
<script src="assets/vendor/jqueryui-touch-punch/jqueryui-touch-punch.js"></script>
<script src="assets/vendor/select2/js/select2.js"></script>
<script src="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
<script src="assets/vendor/jquery-maskedinput/jquery.maskedinput.js"></script>
<script src="assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
<script src="assets/vendor/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script src="assets/vendor/bootstrap-timepicker/bootstrap-timepicker.js"></script>
<script src="assets/vendor/fuelux/js/spinner.js"></script>
<script src="assets/vendor/dropzone/dropzone.js"></script>
<script src="assets/vendor/bootstrap-markdown/js/markdown.js"></script>
<script src="assets/vendor/bootstrap-markdown/js/to-markdown.js"></script>
<script src="assets/vendor/bootstrap-markdown/js/bootstrap-markdown.js"></script>
<script src="assets/vendor/summernote/summernote.js"></script>
<script src="assets/vendor/bootstrap-maxlength/bootstrap-maxlength.js"></script>
<script src="assets/vendor/ios7-switch/ios7-switch.js"></script>
<script src="assets/vendor/bootstrap-confirmation/bootstrap-confirmation.js"></script>

<!-- DATATABLES PAGE VENDOR -->
<script src="assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
<script src="assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>

<!-- FILEINPUT JS -->
<script src="assets/javascripts/fileinput.js"></script>
<script src="assets/vendor/bootstrap-fileupload/bootstrap-fileupload.min.js"></script>
	
<!-- PNOTIFY NOTIFICATIONS JS -->
<script src="assets/vendor/pnotify/pnotify.custom.js"></script>

<!-- ANIMATIONS APPEAR JS -->
<script src="assets/vendor/jquery-appear/jquery-appear.js"></script>

<!-- FORM VALIDATION -->
<script src="assets/vendor/jquery-validation/jquery.validate.js"></script>

<!-- Theme Base, Components and Settings -->
<script src="assets/javascripts/theme.js"></script>

<!-- THEME CUSTOM -->
<script src="assets/javascripts/theme.custom.js"></script>

<!-- THEME INITIALIZATION FILES -->
<script src="assets/javascripts/theme.init.js"></script>

<!-- COUNTER INCREASE NUMBERS -->
<script src="assets/javascripts/jquery.waypoints.min.js"></script>
<script src="assets/javascripts/jquery.countup.js"></script>

<!-- CALENDAR FILES -->
<script src="assets/vendor/moment/moment.js"></script>
<script src="assets/vendor/fullcalendar/fullcalendar.js"></script>

<!-- CHART FILES -->
<script src="assets/vendor/liquid-meter/liquid.meter.js"></script>
<script src="assets/vendor/snap.svg/snap.svg.js"></script>
<script src="assets/vendor/snap.svg/snap.svg.js"></script>
<script src="assets/vendor/liquid-meter/liquid.meter.js"></script>
	
<!-- USER JS -->
<script src="assets/javascripts/user_config/dashboard.js"></script>
<script src="assets/javascripts/user_config/forms_validation.js"></script>
<script src="assets/javascripts/modals.js"></script>

<!--Input Mask-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>


<script type="text/javascript">
	jQuery(document).ready(function($)	{
		$(\'.table_default\').dataTable( {
			bAutoWidth : false,
			ordering: false
		});
	});
</script>
<!-- SHOW PNOTIFIVATION -->

<script type="text/javascript">
	$(\'.popup-youtube\').magnificPopup({
		disableOn: 700,
		type: \'iframe\',
		mainClass: \'mfp-fade\',
		removalDelay: 160,
		preloader: false,
		fixedContentPos: false
	});

	$(\'.thumbnail .mg-toggle\').parent()
	.on(\'show.bs.dropdown\', function( ev ) {
		$(this).closest(\'.mg-thumb-options\').css(\'overflow\', \'visible\');
	})
	.on(\'hidden.bs.dropdown\', function( ev ) {
		$(this).closest(\'.mg-thumb-options\').css(\'overflow\', \'\');
	});

	$(\'.thumbnail\').on(\'mouseenter\', function() {
		var toggle = $(this).find(\'.mg-toggle\');
		if ( toggle.parent().hasClass(\'open\') ) {
			toggle.dropdown(\'toggle\');
		}
	});
	
	$(".counter").countUp();


</script>
<script>
	// ==============================
	// Admission Date
	// ==============================
	const admissionDate = document.getElementById("std_admissiondate");

	// Set current date as default (mm/dd/yyyy)
	const today = new Date();
	const mm = String(today.getMonth() + 1).padStart(2, "0");
	const dd = String(today.getDate()).padStart(2, "0");
	const yyyy = today.getFullYear();
	admissionDate.value = `${mm}/${dd}/${yyyy}`;

	// Block non-date characters
	admissionDate.addEventListener("keydown", function (e) {
		const allowed = ["Backspace", "Tab", "ArrowLeft", "ArrowRight", "Delete", "/", "Home", "End"];
		const isNumber = e.key >= "0" && e.key <= "9";
		if (!isNumber && !allowed.includes(e.key)) e.preventDefault();
	});

	// Validate date format on blur
	admissionDate.addEventListener("blur", function () {
		const pattern = /^(0[1-9]|1[0-2])\/(0[1-9]|[12]\d|3[01])\/\d{4}$/;
		if (!pattern.test(this.value)) {
			// alert("Please enter a valid date in mm/dd/yyyy format");
			this.focus();
		}
	});

	/* INPUT MASK */
	$(document).ready(function(){
		$(".cnic").mask("00000-0000000-0");
		$(".phone").mask("9999-9999999");
		$(".date_mask").mask("99-99-9999");
	});
</script>

</body>
</html>';
?>