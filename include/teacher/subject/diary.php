<?php
echo '
<header class="panel-heading bg-primary">
    <a href=""><p class="text-weight-semibold mt-none text-center" style="font-size: 24px; color:#ffffff;">Diary - '.$value_detail['class_name'].' ('.$value_detail['section_name'].')</p></a>
</header>
<header class="panel-heading">
	<a href="#make_announcement" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Add Diary</a>
	<h2 class="panel-title"><i class="fa fa-list"></i> Diary List</h2>
</header>
<div class="panel-body">';
	include_once('diary/query_diary.php');
	include_once('diary/list.php');
	include_once("include/modals/diary/modal_add.php");
	echo'
</div>';