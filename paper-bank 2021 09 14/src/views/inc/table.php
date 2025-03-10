  <div class="panel-body" style="width: 100%;">
    <table class="table table-bordered table_default table-striped table-condensed mb-none" id="table_export">
      <thead>
        <tr>
          <th class='text-center'>Sr #</th>
          <?php foreach ($data['table_header'] as $header) : ?>
            <th> <?= $header ?></th>
          <?php endforeach; ?>
          <th width="100px;" class="center">Options</th>
        </tr>
      </thead>
      <tbody>

        <?php
        // Increment
        $i = 1;
        foreach ($data['data'] as $data1) :
        ?>
          <tr>
            <?php
            if (isset($data['show']) && !empty($data['show'])) :
              echo "<td class='text-center'>$i</td>";
              $i++;
              foreach ($data['show'] as $key) : ?>
                <td>
                  <?php
                  if ($key == "image") :
                    echo "<img src=" . URLROOT . '/uploads/' . $data1[$key] . " style='width:40px; height:40px;'>";
                  elseif ($key == "status") :
                    echo $data1[$key] ? "<div class='badge bg-success'>Active</div>" :  "<div class='badge bg-danger'>In Active</div>";
                  elseif ($key == "question_status") :
                    if ($data1[$key] ==1 ):
                      echo"<div class='badge bg-success'>Active</div>"; 
                    elseif ($data1[$key] ==2 ) :
                      echo "<div class='badge bg-danger'>In Active</div>";
                    else :
                      echo "-";
                    endif;
                  else :
                    echo $data1[$key];
                  endif;
                  ?>
                </td>
            <?php
              endforeach;
            endif;
            ?>
            <?php if (isset($data['show']) && !empty($data['show'])) : ?>
              <td class=" center ">
                <a class="btn btn-success btn-xs ml-xs" href="<?= URLROOT . '/' . $data['controller'] . '/edit' . '/' . $data1[$data['primary_key']] ?>"> <i class=" fa fa-edit"></i>
                </a>
                <a class="btn btn-danger btn-xs ml-xs" href="<?= URLROOT . '/' . $data['controller'] . '/delete' . '/' . $data1[$data['primary_key']] ?>"> <i class=" fa fa-trash"></i>
                </a>
              </td>
            <?php endif; ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

  </div>