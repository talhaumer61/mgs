<?php
//---------------- Inspection Schedule -----------------
$sqllmsSche	= $dblms->querylms("SELECT c.campus_name, d.purposed_date
                                FROM ".INSPECTION_SCHEDULE." s
                                INNER JOIN ".INSPECTION_SCHEDULE_DET." d ON d.id_schedule = s.schedule_id 
                                INNER JOIN ".CAMPUS."                  c ON c.campus_id   = d.id_campus
                                WHERE s.schedule_approval = '2' AND s.is_deleted != '1'
                                ORDER BY d.purposed_date ASC");
//-----------------------------------------------------
$scheduleArray = array();
while($valuesSchedule = mysqli_fetch_array($sqllmsSche)) {
   array_push($scheduleArray, $valuesSchedule);
}
//-----------------------------------------------------
echo'
<script>


$( document ).ready( function () {
    $( "#inspection_schedule" ).fullCalendar({
        header: {
            left: "title",
            right: "prev,today,next"
        },
        //defaultView: "basicWeek",
        displayEventTime: false,
        editable: false,
        firstDay: 1,
        height: 550,
        droppable: false,
        events: [
            ';
            foreach($scheduleArray as $schedule){

                $strt_day   = date('d', strtotime($schedule['purposed_date']));
                $strt_month = date('m', strtotime($schedule['purposed_date'])) -1 ;
                $strt_year   = date('Y', strtotime($schedule['purposed_date']));
                
                $end_day = date('d', strtotime($schedule['purposed_date'])) + 1;
                $end_month = date('m', strtotime($schedule['purposed_date'])) - 1;
                $end_year = date('Y', strtotime($schedule['purposed_date']));

                echo'
                {title: "'.$schedule['campus_name'].'",
                    start: new Date( '.$strt_year.', '.$strt_month.', '.$strt_day.' ),
                    end: new Date( '.$end_year.', '.$end_month.', '.$end_day.' ) 
                }, ';
            }
            echo'
            ]
    });
}); 

</script>

<!-- CALENDAR-->
<div class="col-md-12">
    <section class="panel panel-featured panel-featured-primary">
        <header class="panel-heading">
            <h2 class="panel-title">
                Inspection Schedule	
            </h2>
        </header>
        <div class="panel-body">
            <div id="inspection_schedule"></div>
        </div>
    </section>
</div>';
?>