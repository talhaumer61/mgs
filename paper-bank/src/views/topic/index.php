<section role="main" class="content-body">
  <header class="page-header"><h2><?=$data['title']?></h2></header>

  <!-- INCLUDEING PAGE -->
  <div class="col-md-12">
    <section class="panel panel-featured panel-featured-primary">
      <header class="panel-heading">
        <a href="#createModal" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Add New Topic</a>
        <h2 class="panel-title"><i class="fa fa-list"></i> Topics List</h2>
      </header>

      <div class="panel-body" style="width: 100%;">
        <table class="table table-bordered table_default table-striped table-condensed mb-none" id="table_export">
          <thead>
          <tr>
            <th class='center' width="50px;">Sr #</th>
            <th class="center" >Class Name</th>
            <th class="center" >Subject Name</th>
            <th class="center" >Chapter Name</th>
            <th class="center" >Topic Name</th>
            <th class="center"  width="100px;">Topic Status</th>
            <th class="center" width="100px;">Options</th>
          </tr>
          </thead>
          <tbody>
          <?php
          // Increment
          $i = 1;
          foreach ($data['topics'] as $topic) :
            ?>
            <tr>
              <td class='center'><?=$i?></td>
              <td class='center'><?=$topic->class->class_name?></td>
              <td class='center'><?=$topic->subject->subject_name?></td>
              <td class='center'> <?=$topic->chapter->chapter_name?></td>
              <td class='center'><?=$topic->topic_name?></td>
              <td class='center'>
                <?php
                echo $topic->topic_status ? "<div class='badge bg-success'>Active</div>" :  "<div class='badge bg-danger'>Inactive</div>";
                ?>
              </td>
              <td class="center ">
                <a class="modal-with-move-anim btn btn-success btn-xs ml-xs" data-modal="#updateModal" data-load-html="true" data-fetch-url="<?=URLROOT . "/topics/edit_html/$topic->topic_id"?>" href="#updateModal"> <i class=" fa fa-edit"></i>
                </a>
                <a class="btn btn-danger btn-xs ml-xs" href="<?=route("topics/delete/$topic->topic_id")?>"> <i class=" fa fa-trash"></i>
                </a>
              </td>
            </tr>
            <?php
            // Increment the count
            $i ++;
          endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>
  </div>


  <!-- Create New Topic -->
  <div id="createModal" class="zoom-anim-dialog modal-block modal-block mfp-hide">
    <section class="panel panel-featured panel-featured-primary">
      <form action="<?=route('topics/create')?>" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="novalidate">
        <header class="panel-heading">
          <h4 class="panel-title">Create New Topic</h4>
        </header>

        <div class="panel-body">

          <div class="row">
            <div class="col-md-12 mx-auto">
              <div class="form-group">
                <label class="control-label">Topic Name <span class="required" aria-required="true">*</span></label>
                <input type="text" class="form-control" name="topic_name" id="topic_name" required="" title="Must Be Required" aria-required="true">
              </div>
            </div>
          </div>

          <div class="row mt-sm">
            <div class="col-md-12 mx-auto">
              <div class="form-group">
                <label class="control-label">Class </label>
                <select class="form-control" required title="Must Be Required" data-plugin-selectTwo  onchange="get_class_subjects(this.value)"  data-width="100%" name="id_class" required title="Must Be Required">
                  <option value="">Select</option>
                  <?php foreach ($data['classes'] as $class) : ?>
                    <option value=<?= $class->class_id ?> <?=isset($data['id_class']) && $data['id_class'] == $class->class_id ? "selected" : '';?>><?= $class->class_name ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <div class="row mt-sm">
            <div class="col-md-12 mx-auto">
              <div class="form-group">
                <label class="control-label">Subject </label>
                <select class="form-control" name="id_subject"  onchange="get_subject_chapters(this.value)"  data-plugin-selectTwo id="class_subjects" data-width="100%" >
                  <option value="">Select Cass First</option>
                  <?php  foreach ($data['class_subjects'] as $class_subjects) : ?>
                    <option value="<?=$class_subjects['subject_id']?>" <?=isset($data['id_subject']) && $data['id_subject'] == $class_subjects['subject_id'] ? 'selected' : "";?>><?=$class_subjects['subject_name']?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <div class="row mt-sm">
            <div class="col-md-12 mx-auto">
              <div class="form-group">
                <label class="control-label">Chapter </label>
                <select class="form-control" name="id_chapter" data-plugin-selectTwo id="subject_chapters" data-width="100%" >
                  <option value="">Select Cass First</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-12">
              <div class="form-group mt-md mb-sm">
                <label class="control-label">Status <span class="required">*</span></label>
                <div class="radio-custom radio-inline ml-md mt-sm">
                  <input type="radio" id="topic_status" name="topic_status" value="1" checked>
                  <label for="radioExample1">Active</label>
                </div>
                <div class="radio-custom radio-inline">
                  <input type="radio" id="topic_status" name="topic_status" value="0">
                  <label for="radioExample2">Inactive</label>
                </div>
              </div>
            </div>
          </div>

        </div>

        <footer class="panel-footer">
          <div class="row">
            <div class="col-md-12 text-right">
              <button class="btn btn-default modal-dismiss">Cancel</button>
              <button type="submit" id="submit" name="submit" class="mr-xs btn btn-primary ">Add Topic</button>
            </div>
          </div>
        </footer>

      </form>
    </section>
  </div>

  <!-- Update Topic -->
  <div id="updateModal" class="zoom-anim-dialog modal-block modal-block mfp-hide">
    <section class="panel panel-featured panel-featured-primary">
    </section>
  </div>


</section>
