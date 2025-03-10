<section role="main" class="content-body">
  <!-- <header class="page-header">
    <h2> Student Panel</h2>
  </header> -->
  <!-- INCLUDEING PAGE -->
  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-12">
            <section class="panel panel-featured panel-featured-primary" >
                <form action="<?= URLROOT ?>/subjects/update/<?=$data['id']?>" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="novalidate">
                    <header class="panel-heading">
                        <h4 class="panel-title">Update Subject</h4>
                    </header>

                    <div class="panel-body">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Subject Name<span class="required" aria-required="true">*</span></label>
                                    <input type="text" class="form-control" value="<?=$data['subject']->name?>" name="subject_name" id="subject_name" required="" title="Must Be Required" aria-required="true">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Class <span class="required" aria-required="true">*</span></label>
                                    <select class="form-control select2-hidden-accessible" required="" title="Must Be Required " data-plugin-selecttwo="" data-width="100% " data-minimum-results-for-search="Infinity " name="class_id" aria-required="true" tabindex="-1" aria-hidden="true">
                                        <?php foreach ($data['classes'] as  $class) :  ?>
                                            <option value="<?= $class['class_id'] ?>" <?=$class->class_id == $data['subject']->class_id ? "selected" : "";?> ><?= $class['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <?php //foreach ($data['paper_types'] as  $paper_type) :  ?>

                          <?php foreach ($data['subject_term_marks'] as $subject_term_mark): ?>
                            <?php // if ($subject_term_mark?->id_paper_type == $paper_type->paper_type_id) : ?>
                              <div class="row" style="margin-top: 20px">

                                  <div class="col-md-3">
                                      <div class="form-group">
                                        <label class="control-label">Paper Type <span class="required" aria-required="true">*</span></label>
                                        <input type="hidden" name="paper_type[]"  value="<?= $subject_term_mark['parent']['paper_type_id'] ?>">
                                        <input type="hidden" name="record_id[]"  value="<?= $subject_term_mark['child']['id'] ?? -1; ?>">
                                        <input type="text" class="form-control" value="<?= $subject_term_mark['parent']['paper_type'] ?>" disabled >
                                      </div>
                                  </div>

                                  <div class="col-md-3">
                                      <div class="form-group">
                                        <label class="control-label">No. of Objective Questions<span class="required" aria-required="true">*</span></label>
                                        <input class="form-control" name="no_of_objective[]" value="<?=$subject_term_mark['child']->no_of_objective ?? 0 ?>" type="number" min="1">
                                      </div>
                                  </div>

                                  <div class="col-md-3">
                                      <div class="form-group">
                                          <label class="control-label">No. of Subjective Questions<span class="required" aria-required="true">*</span></label>
                                          <input class="form-control" name="no_of_subjective[]" value="<?=$subject_term_mark['child']->no_of_subjective / 5?? 0 ?>" type="number"  min="1">
                                      </div>
                                  </div>

                                  <div class="col-md-3">
                                      <div class="form-group">
                                          <label class="control-label">Total Marks<span class="required" aria-required="true">*</span></label>
                                          <input class="form-control" type="number" value="<?=$subject_term_mark['child']->max_numbers ?? 0 ?>"  disabled>
                                      </div>
                                  </div>
                              </div>
                            <?php // endif;?>
                          <?php endforeach; ?>

                        <?php //endforeach; ?>
                    </div>

                    <footer class="panel-footer">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button class="btn btn-default modal-dismiss">Cancel</button>
                                <button type="submit " id="submit_student " name="submit_student " class="mr-xs btn btn-primary ">Update Subject</button>
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