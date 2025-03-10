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
                <form action="<?= URLROOT ?>/subjects/create" class="mb-lg validate"  method="post" accept-charset="utf-8" novalidate="novalidate">
                    <header class="panel-heading">
                        <h4 class="panel-title">Add Subject Marks</h4>
                    </header>

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Subject Name<span class="required" aria-required="true">*</span></label>
                                    <input type="text" class="form-control" name="subject_name" id="subject_name" required="" title="Must Be Required" aria-required="true">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Class <span class="required" aria-required="true">*</span></label>
                                    <select class="form-control select2-hidden-accessible" required="" title="Must Be Required " data-plugin-selecttwo="" data-width="100% " data-minimum-results-for-search="Infinity " name="class_id" aria-required="true" tabindex="-1" aria-hidden="true">
                                        <?php foreach ($data['classes'] as  $class) :  ?>
                                            <option value="<?= $class['class_id'] ?>"><?= $class['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <?php foreach ($data['paper_types'] as  $paper_type) :  ?>
                            <div class="row" style="margin-top: 20px">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Paper Type <span class="required" aria-required="true">*</span></label>
                                        <input type="hidden" name="paper_type[]"  value="<?= $paper_type['paper_type_id'] ?>">
                                        <input type="text" class="form-control" value="<?= $paper_type['paper_type'] ?>" disabled >
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">No. of Objective Questions<span class="required" aria-required="true">*</span></label>
                                        <input class="form-control" name="no_of_objective[]" type="number" min="1">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">No. of Subjective Questions<span class="required" aria-required="true">*</span></label>
                                        <input class="form-control" name="no_of_subjective[]" type="number"  min="1">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Total Marks<span class="required" aria-required="true">*</span></label>
                                        <input class="form-control" type="number"  disabled>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <footer class="panel-footer">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button class="btn btn-default modal-dismiss">Cancel</button>
                                <button type="submit " id="submit_student " name="submit_student " class="mr-xs btn btn-primary ">Add Subject</button>
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