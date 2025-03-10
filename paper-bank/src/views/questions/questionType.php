<section role="main" class="content-body">
  <header class="page-header">
    <h2>Choose Question Type</h2>
  </header>

  <!-- INCLUDEING PAGE -->
  <div class="row">
    <div class="col-md-12">
      <section class="panel panel-featured panel-featured-primary" >
          <div class="panel-heading">
            <h4 class="panel-title"><i class="fa fa-plus-square"></i>&nbsp;Select Question Type</h4>
          </div>
          <div class="panel-body text-center">
            <?php foreach ($data['question_types'] as $question_type): ?>
              <a href="<?=route('questions/add?question_type_id=' . $question_type->question_type_id)?>" class="btn btn-primary"><?=$question_type->question_type_name?></a>
            <?php endforeach; ?>
          </div>
      </section>
    </div>
  </div>
</section>