<div class="row mt-sm">
  <div class="col-md-6">
    <label class="control-label">Question English <span class="required" aria-required="true">*</span></label>

    <!-- <textarea class="form-control" name="question_english" id="question_english"></textarea> -->
    <textarea data-plugin-summernote class="form-control summernote summernoteEx" name="question_english" id="question_englishs
    "><?= isset($data['question']['question_english']) ? $data['question']['question_english'] : '' ?></textarea>
  </div>

  <div class="col-md-6">
    <label class="control-label">Question Urdu <span class="required" aria-required="true">*</span></label>
    <textarea data-plugin-summernote class="form-control summernote summernoteEx" name="question_urdu" id="question_urdu"><?= isset($data['question']['question_urdu']) ? $data['question']['question_urdu'] : '' ?></textarea>
  </div>
</div>