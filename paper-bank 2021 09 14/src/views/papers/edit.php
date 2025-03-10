<section role="main" class="content-body">
  <header class="page-header">
      <h2>Generate Paper</h2>
  </header>
  <!-- INCLUDEING PAGE -->

  <div class="row pb-xs">
    <div class="col-md-4">
      <section class="panel panel-featured-left panel-featured-primary p-none">
        <div class="panel-body">
          <div class="widget-summary widget-summary-sm">
            <div class="widget-summary-col widget-summary-col-icon">
              <div class="summary-icon bg-primary">
                <i class="fa fa-check"></i>
              </div>
            </div>
            <div class="widget-summary-col">
              <div class="summary">
                <h4 class="title">Objective Marks</h4>
                <div class="info">
                  <strong class="amount" id="marks_of_objective"><?=$data['objective_marks']?></strong>
                  <span class="text-primary text-uppercase">marks</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <div class="col-md-4">
      <section class="panel panel-featured-left panel-featured-primary p-none">
        <div class="panel-body">
          <div class="widget-summary widget-summary-sm">
            <div class="widget-summary-col widget-summary-col-icon">
              <div class="summary-icon bg-primary">
                <i class="fa fa-check"></i>
              </div>
            </div>
            <div class="widget-summary-col">
              <div class="summary">
                <h4 class="title">Subjective Marks</h4>
                <div class="info">
                  <strong class="amount" id="marks_of_subjective"><?=$data['subjective_marks']?></strong>
                  <span class="text-primary text-uppercase">Marks</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <div class="col-md-4">
      <section class="panel panel-featured-left panel-featured-primary p-none">
        <div class="panel-body">
          <div class="widget-summary widget-summary-sm">
            <div class="widget-summary-col widget-summary-col-icon">
              <div class="summary-icon bg-primary">
                <i class="fa fa-check"></i>
              </div>
            </div>
            <div class="widget-summary-col">
              <div class="summary">
                <h4 class="title">Total</h4>
                <div class="info">
                  <strong class="amount" id="total_marks"><?=$data['total_marks']?></strong>
                  <span class="text-primary text-uppercase">marks</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>

  <div class="row">
        <div class="col-md-12">
            <section class="panel panel-featured panel-featured-primary">
                <form action="<?= URLROOT ?>/papers/generate/<?=$data['paper_id']?>" id="paper-generate-form" class="mb-lg validate"  method="post" accept-charset="utf-8">
                    <header class="panel-heading">
                        <h2 class="panel-title"><i class="fa fa-list"></i> Select Class</h2>
                    </header>
                    <div class="panel-body">
                        <div class="row" style="margin-bottom: 20px;">
                            <div class="col-md-4">
                                <label class="control-label">Class <span class="required">*</span></label>
                                <select class="form-control" onchange="get_class_subjects(this.value)" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="class_id" required title="Must Be Required">
                                    <option value="">Select</option>
                                    <?php foreach ($data['classes'] as $class) : ?>
                                        <option value=<?= $class['class_id'] ?> <?php echo $class->class_id == $data['paper']->id_class ? 'selected' : ''; ?> ><?= $class->class_name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="control-label">Subject <span class="required">*</span></label>
                                <select class="form-control" id="class_subjects" onchange="get_subject_chapters_list(this.value)" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="subject_id" required title="Must Be Required">
                                    <?php foreach ($data['subjects'] as $subject) : ?>
                                        <option value=<?= $subject['subject_id'] ?> <?php echo $subject->subject_id == $data['paper']->id_subject ? 'selected' : ''; ?> ><?= $subject->subject_name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                          <div class="col-md-4">
                            <label class="control-label">Exam Type <span class="required">*</span></label>
                            <select class="form-control" id="paper_type" onchange="get_max_marks(this)" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="paper_type" >
                              <option value="-1">Select</option>
                              <?php foreach ($data['paper_type'] as $paperType): ?>
                                <option value=<?=$paperType->type_id?>  <?= $paperType->type_id == $data['paper']->id_exam_type ? 'selected' : '';?>   > <?=$paperType->type_name?></option>
                              <?php endforeach;?>
                            </select>
                          </div>
                        </div>

                        <div class="row" id="chapters-rows" style="margin-bottom: 25px;">
                            <?php foreach ($data['chapters'] as $chapter) : ?>
                            <div>
                                <div style="margin-bottom: 20px; " class="col-md-6"><label class="control-label">Chapter <span class="required">*</span></label>
                                    <select class="form-control" name="query[chapter_id][]" id="subject_chapters" required="" title="Must Be Required" data-plugin-selecttwo="" data-width="100%" data-minimum-results-for-search="Infinity">
                                        <?php foreach ($data['chapters'] as $chapterInner) : ?>
                                            <option value="<?= $chapterInner->chapter_id ?>"  <?= $chapterInner->chapter_id == $chapter->chapter_id ? 'selected' : ""; ?> ><?php echo $chapterInner->chapter_name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div style="margin-bottom: 20px;" class="col-md-3">
                                    <label class="control-label ">Objective Questions <span class="required">*</span></label>
                                    <input type="text" class="form-control objective_question_marks" name="query[objective_questions][]" id="section_name" required="" value="<?=$data['count'][$chapter->chapter_id]['objective_count']?>"  title="Must Be Required" aria-required="true">
                                </div>

                                <div style="margin-bottom: 20px;" class="col-md-3">
                                    <label class="control-label ">Subjective Questions <span class="required">*</span></label>
                                    <input type="text" class="form-control subjective_question_marks" name="query[subjective_questions][]" id="section_name" required="" value="<?=$data['count'][$chapter->chapter_id]['subjective_count']?>" title="Must Be Required" aria-required="true">
                                </div>
                            </div>
                            <?php endforeach; ?>

                        </div>

                        <div class="col-md-12 text-center">
                            <button type="submit" id="show_students" name="show_students" class="mr-xs btn btn-primary">Generate Paper</button>
                        </div>
                    </div>
                </form>
            </section>