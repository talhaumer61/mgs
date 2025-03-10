<?php require_once 'question.php'?>

<div class="row" style="margin-bottom: 10px;">
  <div class="col-md-1"></div>
  <div class="col-md-10"><h5 class="center"> <b>English Options</b> </h5></div>
  <div class="col-md-1"></div>
</div>

<div class="row" style="margin-bottom: 20px;" id="objective-fields">
  <div class="col-md-1"></div>
  <div class="col-md-2">
    <label class="control-label">Option A</label>
    <input type="text" class="form-control" value="<?= isset($data['question']->answers_options->e_option_a) ? $data['question']->answers_options->e_option_a : '' ?>" name="e_option_a" id="e_option_a" title="Must Be Required" aria-required="true">
  </div>
  <div class="col-md-2">
    <label class="control-label">Option B</label>
    <input type="text" class="form-control" value="<?= isset($data['question']->answers_options->e_option_b) ? $data['question']->answers_options->e_option_b : '' ?>" name="e_option_b" id="e_option_b" title="Must Be Required" aria-required="true">
  </div>
  <div class="col-md-2">
    <label class="control-label">Option C</label>
    <input type="text" class="form-control" value="<?= isset($data['question']->answers_options->e_option_c) ? $data['question']->answers_options->e_option_c : '' ?>" name="e_option_c" id="e_option_c" title="Must Be Required" aria-required="true">
  </div>
  <div class="col-md-2">
    <label class="control-label">Option D</label>
    <input type="text" class="form-control" value="<?= isset($data['question']->answers_options->e_option_d) ? $data['question']->answers_options->e_option_d : '' ?>" name="e_option_d" id="e_option_d" title="Must Be Required" aria-required="true">
  </div>
  <div class="col-md-2">
    <label class="control-label">Correct Option</label>
    <input type="text" class="form-control" value="<?= isset($data['question']->answers_options->e_option_correct) ? $data['question']->answers_options->e_option_correct : '' ?>" name="e_option_correct" id="e_option_correct" title="Must Be Required" aria-required="true">
  </div>
  <div class="col-md-1"></div>
</div>
<div class="row" style="margin-bottom: 10px;">
  <div class="col-md-1"></div>
  <div class="col-md-10"><h4 class="center"> <b>اُردو آپشنز</b> </h4></div>
  <div class="col-md-1"></div>
</div>

<div class="row" dir="rtl" style="margin-bottom: 20px;" id="objective-fields">
  <div class="col-md-1"></div>
  <div class="col-md-2">
    <label class="control-label"><b>( درست آپشن )</b></label>
    <input type="text" dir="rtl" class="form-control" value="<?= isset($data['question']->answers_options->u_option_correct) ? $data['question']->answers_options->u_option_correct : '' ?>" name="u_option_correct" id="u_option_correct" title="Must Be Required" aria-required="true">
  </div>
  <div class="col-md-2">
    <label class="control-label"><b>( د )</b></label>
    <input type="text" dir="rtl" class="form-control" value="<?= isset($data['question']->answers_options->u_option_d) ? $data['question']->answers_options->u_option_d : '' ?>" name="u_option_d" id="u_option_d" title="Must Be Required" aria-required="true">
  </div>
  <div class="col-md-2">
    <label class="control-label"><b>( ج )</b></label>
    <input type="text" dir="rtl" class="form-control" value="<?= isset($data['question']->answers_options->u_option_c) ? $data['question']->answers_options->u_option_c : '' ?>" name="u_option_c" id="u_option_c" title="Must Be Required" aria-required="true">
  </div>
  <div class="col-md-2">
    <label class="control-label"><b>( ب )</b></label> 
    <input type="text" dir="rtl" class="form-control" value="<?= isset($data['question']->answers_options->u_option_b) ? $data['question']->answers_options->u_option_b : '' ?>" name="u_option_b" id="u_option_b" title="Must Be Required" aria-required="true">
  </div>
  <div class="col-md-2">
    <label class="control-label"><b>( الف)</b></label>
    <input dir="rtl" type="text" value="<?= isset($data['question']->answers_options->u_option_a) ? $data['question']->answers_options->u_option_a : '' ?>" class="form-control" name="u_option_a" id="u_option_a" title="Must Be Required" aria-required="true">
  </div>
  <div class="col-md-1"></div>
</div>