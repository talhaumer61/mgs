<?php
echo'
<header class="panel-heading bg-primary">
    <a href=""><p class="text-weight-semibold mt-none text-center" style="font-size: 24px; color:#ffffff;">Worksheets - '.$value_detail['class_name'].' ('.$value_detail['section_name'].')</p></a>
</header>
<header class="panel-heading">
	<h2 class="panel-title"><i class="fa fa-list"></i> Worksheets List</h2>
</header>
<div class="panel-body">';
    include_once('worksheet/list.php');
    echo'
</div>';