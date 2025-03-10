<section role="main" class="content-body">
  <header class="page-header">
    <h2> Student Marks Panel</h2>
  </header>
  <!-- INCLUDEING PAGE -->
  <div class="row">
    <div class="col-md-12">
      <section class="panel panel-featured panel-featured-primary">
        <form action="<?= URLROOT ?>/studentmarks/get_students_by_class" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
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
              <button type="submit" id="show_students" name="show_students" class="mr-xs btn btn-primary">Show Students</button>
            </div>
          </div>
        </form>
      </section>

      <section class="panel panel-featured panel-featured-primary">
        <header class="panel-heading">
          <h2 class="panel-title"><i class="fa fa-list"></i> Students List</h2>
        </header>

        <form method="post" action="<?= URLROOT ?>/studentmarks/create" id="result-form">
          <div class="panel-body" style="width: 100%;">
            <table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
              <thead>
                <tr>
                  <th>Sr #</th>
                  <th>Image</th>
                  <th>Student Name</th>
                  <?php foreach ($data['subjects'] as $subject) : ?>
                    <th><?= $subject['subject']->name ?></th>
                  <?php endforeach; ?>
                  <th width="100px;" class="center">Total Marks</th>
                </tr>
              </thead>
              <tbody>

                <?php
                // Increment
                if (isset($data['students'])) :

                  $i = 1;
                  foreach ($data['students'] as $student) :
                ?>
                    <tr>
                      <td><?= $i ?></td>
                      <td>
                        <img src="<?= URLROOT . '/uploads/' . $student->image; ?>" style='width:40px; height:40px;'>
                      </td>
                      <td><?= $student->student_name ?></td>

                      <!-- Loop over all the subjects -->
                      <?php foreach ($data['subjects'] as $subject) : ?>
                        <th>
                          <style>
                            .grid{
                              display: grid;
                              grid-template: repeat(auto-fit, minmax(150px, 1fr));
                              gap: 10px;
                            }
                          </style>
                          <div class="grid numbers-container"  data-max-numbers="<?=$subject['max_marks']?> ">
                            <div>
                              <label class="control-label">Subjective Marks </label>
                              <input class="form-control" id="numbers-<?= $i ?>" placeholder="Subjective" type="number" value="0" min="0" name="marks[<?= $student->student_id ?>][<?= $subject['subject']->subject_id ?>][subjective]">
                            </div>
                            <div >
                                <label class="control-label">Objective Marks </label>
                              <input class="form-control" id="numbers-<?= $i ?>" placeholder="Objective" type="number" value="0" min="0" name="marks[<?= $student->student_id ?>][<?= $subject['subject']->subject_id ?>][objective]">
                            </div>
                          </div>
                        </th>
                      <?php endforeach; ?>
                      <!-- Loop over all the subject ends here -->
                      <td id="total"></td>
                    </tr>
                <?php
                    $i++;
                  endforeach;
                endif;
                ?>
              </tbody>
            </table>
            <input type="hidden" name="paper_type_id" value="<?= $data['paper_type_id'] ?? 0 ?>">
            <input type="hidden" name="id_class" value="<?= $data['class_id'] ?? 0 ?>">
          </div>

          <button type="submit" class="btn btn-primary" id="submit-result"> Submit</button>
        </form>

      </section>

      <script>
        const numberOfRows = document.querySelectorAll("tbody tr");

        numberOfRows.forEach((el, i) => {

          const totalField = document.querySelectorAll(`#total`);

          const numbersFields = document.querySelectorAll(`#numbers-${i + 1}`);

          numbersFields.forEach(input => {

            input.addEventListener('keyup', () => {
              let sum = 0;
              numbersFields.forEach(el1 => {
                sum += Number(el1.value);
              })
              totalField[i].innerHTML = sum;
            });

          })
        })
      </script>