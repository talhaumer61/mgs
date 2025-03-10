<section role="main" class="content-body">
  <header class="page-header">
    <h2>Paper Subject Marks</h2>
  </header>

  <div class="row">
    <div class="col-md-12">
      <section class="panel panel-featured panel-featured-primary" >

        <header class="panel-heading">
          <a href="<?=route("subjectmarks/add")?>" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Add New Marks</a>
          <h2 class="panel-title"><i class="fa fa-list"></i> Paper Subject Marks List</h2>
        </header>

        <div class="panel-body" style="width: 100%;">
          <table class="table table-bordered table_default table-striped table-condensed mb-none" id="table_export">
            <thead>
            <tr>
              <th class="text-center">Sr #</th>
              <th>Subject Name</th>
              <th>Exam Type</th>
              <th>Objective Marks</th>
              <th>Subjective Marks</th>
              <th>Total Marks</th>
              <th width="200px;" class="center">Options</th>
            </tr>
            </thead>
            <tbody>
            <?php for ($i = 0; $i < sizeof($data['marks']); $i++) : ?>
              <tr>
                <td class="text-center"><?= $i + 1 ?></td>
                <td><?= $data['marks'][$i]->subject->subject_name ?></td>
                <td><?= $data['marks'][$i]->exam->type_name ?></td>
                <td><?= $data['marks'][$i]->objective_marks ?></td>
                <td><?= $data['marks'][$i]->subjective_marks ?></td>
                <td><?= $data['marks'][$i]->subjective_marks + $data['marks'][$i]->objective_marks?></td>
                <td class=" center ">
                  </a>
                  <a class="btn btn-success btn-xs ml-xs" href="<?= URLROOT . '/subjectmarks/edit' . '/' . $data['marks'][$i]->id  ?>"> <i class=" fa fa-edit"></i>
                  </a>
                  <a class="btn btn-danger btn-xs ml-xs" href="<?= URLROOT . '/subjectmarks/delete' . '/' . $data['marks'][$i]->id ?>"> <i class=" fa fa-trash"></i>
                  </a>
                </td>
              </tr>
            <?php endfor; ?>
            </tbody>
          </table>
        </div>

      </section>
    </div>
  </div>


</section>