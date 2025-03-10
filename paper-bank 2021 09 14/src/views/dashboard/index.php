<section role="main" class="content-body">
  <header class="page-header">
    <h2>Admin Panel</h2>
  </header>
  <!-- INCLUDEING PAGE -->
  <div class="row">
    <div class="col-md-4 col-xl-4">
      <section class="panel panel-featured-left panel-featured-secondary">
        <div class="panel-body">
          <div class="widget-summary">
            <div class="widget-summary-col widget-summary-col-icon">
              <div class="summary-icon bg-primary">
                <i class="fa fa-question"></i>
              </div>
            </div>
            <div class="widget-summary-col">
              <div class="summary">
                <h4 class="title">Subjective Questions</h4>
                <div class="info"><strong class="amount"><?=$data['subjective_questions']?></strong></div>
              </div>
              <div class="summary-footer">
                <span class="text-muted text-uppercase">total questions</span>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <div class="col-md-4 col-xl-4">
      <section class="panel panel-featured-left panel-featured-secondary">
        <div class="panel-body">
          <div class="widget-summary">
            <div class="widget-summary-col widget-summary-col-icon">
              <div class="summary-icon bg-danger">
                <i class="fa fa-question"></i>
              </div>
            </div>
            <div class="widget-summary-col">
              <div class="summary">
                <h4 class="title">Objective Questions</h4>
                <div class="info"><strong class="amount"><?=$data['objective_questions']?></strong></div>
              </div>
              <div class="summary-footer">
                <span class="text-muted text-uppercase">total questions</span>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <div class="col-md-4 col-xl-4">
      <section class="panel panel-featured-left panel-featured-secondary">
        <div class="panel-body">
          <div class="widget-summary">
            <div class="widget-summary-col widget-summary-col-icon">
              <div class="summary-icon bg-success">
                <i class="fa fa-print"></i>
              </div>
            </div>
            <div class="widget-summary-col">
              <div class="summary">
                <h4 class="title">Papers</h4>
                <div class="info"><strong class="amount"><?=$data['total_papers']?></strong></div>
              </div>
              <div class="summary-footer">
                <span class="text-muted text-uppercase">Total Papers Generated</span>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>

</section>