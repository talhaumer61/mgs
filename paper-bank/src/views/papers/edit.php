<section role="main" class="content-body">
  <header class="page-header">
    <h2>Generate Paper</h2>
  </header>
  <!-- INCLUDEING PAGE -->
  
  <div class="row">
    <div class="col-md-12">
    <section class="panel panel-featured panel-featured-primary">
      <form action="<?= URLROOT ?>/papers/generate/<?=$data['paper_id']?>" class="mb-lg validate" id="paper-generate-form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
        <header class="panel-heading" style="display: flex; justify-content: space-between; align-items: center">
          <h2 class="panel-title"><i class="fa fa-list"></i> Generate Papers</h2>
        </header>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-6">
              <div class="row" style="margin-bottom: 10px;">
                <div class="col-md-4">
                  <label class="control-label pull-right">Id <span class="required">*</span> :</label>
                </div>
                <div class="col-md-8">
                  <input type="number" value="<?php if(isset($data['paper_id'])){ echo $data['paper_id'];}else{ echo'';}?>" disabled class="form-control" placeholder="Enter ID" name="id" id="id" required="" title="Must Be Required" aria-required="true">
                </div>
              </div>

              <div class="row" style="margin-bottom: 10px;">
                <div class="col-md-4">
                  <label class="control-label pull-right">Exam Type <span class="required">*</span> :</label>
                </div>
                <div class="col-md-8">
                  <select class="form-control" id="paper_type"  required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="paper_type" >
                    <option value="-1">Please Select</option>
                    <?php foreach ($data['paper_type'] as $paperType): ?>
                      <option value=<?=$paperType->type_id?> <?=isset($data['paper']['id_exam_type']) && $data['paper']['id_exam_type'] == $paperType->type_id ? "selected" : '';?>><?=$paperType->type_name?></option>
                    <?php endforeach;?>
                  </select>
                </div>
              </div>

              <div class="row" style="margin-bottom: 10px;">
                <div class="col-md-4">
                  <label class="control-label pull-right">Class <span class="required">*</span> :</label>
                </div>
                <div class="col-md-8">
                  <select class="form-control" onchange="get_class_subjects(this.value)" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="class_id" required title="Must Be Required">
                    <option value="">Please Select</option>
                    <?php foreach ($data['classes'] as $class) : ?>
                      <option value=<?= $class->class_id ?> <?=isset($data['paper']['id_class']) && $data['paper']['id_class'] == $class->class_id ? "selected" : '';?>><?= $class->class_name ?> </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="row" style="margin-bottom: 10px;">
                <div class="col-md-4">
                  <label class="control-label pull-right">Subject <span class="required">*</span> :</label>
                </div>
                <div class="col-md-8">
                  <select class="form-control" name="id_subject"  onchange="get_subject_chapters_fltr(this.value)"  data-plugin-selectTwo id="class_subjects" data-width="100%" required title="Must Be Required" >
                    <option value="">Please Select</option>
                    <?php  foreach ($data['class_subjects'] as $class_subjects) : ?>
                    <option value="<?=$class_subjects['subject_id']?>" <?=isset($data['paper']['id_subject']) && $data['paper']['id_subject'] == $class_subjects['subject_id'] ? 'selected' : "";?>><?=$class_subjects['subject_name']?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="row" style="margin-bottom: 10px;">
                <div class="col-md-4">
                  <label class="control-label pull-right">From Chapter <span class="required">*</span> :</label>
                </div>
                <div class="col-md-8">
                  <select class="form-control" name="id_chapter"  data-plugin-selectTwo data-width="100%"  id="subject_chapters">
                    <option value="">Please Select</option>
                    <?php  foreach ($data['subject_chapters'] as $subject_chaptes) : ?>
                      <option value="<?=$subject_chaptes['chapter_id']?>" <?=isset($data['paper']['id_chapter_from']) && $data['paper']['id_chapter_from'] == $subject_chaptes['chapter_id'] ? 'selected' : "";?>><?=$subject_chaptes['chapter_name']?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="row" style="margin-bottom: 10px;">
                <div class="col-md-4">
                  <label class="control-label pull-right">To Chapter <span class="required">*</span> :</label>
                </div>
                <div class="col-md-8">
                  <select class="form-control" name="id_chapter_to"  data-plugin-selectTwo data-width="100%"  id="subject_chapters_to">
                    <option value="">Please Select</option>
                    <?php  foreach ($data['subject_chapters'] as $subject_chaptes) : ?>
                      <option value="<?=$subject_chaptes['chapter_id']?>" <?=isset($data['paper']['id_chapter_to']) && $data['paper']['id_chapter_to'] == $subject_chaptes['chapter_id'] ? 'selected' : "";?>><?=$subject_chaptes['chapter_name']?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="row" style="margin-bottom: 10px;">
                <div class="col-md-4">
                  <label class="control-label pull-right">Paper Style <span class="required">*</span> :</label>
                </div>
                <div class="col-md-8">
                <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="paper_style" required title="Must Be Required">
                    <option value="">Please Select</option>
                    <?php foreach ($data['paperStyles'] as $style) : ?>
                      <option value=<?= $style->paper_style_id ?> <?=isset($data['paper']['id_paper_style']) && $data['paper']['id_paper_style'] == $style->paper_style_id ? 'selected' : "";?>><?= $style->paper_style_name ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="row" style="margin-bottom: 10px;">
                <div class="col-md-4">
                  <label class="control-label pull-right">Paper Time <span class="required">*</span> :</label>
                </div>
                <div class="col-md-8">
                  <input type="number" class="form-control" placeholder="Enter Paper Time in Minutes" name="paper_time" id="paper_time" value="<?=isset($data['paper']['paper_time']) ? $data['paper']['paper_time'] : ''?>" required="" title="Must Be Required" aria-required="true">
                </div>
              </div>

            </div>

            
            <div class="col-md-6">
              <div class="row" style="margin-bottom: 10px;">
                <div class="col-md-6">
                  <label class="control-label pull-right">No Of MCQ's <span class="required">*</span> :</label>
                </div>
                <div class="col-md-6">
                  <input type="number" class="form-control" placeholder="Enter number of Mcqs" name="no_mcqs" id="no_mcqs" required="" value="<?=isset($data['paper']['no_mcqs']) ? $data['paper']['no_mcqs'] : ''?>" title="Must Be Required" aria-required="true">
                </div>
              </div>

              <div class="row" style="margin-bottom: 10px;">
                <div class="col-md-6">
                  <label class="control-label pull-right">Marks Per MCQ <span class="required">*</span> :</label>
                </div>
                <div class="col-md-6">
                  <input type="number" class="form-control" placeholder="Enter marks for a Mcq" name="marks_mcq" id="marks_mcq" required="" value="<?=isset($data['paper']['marks_mcq']) ? $data['paper']['marks_mcq'] : ''?>" title="Must Be Required" aria-required="true">
                </div>
              </div>

              <div class="row" style="margin-bottom: 10px;">
                <div class="col-md-6">
                  <label class="control-label pull-right">No Of Short Questions <span class="required">*</span> :</label>
                </div>
                <div class="col-md-6">
                  <input type="number" class="form-control" placeholder="Enter number of Short Questions" name="no_short_questions" value="<?=isset($data['paper']['no_short_question']) ? $data['paper']['no_short_question'] : ''?>" id="no_short_questions" required="" title="Must Be Required" aria-required="true">
                </div>
              </div>

              <div class="row" style="margin-bottom: 10px;">
                <div class="col-md-6">
                  <label class="control-label pull-right">Marks Per Short Questions <span class="required">*</span> :</label>
                </div>
                <div class="col-md-6">
                  <input type="number" class="form-control" placeholder="Enter marks for a Short Question" name="marks_short_question" value="<?=isset($data['paper']['marks_short_question']) ? $data['paper']['marks_short_question'] : ''?>" id="marks_short_question" required="" title="Must Be Required" aria-required="true">
                </div>
              </div>

              <div class="row" style="margin-bottom: 10px;">
                <div class="col-md-6">
                  <label class="control-label pull-right">No Of Long Questions <span class="required">*</span> :</label>
                </div>
                <div class="col-md-6">
                  <input type="number" value="<?=isset($data['paper']['no_long_question']) ? $data['paper']['no_long_question'] : ''?>" class="form-control"  placeholder="Enter number of Long Question" name="no_long_questions" id="no_long_questions" required="" title="Must Be Required" aria-required="true">
                </div>
              </div>

              <div class="row" style="margin-bottom: 10px;">
                <div class="col-md-6">
                  <label class="control-label pull-right">Marks Per Long Question <span class="required">*</span> :</label>
                </div>
                <div class="col-md-6">
                  <input type="number" value="<?=isset($data['paper']['marks_long_question']) ? $data['paper']['marks_long_question'] : ''?>" class="form-control" placeholder="Enter marks for a Long Question" name="marks_long_question" id="marks_long_question" required="" title="Must Be Required" aria-required="true">
                </div>
              </div>

              <div class="row" style="margin-bottom: 10px;">
                <div class="col-md-6">
                  <label class="control-label pull-right">No Of Lines For Short Question <span class="required">*</span> :</label>
                </div>
                <div class="col-md-6">
                  <input type="number" class="form-control"  placeholder="Enter number of Lines for Short Question" name="lines_short_question" value="<?=isset($data['paper']['no_lines_short_question']) ? $data['paper']['no_lines_short_question'] : ''?>" id="lines_short_question" required="" title="Must Be Required" aria-required="true">
                </div>
              </div>

              <div class="row" style="margin-bottom: 10px;">
                <div class="col-md-6">
                  <label class="control-label pull-right">No Of Lines For Long Question <span class="required">*</span> :</label>
                </div>
                <div class="col-md-6">
                  <input type="number" class="form-control" placeholder="Enter number of Lines for Long Question" name="lines_long_question" value="<?=isset($data['paper']['no_lines_long_question']) ? $data['paper']['no_lines_long_question'] : ''?>" id="lines_long_question" required="" title="Must Be Required" aria-required="true">
                </div>
              </div>

            </div>
          </div>
          


          <div class="col-md-12 text-center">
            <button type="submit" id="generate_paper" name="show_students" class="mr-xs btn btn-primary">Generate Paper</button>
            <a href="<?=route("dashboard")?>" class="mr-xs btn btn-info">Home</a>
          </div>
        </div>
      </form>
    </section>

    
