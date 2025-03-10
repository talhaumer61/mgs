<section class="panel">
	<div class="panel-body">
		<div class="row">
			<div class="col-lg-12">
				<div id="financegraph" style="height: 240px;"></div>
			</div>
		</div>
	</div>
</section>

<script type="application/javascript">
//FINANCES GRAPH SCRIPT
Highcharts.chart('financegraph', {

	chart: {
		type: 'spline',
		backgroundColor: 'transparent'
	},
	title: {
		text: ' Fee Graph'
	},
	xAxis: {
		categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
			'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
	},
	yAxis: {
		title: {
			min: 0,
			text: 'Amount'
		},
		labels: {
			overflow: 'justify'
		}
	},
	legend: {
		layout: 'vertical',
		align: 'right',
		verticalAlign: 'top',
		x: 5,
		y: -10,
		floating: true,
		borderWidth: 1,
		backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
		shadow: true
	},
	credits: {
		enabled: false
	},
	tooltip: {
		crosshairs: true,
		shared: true
	},

	series: [{
		name: 'Receivable',
		data: [
			
			<?php
			
			foreach($monthtypes as $month){

				if($month['id'] < 10) {
					$monthId = '0'.$month['id'];
				} else {
					$monthId = $month['id'];
				}

				$sqllmsReceivable	= $dblms->querylms("SELECT SUM(f.total_amount) as receivable
												FROM ".FEES." f
												WHERE f.id_campus IN (".$id_campus.")
												AND f.id_type IN (1, 2) AND (f.id_month = '".$month['id']."' OR f.issue_date LIKE '%-".$monthId."-%') 
												AND f.is_deleted != '1'");
				$valueReceivable = mysqli_fetch_array($sqllmsReceivable);
				if($valueReceivable['receivable']) {
					echo"{ y:".$valueReceivable['receivable']."},";
				} else {
					echo"{ y:0},";
				}
			}
			?>
		],
		color: '#5bc0de'
	}, {
		name: 'Recieved',
		data: [
			
			<?php
			
			foreach($monthtypes as $month){

				if($month['id'] < 10) {
					$monthId = '0'.$month['id'];
				} else {
					$monthId = $month['id'];
				}

				$sqllmsReceived	= $dblms->querylms("SELECT SUM(f.total_amount) as received
												FROM ".FEES." f
												WHERE f.id_campus IN (".$id_campus.")
												AND f.status = '1' AND f.id_type IN (1, 2)
												AND (f.id_month = '".$month['id']."' OR f.issue_date LIKE '%-".$monthId."-%')
												AND f.is_deleted != '1'");
				$valueReceived = mysqli_fetch_array($sqllmsReceived);
				if($valueReceived['received']) {
					echo"{ y:".$valueReceived['received']."},";
				} else {
					echo"{ y:0},";
				}
			}
			?>

		],
		color: '#47a447'
	}, {
		name: 'Pending',
		data: [
			
			<?php
			foreach($monthtypes as $month){

				if($month['id'] < 10) {
					$monthId = '0'.$month['id'];
				} else {
					$monthId = $month['id'];
				}
				
				$sqllmsPending	= $dblms->querylms("SELECT SUM(f.total_amount) as pending
												FROM ".FEES." f
												WHERE f.id_campus IN (".$id_campus.")
												AND f.status = '2' AND f.id_type IN (1, 2) 
												AND (f.id_month = '".$month['id']."' OR f.issue_date LIKE '%-".$monthId."-%')
												AND f.is_deleted != '1'");
				$valuePending = mysqli_fetch_array($sqllmsPending);
				if($valuePending['pending']) {
					echo"{ y:".$valuePending['pending']."},";
				} else {
					echo"{ y:0},";
				}
			}
			?>

		],
		color: '#ed9c28'
	}]

});
</script>