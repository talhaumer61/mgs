<?php
echo'
<header class="panel-heading bg-primary">
    <p class="text-weight-semibold mt-none text-center" style="font-size: 24px; color:#ffffff;">Summer Vacation Work  - '.$value_detail['class_name'].' ('.$value_detail['section_name'].')</p>
</header>
<header class="panel-heading">
	<h2 class="panel-title"><i class="fa fa-list"></i> Summer Vacation Work List</h2>
</header>
<div class="panel-body">';
    include_once('summer_work/list.php');
    echo'
</div>';