<section role="main" class="content-body">
  <header class="page-header">
    <h2>Chapters Panel</h2>
  </header>

  <!-- INCLUDEING PAGE -->
  <div class="row">
    <div class="col-md-12">
      <section class="panel panel-featured panel-featured-primary">
        <header class="panel-heading">
          <a href="<?= URLROOT ?>/chapters/add" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Chapter</a>
          <h2 class="panel-title"><i class="fa fa-list"></i> Chapters List</h2>
        </header>

        <div class="panel-body" style="width: 100%;">
          <table class="table table-bordered table_default table-striped table-condensed mb-none" id="table_export">
            <thead>
            <tr>
              <th class='center' width="50">Sr #</th>
              <th class="center" style="width: 100px;">Chapter No.</th>
              <th class="center">Class Name</th>
              <th class="center">Subject</th>
              <th class="center">Chapter Name</th>
              <th width="100px" class="center">Options</th>
            </tr>
            </thead>
            <tbody>

            <?php
            // Increment
            $i = 1;
            foreach ($data['chapters'] as $chapter) :

              ?>
              <tr>
                <td class="center"><?=$i?></td>
                <td class="center"><?=$chapter->chapter_no?></td>
                <td class="center">
                  <?php
                  foreach($data['classes'] as $class):
                      if($class->class_id == $chapter->subject->id_class):
                        echo $class->class_name;
                      endif;
                  endforeach;
                  ?>
                </td>
                <td class="center"><?=$chapter->subject->subject_name?></td>
                <td class="center"><?=$chapter->chapter_name?></td>
                <td class=" center ">
                  <a class="btn btn-success btn-xs ml-xs" href="<?= URLROOT?>/chapters/edit/<?=$chapter->chapter_id?>"> <i class=" fa fa-edit"></i>
                  </a>
                  <!-- <a class="btn btn-danger btn-xs ml-xs" href="<?= URLROOT?>/chapters/delete/<?=$chapter->chapter_id?>"> <i class=" fa fa-trash"></i>
                  </a> -->
                </td>
              </tr>
            <?php
            $i++;
            endforeach; ?>
            </tbody>
          </table>

        </div>

      </section>