<style>
  .paper{
    display: block;
    width: 100%;
    max-width: 100vw;
    color: black;
    font-family: Arial, serif ;
  }

  @media print {
    @page {
      size: 210mm 297mm;
      margin: 5mm;
    }
  }

  .paper-print{
    background-color: white !important;
  }

  .result-print{
    size: 210mm 297mm;
    min-height: calc(297mm - 10mm);
    max-height: calc(297mm - 10mm);
  }

  .paper-subjective-question > p{
    font-size: 17px;
    color: black;
  }

  .paper-objective-question-options p{
    margin-left: 20px;
  }

  .paper-logo-container{
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 30px;
  }

  .paper-logo{
    width: 100px;
    height: 100px;
  }

  .result-header{
    /*background: red;*/
    display: flex;
    /*justify-content: start;*/
    /*align-items: stretch;*/
    /*gap: 20px;*/
  }

  .student-img{
    width: 100px !important;
    height: 100px !important;
    background-position: center;
    background-size: contain;
    background-repeat: no-repeat;
  }

  .flex{
    display: flex;
    /*align-items: center;*/
    /*justify-content: stretch;*/
    font-weight: 600;
  }

  .flex p {
    border: 1px solid black;
    padding: 10px;
    margin: 0;
  }

  .ml{
    /*margin-left: 2vw;*/
    /*padding-left: 10vw;*/
  }

</style>

<?php for($i = 0; $i < sizeof($data['results']); $i++): ?>
<div class="paper paper-print result-print" id="paper-print-formate">
  <div class="paper-logo-container">
    <img src="<?=URLROOT?>/assets/images/logo.png" alt="" class="paper-logo">
    <div>
      <h1 style="text-transform: uppercase;">MGS School System</h1>
      <p style="font-size: 15px; text-transform: capitalize; margin-top: -15px">This is an address location of your school</p>
    </div>
  </div>

  <div class="panel-body">
    <div class="result-header">
      <table style="width: 100%">
        <tr>
          <td style="width: 100px"><div class="student-img" style="background-image: url('<?= URLROOT ?>/uploads/<?=$data['students'][$i]->image?>');"></div></td>
          <td>
            <table style="width: 100%; height: 100%; border-spacing: 0; border-collapse: collapse" border="1">
              <tbody height="100">
              <tr>
                <td width="10%">Name </td>
                <td class="ml">
                  <b><?=$data['students'][$i]->student_name?></b>
                </td>
                <td width="10%">Name </td>
                <td class="ml">
                  <b><?=$data['students'][$i]->guardian_name?></b>
                </td>
              </tr>
              <tr>
                <td width="10%">Roll number </td>
                <td class="ml">
                  <b><?=$data['students'][$i]->roll_num?></b>
                </td>
                <td width="10%">Name </td>
                <td class="ml">
                  <b><?=$data['students'][$i]->phone?></b>
                </td>
              </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </table>


    </div>

    <div class="result-body">
      <table class="table table-bordered table-hover" style="width: 100%; margin-top: 20px">
        <thead style="background: gray">
        <tr>
          <th style="font-weight:700; text-align:center;vertical-align:middle;">Sr #</th>
          <th style="font-weight:700;vertical-align:middle;">Course Name</th>
          <th style="font-weight:700; text-align:center;">Marks Obtained</th>
        </tr>
        </thead>
        <tbody>
        <?php
          $resultCountable = $data['results'][$i];
          $count = 0;
          $dataJson =  json_decode($resultCountable,true);

          for ($j = 0; $j < sizeof($dataJson['result_items']); $j++):
            $count += $dataJson['result_items'][$j]['obtained_marks'];
            ?>
          <tr style="padding: 10px 0">
            <td style="text-align:center; width:50px;"><?=$j + 1?></td>
            <td><?=$dataJson['result_items'][$j]['subject']['name']?></td>
            <td style="text-align:center; width:100px;"><?=$dataJson['result_items'][$j]['obtained_marks']?></td>
          </tr>
        <?php
          endfor;
          ?>

        </tbody><thead>
        <tr>
          <th colspan="2" style="font-weight:700;"></th>
          <th style="font-weight:700; text-align:center;"><?=$count?></th>
        </tr>
        </thead>

      </table>
    </div>
  </div>
</div>
<?php

endfor;
?>


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
