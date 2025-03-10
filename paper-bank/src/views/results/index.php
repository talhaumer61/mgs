<section role="main" class="content-body">
  <header class="page-header">
    <h2> Print Result</h2>
  </header>
  <!-- INCLUDEING PAGE -->
  <div class="row">
    <div class="col-md-12">
      <section class="panel panel-featured panel-featured-primary">
        <form action="<?= URLROOT ?>/result/print" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
          <header class="panel-heading">
            <h2 class="panel-title"><i class="fa fa-list"></i> Select Class</h2>
          </header>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group mb-md">
                  <label class="control-label">Class <span class="required">*</span></label>
                  <select class="form-control" onchange="getClassPapers(this)" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_class">
                    <option value="">Select</option>
                    <?php foreach ($data['classes'] as $class) : ?>
                      <option value=<?= $class['class_id'] ?> <?= isset($data['class_id']) && $class['class_id'] == $data['class_id'] ? "selected" : ""; ?>><?= $class['name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-md">
                  <label class="control-label">Paper Terms<span class="required">*</span></label>
                  <select class="form-control"  required title="Must Be Required" data-plugin-selectTwo data-width="100%" name='id_paper_type'>
                    <option value="">Select</option>
                    <?php foreach ($data['paper_types'] as $paperType) : ?>
                      <option value=<?= $paperType['paper_type_id'] ?> <?= isset($data['paper_type_id']) && $paperType['paper_type_id'] == $data['paper_type_id'] ? "selected" : ""; ?>><?= $paperType['paper_type'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-12 text-center">
              <button type="submit" id="show_students" name="show_students" class="mr-xs btn btn-primary">Print Result</button>
            </div>
          </div>
        </form>
      </section>