<section role="main" class="content-body">
  <header class="page-header"><h2><?=$data['title']?></h2></header>

  <!-- INCLUDEING PAGE -->
  <div class="col-md-12">
    <section class="panel panel-featured panel-featured-primary">
      <header class="panel-heading">
        <h2 class="panel-title"><i class="fa fa-list"></i> Paper Style List</h2>
      </header>

      <div class="panel-body" style="width: 100%;">
        <table class="table table-bordered table_default table-striped table-condensed mb-none" id="table_export">
          <thead>
          <tr>
            <th class='center' width="50px;">Sr #</th>
            <th class="center" >Type Name</th>
            <th class='center' width="100px;">Type Status</th>
          </tr>
          </thead>

          <tbody>

          <?php
          // Increment
          $i = 1;
          foreach ($data['styles'] as $style) :
            ?>
            <tr>
              <td class='center'><?=$i?></td>
              <td class='center'><?=$style->paper_style_name?></td>
              <td class='center'>
                <?php
                echo $style->paper_style_status ? "<div class='badge bg-success'>Active</div>" :  "<div class='badge bg-danger'>In Active</div>";
                ?>
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
