<section role="main" class="content-body">

  <header class="page-header">
    <h2>Paper Subject Marks</h2>
  </header>

  <!-- INCLUDEING PAGE -->
  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-12">
          <section class="panel panel-featured panel-featured-primary" >
            <form action="<?= URLROOT ?>/subjectmarks/create" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="novalidate">
              <header class="panel-heading">
                <h4 class="panel-title"><i class="fa fa-plus-square"></i>&nbsp; Add Subject Marks</h4>
              </header>

              <div class="panel-body">

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="control-label">Subject <span class="required" aria-required="true">*</span></label>
<!--                      <input type="text" class="form-control" name="subject_name" id="subject_name" required="" title="Must Be Required" aria-required="true">-->
                      <select class="form-control select2-hidden-accessible" required="" title="Must Be Required " data-plugin-selecttwo="" data-width="100% "  name="subject_id" aria-required="true" tabindex="-1" aria-hidden="true">
                        <option value="">Select</option>
                        <?php foreach ($data['subjects'] as  $subject) :  ?>
                          <option value="<?= $subject->subject_id ?>"><?= $subject->subject_name ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="control-label">Exam Term <span class="required" aria-required="true">*</span></label>
                      <select class="form-control select2-hidden-accessible" required="" title="Must Be Required " data-plugin-selecttwo="" data-width="100% "  name="exam_type_id" aria-required="true" tabindex="-1" aria-hidden="true">
                        <option value="">Select</option>
                        <?php foreach ($data['exam_types'] as  $exam) :  ?>
                          <option value="<?= $exam->type_id ?>"><?= $exam->type_name ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="row mb-md" style="margin-top: 20px">

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="control-label">No. of Objective Questions <span class="required" aria-required="true">*</span></label>
                      <input class="form-control" name="no_of_objective" id="no_of_objective" type="number" min="1">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="control-label">No. of Subjective Questions <span class="required" aria-required="true">*</span></label>
                      <input class="form-control" name="no_of_subjective" id="no_of_subjective" type="number"  min="1">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="control-label">Total Marks <span class="required" aria-required="true">*</span></label>
                      <input class="form-control" type="number" name="total_marks" id="total_marks"  disabled>
                    </div>
                  </div>

                </div>

              </div>

              <footer class="panel-footer">
                <div class="row">
                  <div class="col-md-12 text-right">
                    <a href="<?=route("subjectmarks")?>" class="btn btn-default">Cancel</a>
                    <button type="submit " id="submit_student " name="submit_student " class="mr-xs btn btn-primary ">Add Subject Marks</button>
                  </div>
                </div>
              </footer>
            </form>
          </section>
        </div>

      </div>
    </div>
  </div>
</section>