</body>

</html>

<!-- VENDOR -->
<script src="<?= PARENTROOT ?>/assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
<script src="<?= PARENTROOT ?>/assets/vendor/bootstrap/js/bootstrap.js"></script>
<script src="<?= PARENTROOT ?>/assets/vendor/nanoscroller/nanoscroller.js"></script>
<script src="<?= PARENTROOT ?>/assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= PARENTROOT ?>/assets/vendor/magnific-popup/jquery.magnific-popup.js"></script>
<script src="<?= PARENTROOT ?>/assets/vendor/jquery-placeholder/jquery-placeholder.js"></script>
<script src="<?= PARENTROOT ?>/assets/vendor/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script src="<?= PARENTROOT ?>/assets/vendor/jquery-ui/jquery-ui.js"></script>
<script src="<?= PARENTROOT ?>/assets/vendor/jqueryui-touch-punch/jqueryui-touch-punch.js"></script>
<script src="<?= PARENTROOT ?>/assets/vendor/select2/js/select2.js"></script>
<script src="<?= PARENTROOT ?>/assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
<script src="<?= PARENTROOT ?>/assets/vendor/jquery-maskedinput/jquery.maskedinput.js"></script>
<script src="<?= PARENTROOT ?>/assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
<script src="<?= PARENTROOT ?>/assets/vendor/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script src="<?= PARENTROOT ?>/assets/vendor/bootstrap-timepicker/bootstrap-timepicker.js"></script>
<script src="<?= PARENTROOT ?>/assets/vendor/fuelux/js/spinner.js"></script>
<script src="<?= PARENTROOT ?>/assets/vendor/dropzone/dropzone.js"></script>
<script src="<?= PARENTROOT ?>/assets/vendor/bootstrap-markdown/js/markdown.js"></script>
<script src="<?= PARENTROOT ?>/assets/vendor/bootstrap-markdown/js/to-markdown.js"></script>
<script src="<?= PARENTROOT ?>/assets/vendor/bootstrap-markdown/js/bootstrap-markdown.js"></script>
<script src="<?= PARENTROOT ?>/assets/vendor/summernote/summernote.js"></script>
<script src="<?= PARENTROOT ?>/assets/vendor/bootstrap-maxlength/bootstrap-maxlength.js"></script>
<script src="<?= PARENTROOT ?>/assets/vendor/ios7-switch/ios7-switch.js"></script>
<script src="<?= PARENTROOT ?>/assets/vendor/bootstrap-confirmation/bootstrap-confirmation.js"></script>

<!--Tippy Js-->
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>
<script>
    $('*[data-tippy]').each(function(){
        tippy(this, {
            content: $(this).attr("data-tippy"),
        });
    })
</script>

<!-- DATATABLES PAGE VENDOR -->
<script src="<?= PARENTROOT ?>/assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
<script src="<?= PARENTROOT ?>/assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?= PARENTROOT ?>/assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>

<!-- FILEINPUT JS -->
<script src="<?= PARENTROOT ?>/assets/javascripts/fileinput.js"></script>
<script src="<?= PARENTROOT ?>/assets/vendor/bootstrap-fileupload/bootstrap-fileupload.min.js"></script>

<!-- ANIMATIONS APPEAR JS -->
<script src="<?= PARENTROOT ?>/assets/vendor/jquery-appear/jquery-appear.js"></script>

<!-- FORM VALIDATION -->
<script src="<?= PARENTROOT ?>/assets/vendor/jquery-validation/jquery.validate.js"></script>

<!-- Theme Base, Components and Settings -->
<script src="<?= PARENTROOT ?>/assets/javascripts/theme.js"></script>

<!-- THEME CUSTOM -->
<!--TODO: Need to change it to the PARENTROOT -->
<script src="<?= URLROOT ?>/assets/javascripts/theme.custom.js"></script>

<!-- THEME INITIALIZATION FILES -->
<script src="<?= PARENTROOT ?>/assets/javascripts/theme.init.js"></script>

<!-- CALENDAR FILES -->
<script src="<?= PARENTROOT ?>/assets/vendor/moment/moment.js"></script>
<script src="<?= PARENTROOT ?>/assets/vendor/fullcalendar/fullcalendar.js"></script>

<!-- CHART FILES -->
<script src="<?= PARENTROOT ?>/assets/vendor/liquid-meter/liquid.meter.js"></script>
<script src="<?= PARENTROOT ?>/assets/vendor/snap.svg/snap.svg.js"></script>
<script src="<?= PARENTROOT ?>/assets/vendor/snap.svg/snap.svg.js"></script>
<script src="<?= PARENTROOT ?>/assets/vendor/liquid-meter/liquid.meter.js"></script>

<!-- USER JS -->
<script src="<?= PARENTROOT ?>/assets/javascripts/user_config/dashboard.js"></script>
<script src="<?= PARENTROOT ?>/assets/javascripts/user_config/forms_validation.js"></script>
<script src="<?= PARENTROOT ?>/assets/javascripts/modals.js"></script>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('.table_default').dataTable({
            bAutoWidth: false,
            ordering: false
        });
    });
</script>
<!-- SHOW PNOTIFIVATION -->

<script type="text/javascript">
    $('.popup-youtube').magnificPopup({
        disableOn: 700,
        type: 'iframe',
        mainClass: 'mfp-fade',
        removalDelay: 160,
        preloader: false,
        fixedContentPos: false
    });

    $('.thumbnail .mg-toggle').parent()
        .on('show.bs.dropdown', function(ev) {
            $(this).closest('.mg-thumb-options').css('overflow', 'visible');
        })
        .on('hidden.bs.dropdown', function(ev) {
            $(this).closest('.mg-thumb-options').css('overflow', '');
        });

    $('.thumbnail').on('mouseenter', function() {
        var toggle = $(this).find('.mg-toggle');
        if (toggle.parent().hasClass('open')) {
            toggle.dropdown('toggle');
        }
    });
</script>

<script type="text/javascript">

  const jsonDataBtns = document.querySelectorAll("*[data-fetch-url]");

  jsonDataBtns.forEach(btn => {
    btn.addEventListener('click', async function() {
      const res = await axios.get(btn.dataset.fetchUrl);
      const modal = document.querySelector(`${btn.dataset.modal} > .panel`);

      // Reset the modal box
      modal.innerHTML = "<h2 style='color: white'>Loading ...</h2>"

      if (res.status !== 200){
        $.magnificPopup.close({
          items : {
            src : btn.dataset.modal
          },
        });
        return new PNotify({
          title   : "Invalid Id"   ,
          text    : "Something went wrong while processing your request."    ,
          type    : "error"    ,
          hide    : true  ,
          buttons: {
            closer  : true  ,
            sticker : false
          }
        });
      }

      modal.innerHTML = String(res.data);
    });
  })

  // CKEDITOR.replace("question_english");

  



  

</script>