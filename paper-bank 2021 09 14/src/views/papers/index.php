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
                    <strong class="amount" id="marks_of_objective">0</strong>
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
                    <strong class="amount" id="marks_of_subjective">0</strong>
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
                    <strong class="amount" id="total_marks">0</strong>
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
      <form action="<?= URLROOT ?>/papers/generate" class="mb-lg validate" id="paper-generate-form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
        <header class="panel-heading" style="display: flex; justify-content: space-between; align-items: center">
          <h2 class="panel-title"><i class="fa fa-list"></i> Generate Papers</h2>
        </header>
        <div class="panel-body">
          <div class="row" style="margin-bottom: 20px;">
            <div class="col-md-4">
              <label class="control-label">Class <span class="required">*</span></label>
              <select class="form-control" onchange="get_class_subjects(this.value)" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="class_id" required title="Must Be Required">
                <option value="">Select</option>
                <?php foreach ($data['classes'] as $class) : ?>
                  <option value=<?= $class->class_id ?>><?= $class->class_name ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-4">
              <label class="control-label">Subject <span class="required">*</span></label>
              <select class="form-control" id="class_subjects" onchange="get_subject_chapters_list(this.value)" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="subject_id" required title="Must Be Required">
                <option value="">Select Class First</option>
              </select>
            </div>

            <div class="col-md-4">
              <label class="control-label">Exam Type <span class="required">*</span></label>
              <select class="form-control" id="paper_type" onchange="get_max_marks(this)" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="paper_type" >
                <option value="-1">Select</option>
                <?php foreach ($data['paper_type'] as $paperType): ?>
                  <option value=<?=$paperType->type_id?>><?=$paperType->type_name?></option>
                <?php endforeach;?>
              </select>
            </div>

          </div>

          <div class="row" id="chapters-rows" style="margin-bottom: 25px;">

          </div>

          <div class="col-md-12 text-center">
            <button type="submit" id="generate_paper" name="show_students" class="mr-xs btn btn-primary">Generate Paper</button>
          </div>
        </div>
      </form>
    </section>

    <section class="panel panel-featured panel-featured-primary">
      <header class="panel-heading">
        <h2 class="panel-title"><i class="fa fa-list"></i> Papers List</h2>
      </header>

      <div class="panel-body" style="width: 100%;">
        <table class="table table_default table-bordered table-striped table-condensed mb-none" id="table_export">
          <thead>
            <tr>
              <th class="text-center">Sr #</th>
              <th>Class Name</th>
              <th>Subject</th>
              <th>Paper Generated</th>
              <th width="200px;" class="center">Options</th>
            </tr>
          </thead>
          <tbody>
            <?php for ($i = 0; $i < sizeof($data['papers']); $i++) : ?>
              <tr>
                  <td class="text-center"><?= $i + 1 ?></td>
                  <td><?= $data['papers'][$i]->class->class_name ?></td>
                  <td><?= $data['papers'][$i]->subject->subject_name?></td>
                  <td><?= $data['papers'][$i]->date_added ?></td>
                <td class=" center ">
                  <a class="btn bg-primary btn-xs ml-xs" data-tippy="Print Paper" target="_blank" href="<?= URLROOT . '/papers/print' . '/' . $data['papers'][$i]->paper_id  ?>"> <i class=" fa fa-copy"></i>
                  </a>
                  <a class="btn bg-primary btn-xs ml-xs" data-tippy="Print Subjective Part" target="_blank" href="<?= URLROOT . '/papers/print_subjective' . '/' . $data['papers'][$i]->paper_id  ?>"> <i class=" fa fa-print"></i>
                  </a>
                  <a class="btn bg-primary btn-xs ml-xs" data-tippy="Print Objective Part" target="_blank" href="<?= URLROOT . '/papers/print_objective' . '/' . $data['papers'][$i]->paper_id  ?>"> <i class=" fa fa-print"></i>
                  </a>
                  <a class="btn btn-success btn-xs ml-xs" href="<?= URLROOT . '/papers/edit' . '/' . $data['papers'][$i]->paper_id  ?>"> <i class=" fa fa-edit"></i>
                  </a>
                  <a class="btn btn-danger btn-xs ml-xs" href="<?= URLROOT . '/papers/delete' . '/' . $data['papers'][$i]->paper_id ?>"> <i class=" fa fa-trash"></i>
                  </a>
                </td>
              </tr>
            <?php endfor; ?>
          </tbody>
        </table>
      </div>
    </section>
    </div>