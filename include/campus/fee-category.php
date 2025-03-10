<?php
echo '
<title> Fee Category | '.TITLE_HEADER.'</title>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Fee Panel </h2>
	</header>
	<div class="row">
		<div class="col-md-12">';
			include_once("fee/list_fee_category.php");
			echo'
		</div>
	</div>
</section>';