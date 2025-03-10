<div class="paper paper-print" id="paper-print-formate">
  <!--Print Stylesheet    -->
  <link rel="stylesheet"  href="<?= URLROOT ?>/assets/stylesheets/print.css" />
  <style>
    .flex-reverse{
      flex-direction: row-reverse !important;
    }
  </style>
  <div class="paper-logo-container">
    <img src="<?=URLROOT?>/assets/images/logo.png" alt="" class="paper-logo">
    <div>
      <h1 style=" text-transform: uppercase; line-height: 10px;">MGS School System</h1>
      <p style="font-size: 15px; line-height: 10px;">Location of the school address is here</p>
      <p style="font-size: 15px; line-height: 10px;">0301-4971904 041-45789379</p>
    </div>
  </div>

  <div class="paper-header">

    <div class="paper-header-left" style="min-width: 350px">
      <div class="paper-header-item">
        <div class="paper-header-item-bold">Name</div>
        <div class="paper-header-item-underline"></div>
      </div>

      <div class="paper-header-item">
        <div class="paper-header-item-bold">Class</div>
        <div class="paper-header-item-underline"><?=$data['class']->class_name?></div>
      </div>

      <div class="paper-header-item">
        <div class="paper-header-item-bold">Subject</div>
        <div class="paper-header-item-underline"><?=$data['subject']->subject_name?></div>
      </div>
    </div>

    <div class="paper-header-right" style="min-width: 350px">
      <div class="paper-header-item">
        <div class="paper-header-item-bold">Teacher Name</div>
        <div class="paper-header-item-underline"><!-- Teacher Name Goes here --> </div>
      </div>

      <div class="paper-header-item">
        <div class="paper-header-item-bold">Date</div>
        <div class="paper-header-item-underline"> <?= date('d-m-Y') ?></div>
      </div>

      <div class="paper-header-item">
        <div class="paper-header-item-bold">Signature</div>
        <div class="paper-header-item-underline"></div>
      </div>
    </div>


  </div>

  <div class="paper-body">
    <h1 class="heading">Objective Part</h1>
    <div class="paper-body-objective">
      <?php for($i=0; $i < sizeof($data['objective']); $i++ ) : ?>
        <div class="paper-question-box">
          <p class="paper-objective-question"><span class="question-number"><?=$i+1?> )</span> <?=$data['objective'][$i]['question']->question?></p>
          <div class="paper-objective-question-options">
            <?php for($j=0; $j < sizeof($data['objective'][$i]['question']->answers_options); $j++) : ?>
              <p><span class="question-number">(<?=$j+1?>)</span>  <?=$data['objective'][$i]['question']->answers_options[$j]->answer_option?></p>
            <?php endfor; ?>
          </div>
        </div>
      <?php endfor; ?>
    </div>
  </div>
</div>


<script>
  window.addEventListener("load", function (){
    console.log("Print is working");
    ``
    let body = document.querySelector('body').innerHTML;

    let print = document.getElementById('paper-print-formate').innerHTML;

    document.querySelector('body').innerHTML = print;

    // setTimeout(window.print, 2000);
    window.print();
    document.querySelector('body').innerHTML = body;
  });
</script>
