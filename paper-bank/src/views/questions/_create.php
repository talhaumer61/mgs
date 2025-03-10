<section role="main" class="content-body">
  <header class="page-header">
    <h2>Questions Panel</h2>
  </header>
  <!-- INCLUDEING PAGE -->
  <div class="row">
    <div class="col-md-12">
      <section class="panel panel-featured panel-featured-primary" >
        <form action="<?= URLROOT ?>/questions/create" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="novalidate">

          <div class="panel-heading">
            <h4 class="panel-title"><i class="fa fa-plus-square"></i>&nbsp;&nbspAdd New Question</h4>
          </div>

          <div class="panel-body">

            <div class="row">
              <div class="col-md-3">
                <label class="control-label">Class
                  <span class="required">*</span></label>
                <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" onchange="get_class_subjects(this.value)"  name="class_id" >
                  <option value="">Select</option>
                  <?php foreach ($data['classes'] as $class) : ?>
                    <option value=<?= $class['class_id'] ?>><?= $class['class_name'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-3">
                <label class="control-label">Subject
                  <span class="required">*</span></label>
                <select class="form-control" id="class_subjects" onchange="get_subject_chapters(this.value)" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="subject_id" >
                  <option value="">Select Class First</option>
                </select>
              </div>

              <div class="col-md-3">
                <label class="control-label">Chapter
                  <span class="required">*</span></label>
                <select class="form-control" id="subject_chapters" required title="Must Be Required" data-plugin-selectTwo data-width="100%"  name="chapter_id" >
                  <option value="">Select Subject First</option>
                </select>
              </div>

              <div class="col-md-3">
                <label class="control-label">Type
                  <span class="required">*</span></label>
                <select class="form-control" onchange="show_options_tab(this.value)" required title="Must Be Required" data-plugin-selectTwo data-width="100%"  name="type" >
                  <option value="">Select</option>
                  <?php foreach ($data['questionType'] as $questionType) : ?>
                    <option value="<?= $questionType['id'] ?>"><?= $questionType['name'] ?></option>
                  <?php endforeach; ?>
                  <!-- <option value="subjective">Subjective</option>
                  <option value="objective">Objective</option> -->
                </select>
              </div>
            </div>

            <div class="row" style="margin-bottom: 20px; margin-top: 20px">
              <div class="col-md-3">
                <label class="control-label">Question Type <span class="required">*</span></label>
                <select class="form-control" disabled id="question_subjective_type" onchange="show_has_sub(this)" required title="Must Be Required"  data-width="100%"  name="question_subjective_type" >
                  <option value="">Select</option>
                  <?php foreach ($data['qType'] as $qType) : ?>
                    <option value="<?= $qType['id'] ?>"><?= $qType['name'] ?></option>
                  <?php endforeach; ?>
                  
                </select>
              </div>

              <div class="col-md-3">
                <label class="control-label">Marks <span class="required">*</span></label>
                <input type="number" id="marks" min="1" readonly class="form-control" name="marks" required=""  title="Must Be Required" aria-required="true">
              </div>



              <div class="col-md-3">
                <label class="control-label">Has Sub Questions <span class="required">*</span></label>
                <select class="form-control" disabled id="has_sub" onchange="show_no_of_parts(this)" required title="Must Be Required"  data-width="100%"  name="has_sub" >
                  <option value="">Select</option>
                  <?php foreach ($data['Yesno'] as $key=> $yesno) : ?>
                    <option value="<?= $yesno['id'] ?>"><?= $yesno['name'] ?></option>
                  <?php endforeach; ?>
                  
                </select>
              </div>

              <div class="col-md-3">
                <label class="control-label">No. of Sub-questions. <span class="required">*</span></label>
                <input type="number" id="no_of_subjective_parts"  disabled class="form-control" name="no_of_subjective_parts" value="0" aria-required="true">
              </div>

               <div class="col-md-3">
                  <div class="form-group">
                   <label class="control-label">Status <span class="required">*</span></label>
                  <div >
                     <div class="radio-custom radio-inline" style="margin: 0">
                       <input type="radio" id="status" name="status" value="1" checked>
                       <label for="radioExample1">Active</label>
                     </div>
                     <div class="radio-custom radio-inline" style="margin: 0; margin-left: 16px">
                       <input type="radio" id="status" name="status" value="2">
                       <label for="radioExample2">Inactive</label>
                     </div>
                   </div>
                 </div>
               </div>
            </div>


            <div id="question-block-container">
              <div class="row" id="question-block" style="margin-bottom: 30px;">
                <div class="col-md-3">
                  <label class="control-label">No. of Lines <span class="required">*</span></label>
                  <input type="number" id="no_of_lines"   class="form-control" name="no_of_lines[]" required title="Must Be Required" aria-required="true">
                </div>

                <div class="col-md-3">
                  <label class="control-label">Difficulty Level <span class="required">*</span></label>
                  <select class="form-control difficulty_level" name="difficulty_level[]" required title="Must Be Required">
                    <option value="">Select</option>
                    <?php foreach ($data['levels'] as $level) : ?>
                      <option value="<?= $level['id'] ?>"><?= $level['difficulty_level'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="col-md-12" style="margin-top: 10px">
                  <div class="form-group">
                    <label class="control-label">Question <span class="required" aria-required="true">*</span></label>
                    <textarea required title="This field is required "  name="question[]"  class="form-control question" rows="5" style="width: 100%;"></textarea>
                  </div>
                </div>
                <div class="col-md-12" style="margin-top: 10px">
                  <div class="form-group">
                    <label class="control-label">Question Urdu Title <span class="required" aria-required="true">*</span></label>
                    <textarea required title="This field is required"  name="question_urdu[]"  class="form-control question_urdu" rows="5" style="width: 100%;"></textarea>
                  </div>
                </div>
              </div>
            </div>

            <div class="row" style="margin-bottom: 20px; display: none;" id="objective-fields">
              <div class="col-md-2">
                <label class="control-label">Option A<span class="required">*</span></label>
                <input type="text" class="form-control" name="options[]" required="" disabled title="Must Be Required" aria-required="true">
              </div>
              <div class="col-md-2">
                <label class="control-label">Option B<span class="required">*</span></label>
                <input type="text" class="form-control" name="options[]" required="" title="Must Be Required" aria-required="true">
              </div>
              <div class="col-md-2">
                <label class="control-label">Option C<span class="required">*</span></label>
                <input type="text" class="form-control" name="options[]" required="" title="Must Be Required" aria-required="true">
              </div>
              <div class="col-md-2">
                <label class="control-label">Option D<span class="required">*</span></label>
                <input type="text" class="form-control" name="options[]" required="" title="Must Be Required" aria-required="true">
              </div>
              <div class="col-md-2">
                <label class="control-label">Correct Option<span class="required">*</span></label>
                <select class="form-control" name="correct_option" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="type" required title="Must Be Required">
                  <option value="-1">Select</option>
                  <option value="0">Option A</option>
                  <option value="1">Option B</option>
                  <option value="2">Option C</option>
                  <option value="3">Option D</option>
                </select>
              </div>
            </div>

          </div>


          <footer class="panel-footer mt-sm ">
            <div class="row ">
              <div class="col-md-12 text-right ">
                <a href="<?=route("questions")?>" class="btn btn-default">Cancel</a>
                <button type="submit " id="submit_student " name="submit_student " class="mr-xs btn btn-primary ">
                  Add Question
                </button>
              </div>
            </div>
          </footer>


        </form>
      </section>
    </div>
  </div>

</section>

<?php 
echo'
<script>
  var QuestionTypeArray = {';
    foreach ($data['qType'] as $qType) {
      echo '"'.$qType['id'].'":"'.$qType['name'].'",';
    }
  echo'};
</script>';
?>