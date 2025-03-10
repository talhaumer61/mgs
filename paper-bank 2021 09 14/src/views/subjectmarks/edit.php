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
            <form action="<?= URLROOT ?>/subjectmarks/update/<?=$data['id']?>" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="novalidate">
              <header class="panel-heading">
                <h4 class="panel-title"><i class=" fa fa-edit"></i>&nbsp;Update Subject Marks</h4>
              </header>

              <div class="panel-body">

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="control-label">Subject <span class="required" aria-required="true">*</span></label>
                      <input type="text" class="form-control" value="<?=$data['subject']->subject_name?>" disabled required="" title="Must Be Required" aria-required="true">
                      <input type="hidden" class="form-control" value="<?=$data['subject']->subject_id?>" name="subject_id" id="subject_id" >
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="control-label">Exam Term <span class="required" aria-required="true">*</span></label>
                      <input type="text" class="form-control" value="<?=$data['exam_type']->type_name?>"  disabled required="" title="Must Be Required" aria-required="true">
                      <input type="hidden" class="form-control" value="<?=$data['exam_type']->type_id?>" name="exam_type_id" id="exam_type_id" >
                    </div>
                  </div>
                </div>

                <div class="row mb-md" style="margin-top: 20px">

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="control-label">No. of Objective Questions <span class="required" aria-required="true">*</span></label>
                      <input class="form-control" name="no_of_objective" id="no_of_objective" type="number" min="1" value="<?=$data['no_of_objective']?>" required title="Field is required">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="control-label">No. of Subjective Questions <span class="required" aria-required="true">*</span></label>
                      <input class="form-control" name="no_of_subjective" id="no_of_subjective" type="number"  min="1" value="<?=$data['no_of_subjective']?>" required title="Field is required">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="control-label">Total Marks <span class="required" aria-required="true">*</span></label>
                      <input class="form-control" id="total_marks" value="<?=$data['total']?>" type="number"  disabled>
                    </div>
                  </div>

                </div>

              </div>

              <footer class="panel-footer">
                <div class="row">
                  <div class="col-md-12 text-right">
                    <a href="<?=route("subjectmarks")?>" class="btn btn-default">Cancel</a>
                    <button type="submit " id="submit_student " name="submit_student " class="mr-xs btn btn-primary ">Update Subject Marks</button>
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