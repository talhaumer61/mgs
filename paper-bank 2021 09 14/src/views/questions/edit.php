<section role="main" class="content-body">
  <header class="page-header">
    <h2>Questions Panel</h2>
  </header>
  <!-- INCLUDEING PAGE -->
  <div class="row">
    <div class="col-md-12">
      <section class="panel panel-featured panel-featured-primary" >
        <form action="<?= URLROOT ?>/questions/update/<?=$data['question_id']?>" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="novalidate">

          <div class="panel-heading">
            <h4 class="panel-title"><i class="fa fa-plus-square"></i>&nbsp; Update Question</h4>
          </div>

          <?php
            $answers_options = $data['question']->answers_options;
          ?>

          <div class="panel-body">
            <input type="hidden" name="type" value="<?=$data['question']->is_objective ? 'objective' : 'subjective';?>">

            <div id="question-block-container">
              <div class="row" id="question-block" style="margin-bottom: 30px;">
                <?php if(!$data['show_no_lines'] && !$data['question']->is_objective):?>
                <div class="col-md-3">
                  <label class="control-label">No. of Lines <span class="required">*</span></label>
                  <input type="number" id="no_of_lines"   value="<?=$data['question']->no_of_lines?>" class="form-control" name="no_of_lines" required title="Must Be Required" aria-required="true">
                </div>
                <?php elseif($data['question']->is_objective):?>

                <?php else:?>
                  <div class="col-md-3">
                    <h5>Since this is a parent question you can't define no of lines</h5>
                  </div>
                <?php endif;?>

                <?php if(!$data['show_no_lines'] && !$data['question']->is_objective):?>
                  <div class="col-md-3">
                    <label class="control-label">Difficulty Level <span class="required">*</span></label>
                    <select class="form-control difficulty_level" name="difficulty_level" required title="Must Be Required">
                      <option value="">Select</option>
                      <?php foreach ($data['levels'] as $level) : ?>
                        <option value="<?= $level['id'] ?>" <?= $data['question']->difficulty_level == $level['id'] ? 'selected' : ""  ?>  ><?= $level['difficulty_level'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                <?php elseif($data['question']->is_objective):?>

                <?php else:?>
                  <div class="col-md-3">
                    <h5>Since this is a parent question you can't define Difficulty Level</h5>
                  </div>
                <?php endif;?>

                <div class="col-md-3">
                  <div class="form-group">
                      <label class="control-label">Status <span class="required">*</span></label>
                    <div >
                      <div class="radio-custom radio-inline" style="margin: 0">
                        <input type="radio" id="status" name="status" value="1" <?=($data['question']->question_status == 1 ) ? 'checked' : '';?> >
                        <label for="radioExample1">Active</label>
                      </div>
                      <div class="radio-custom radio-inline" style="margin: 0; margin-left: 16px">
                        <input type="radio" id="status" name="status" value="2" <?=($data['question']->question_status == 2 ) ? 'checked' : '';?>>
                        <label for="radioExample2">Inactive</label>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- <div class="col-md-3">
                  <div class="form-check" style="margin-top: 30px">
                    <input class="form-check-input" type="checkbox" name="is_ltr" <?= $data['question']->dir_ltr ? 'checked' : "";?> >
                    <label class="form-check-label" for="flexCheckDefault">
                      Is Question in Urdu?
                    </label>
                  </div>
                </div> -->

                <div class="col-md-12" style="margin-top: 10px">
                  <div class="form-group">
                    <label class="control-label">Question <span class="required" aria-required="true">*</span></label>
                    <textarea required title="This field is required"  name="question" data-plugin-summernote class="form-control summernote summernoteEx"  rows="5" style="width: 100%;"><?=$data['question']->question?></textarea>
                  </div>
                </div>
                <div class="col-md-12" style="margin-top: 10px">
                  <div class="form-group">
                    <label class="control-label">Question Urdu Title <span class="required" aria-required="true">*</span></label>
                    <textarea required title="This field is required"  name="question_urdu" data-plugin-summernote class="form-control summernote summernoteEx" rows="5" style="width: 100%;"><?=$data['question']->question_urdu?></textarea>
                  </div>
                </div>
              </div>
            </div>

            <div class="row" style="margin-bottom: 20px; display: <?=$data['question']->is_objective ? 'block' : 'none';?>;" id="objective-fields">
              <div class="col-md-2">
                <label class="control-label">Option A<span class="required">*</span></label>
                <input type="hidden" value="<?= $answers_options[0]->answer_id?>" name="option_ids[]">
                <input type="text" value="<?= $answers_options[0]->answer_option?>" class="form-control" name="options[]" required=""  title="Must Be Required" aria-required="true">
              </div>
              <div class="col-md-2">
                <label class="control-label">Option B<span class="required">*</span></label>
                <input type="hidden" value="<?= $answers_options[1]->answer_id?>" name="option_ids[]">
                <input type="text" value="<?= $answers_options[1]->answer_option?>" class="form-control" name="options[]" required="" title="Must Be Required" aria-required="true">
              </div>
              <div class="col-md-2">
                <label class="control-label">Option C<span class="required">*</span></label>
                <input type="hidden" value="<?= $answers_options[2]->answer_id?>" name="option_ids[]">
                <input type="text" value="<?= $answers_options[2]->answer_option?>" class="form-control" name="options[]" required="" title="Must Be Required" aria-required="true">
              </div>
              <div class="col-md-2">
                <label class="control-label">Option D<span class="required">*</span></label>
                <input type="hidden" value="<?= $answers_options[3]->answer_id?>" name="option_ids[]">
                <input type="text" value="<?= $answers_options[3]->answer_option?>" class="form-control" name="options[]" required="" title="Must Be Required" aria-required="true">
              </div>
              <div class="col-md-2">
                <label class="control-label">Correct Option<span class="required">*</span></label>
                <select class="form-control" name="correct_option" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="type" required title="Must Be Required">
                  <option value="-1">Select</option>
                  <option <?= $answers_options[0]->is_correct ? 'selected' : ''?> value="0">Option A</option>
                  <option <?= $answers_options[1]->is_correct ? 'selected' : ''?> value="1">Option B</option>
                  <option <?= $answers_options[2]->is_correct ? 'selected' : ''?> value="2">Option C</option>
                  <option <?= $answers_options[3]->is_correct ? 'selected' : ''?> value="3">Option D</option>
                </select>
              </div>
            </div>


          </div>

          <footer class="panel-footer mt-sm ">
            <div class="row ">
              <div class="col-md-12 text-right ">
                <a href="<?=route("questions")?>" class="btn btn-default">Cancel</a>
                <button type="submit " id="submit_student " name="submit_student " class="mr-xs btn btn-primary ">
                  Update Question
                </button>
              </div>
            </div>
          </footer>
        </form>
      </section>
    </div>
  </div>
</section>