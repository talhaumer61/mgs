<?php
// EVENT CALENDAR
$sqllms	= $dblms->querylms("SELECT e.id, e.status, e.subject, e.detail, e.date_from, e.date_to, e.event_to, c.campus_name
                                FROM ".EVENTS." e  
                                LEFT JOIN ".CAMPUS." c ON c.campus_id = e.id_campus
                                WHERE e.id_campus IN (".$id_campus.")
                                AND e.status        = '1'
                                AND e.is_deleted    = '0'
                                ORDER BY e.date_from ASC
                            ");
$eventArray = array();
while($rowsvalues = mysqli_fetch_array($sqllms)) {
   array_push($eventArray, $rowsvalues);
}
echo '
<div class="col-md-12">
    <section class="panel panel-featured panel-featured-primary">
        <header class="panel-heading">
            <h2 class="panel-title"> <i class="fa fa-calendar"></i> Event Calender</h2>
        </header>
        <div class="panel-body">
            <div id="event_calendar"></div>
        </div>
    </section>
</div>

<script type="application/javascript">
    $(document).ready(function() {
        $("#event_calendar").fullCalendar({
            // set options and callbacks here...
            header: {
                left: "prev,next today",
                center: "title",
                right: "agendaWeek,month,listMonth"
            },
            defaultView: "month",
            editable: false,
            events: [';
                foreach($eventArray as $event){
                    $strt_day   = date('d', strtotime($event['date_from']));
                    $strt_month = date('m', strtotime($event['date_from'])) -1 ;
                    $strt_year   = date('Y', strtotime($event['date_from']));
                    
                    $end_day = date('d', strtotime($event['date_to'])) + 1;
                    $end_month = date('m', strtotime($event['date_to'])) - 1;
                    $end_year = date('Y', strtotime($event['date_to']));
            
                    echo'
                    {
                        title: "'.$event['subject'].'",
                        start: new Date('.$strt_year.', '.$strt_month.', '.$strt_day.'),
                        end: new Date('.$end_year.', '.$end_month.', '.$end_day.'),
                        description: "'.$event['detail'].'"
                    },';
                }
                echo'
            ]
        });     
    });
</script>';