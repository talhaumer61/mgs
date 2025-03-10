<?php $question = $data['question']; ?>
<style>
  .text-left > * {
    text-align: left !important;
    direction: ltr !important;
  }

  .text-right > * {
    text-align: right !important;
    direction: rtl !important;
  }
</style>
<tr >
  <td>
    <!--  Replaced on the Javascript Side with the correct serial number  -->
    %%SR_NUM%%
  </td>
  <td><?=$question->group_num?></td>
  <td><?=$question->page_num?></td>
  <td>
    <p class="text-left"><?=$question->question_english?></p>
    <p class="text-right" dir="rtl"><?=$question->question_urdu?></p>
  </td>
  <td>
    <button id="update_btn" data-json='<?php echo $question->toJson()?>' data-question-id="<?=$question->question_id?>" class="remove-btn btn btn-xs btn-success"> <i class=" fa fa-edit"></i></button>
    <button id="remove_btn" data-json='<?php echo $question->toJson()?>' data-question-id="<?=$question->question_id?>" class="remove-btn btn btn-xs btn-danger"> <i class=" fa fa-trash"></i></button>
  </td>
</tr>