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
        <div class="paper-header-item-underline"><?=$data['class']['class_name']?></div>
      </div>

      <div class="paper-header-item">
        <div class="paper-header-item-bold">Subject</div>
        <div class="paper-header-item-underline"><?=$data['subject']['subject_name']?></div>
      </div>
    </div>

    <div class="paper-header-right" style="min-width: 350px">
      <div class="paper-header-item">
        <div class="paper-header-item-bold" style="min-width: 150px">Teacher Name</div>
        <div class="paper-header-item-underline"><!-- Teacher name goes here --></div>
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
    <div class="paper-body-subjective">
      <h1 class="heading">Subjective Part</h1>
      <div class="paper-question-box">
        <?php for($i=0; $i < sizeof($data['subjective']); $i++) : ?>
          <?php
          $question = $data['subjective'][$i];
          $is_ltr = $question->dir_ltr;
          $class = "";
          if ($is_ltr)
            $class = "flex-reverse";
          ?>
          <!-- Question Block Starts-->
          <div style="margin-bottom: 40px !important;" >
            <div class="paper-subjective-question <?=$class?>">
              <p dir="<?= !$is_ltr ? 'ltr' : 'rtl';  ?>" ><span class="question-number"><?=$i+1?> )</span> <?=$question->question?></p>
              <p class="question-marks">(<?=$question->marks?>)</p>
            </div>
            <?php
            $no_of_lines = $question->no_of_lines ?? 0;
            for ($j = 0; $j < $no_of_lines; $j++ ):?>
              <div style="width: 100%; border-bottom: 1px dashed black; height: 25px; margin: 12px 0;"></div>
            <?php endfor; ?>

            <!-- Sub Question Block Starts-->
            <?php for($j=0; $j < sizeof($question['sub_questions']); $j++) : ?>
              <?php
              $sub_question = $question['sub_questions'][$j];
              $is_ltr = $sub_question->dir_ltr;
              $class = "";
              if ($is_ltr)
                $class = "flex-reverse";
              ?>
              <div style="padding: 0 20px;">
                <div class="paper-subjective-question <?=$class?>" >
                  <p dir="<?= !$is_ltr ? 'ltr' : 'rtl';  ?>" ><span class="question-number"><?= numberToRoman($j+1)?> )</span> <?=$sub_question->question?></p>
                  <p class="question-marks">(<?=$sub_question->marks?>)</p>
                </div>
                <?php
                $no_of_lines = $sub_question->no_of_lines ?? 0;
                for ($z = 0; $z < $no_of_lines; $z++ ):?>
                  <div style="width: 100%; border-bottom: 1px dashed black; height: 25px; margin: 12px 0;"></div>
                <?php endfor; ?>
              </div>
            <?php endfor;?>
            <!-- Sub Question Block Ends-->
          </div>
          <!-- Question Block Ends-->
        <?php endfor; ?>
      </div>
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

