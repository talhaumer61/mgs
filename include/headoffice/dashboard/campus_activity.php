<?php

$sqlCampusActivity = $dblms->querylms("
    SELECT 
        logfile.id,
        logfile.id_user,
        logfile.id_campus,
        logfile.filename,
        logfile.action,
        logfile.dated,
        logfile.remarks,
        users.adm_fullname AS user_name,
        campus.campus_name AS campus_name

    FROM ".LOGS." logfile

    LEFT JOIN ".ADMINS." users
        ON users.adm_id = logfile.id_user

    LEFT JOIN ".CAMPUS." campus
        ON campus.campus_id = logfile.id_campus

    WHERE logfile.is_deleted != '1' AND logfile.id_campus != '0'

    ORDER BY logfile.dated DESC

    LIMIT 5
");

$campusActivities = array();

while($activity = mysqli_fetch_array($sqlCampusActivity)) {

    $activity['statement'] = get_activity_statement(
        $activity['user_name'],
        $activity['action'],
        $activity['filename'],
        $activity['campus_name']
    );

    $campusActivities[] = $activity;
}
echo '
<!-- =========================================
     LAST CAMPUS ACTIVITY
========================================= -->

<style>

    /* ==========================================
       LAST CAMPUS ACTIVITY
    ========================================== */

    .campus-activity-list {
        margin: -5px 0;
    }

    .campus-activity-item {
        display: flex;
        align-items: center;
        width: 100%;
        padding: 14px 5px;
        border-bottom: 1px solid #eeeeee;
        transition: background-color 0.2s ease;
    }

    .campus-activity-item:hover {
        background: #fafafa;
    }

    .campus-activity-item:last-child {
        border-bottom: 0;
    }


    /* ==========================================
       ICON
    ========================================== */

    .campus-activity-icon {
        width: 42px;
        height: 42px;
        min-width: 42px;

        border-radius: 50%;

        display: flex;
        align-items: center;
        justify-content: center;

        margin-right: 15px;

        font-size: 15px;
    }


    /* ==========================================
       ACTION COLORS
    ========================================== */

    .activity-add {
        background: #eaf8ef;
        color: #47a447;
    }

    .activity-update {
        background: #eef7ff;
        color: #0088cc;
    }

    .activity-delete {
        background: #fff0f0;
        color: #d9534f;
    }

    .activity-login {
        background: #f4efff;
        color: #8e44ad;
    }

    .activity-default {
        background: #f5f5f5;
        color: #777777;
    }


    /* ==========================================
       CONTENT
    ========================================== */

    .campus-activity-content {
        flex: 1;
        min-width: 0;
    }

    .campus-activity-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        width: 100%;
    }

    .campus-activity-top h4 {
        margin: 0;
        color: #333333;
        font-size: 13px;
        line-height: 1.45;
        font-weight: 600;
    }


    /* ==========================================
       BADGE
    ========================================== */

    .campus-activity-badge {
        font-size: 9px;
        line-height: 1;

        padding: 5px 9px;

        border-radius: 10px;

        white-space: nowrap;
        font-weight: 600;

        flex-shrink: 0;
    }


    /* ==========================================
       META INFORMATION
    ========================================== */

    .campus-activity-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;

        gap: 20px;

        margin-top: 6px;

        font-size: 10px;
        color: #aaaaaa;
    }

    .campus-activity-meta span {
        display: inline-flex;
        align-items: center;
    }

    .campus-activity-meta i {
        margin-right: 4px;
        font-size: 10px;
    }


    /* ==========================================
       EMPTY STATE
    ========================================== */

    .campus-activity-empty {
        text-align: center;
        padding: 45px 20px;
    }

    .campus-activity-empty .empty-icon {
        width: 55px;
        height: 55px;

        margin: 0 auto 12px;

        border-radius: 50%;

        background: #f5f5f5;
        color: #aaaaaa;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 21px;
    }

    .campus-activity-empty h4 {
        margin: 0 0 5px;

        font-size: 14px;
        color: #555555;
    }

    .campus-activity-empty p {
        margin: 0;

        font-size: 11px;
        color: #999999;
    }


    /* ==========================================
       FOOTER
    ========================================== */

    .campus-activity-footer {
        text-align: center;
    }

    .campus-activity-footer a {
        color: #0088cc;

        font-size: 11px;
        font-weight: 600;

        text-decoration: none;
    }

    .campus-activity-footer a:hover {
        text-decoration: underline;
    }

    .campus-activity-footer a i {
        margin-left: 4px;
    }


    /* ==========================================
       RESPONSIVE
    ========================================== */

    @media (max-width: 767px) {

        .campus-activity-item {
            align-items: flex-start;
            padding: 13px 0;
        }

        .campus-activity-icon {
            width: 38px;
            height: 38px;
            min-width: 38px;

            margin-right: 11px;
        }

        .campus-activity-top {
            align-items: flex-start;
        }

        .campus-activity-top h4 {
            font-size: 12px;
        }

        .campus-activity-meta {
            gap: 10px;
        }

    }

</style>


<!-- FULL WIDTH -->
<div class="col-md-12">

    <section class="panel panel-featured panel-featured-primary">

        <!-- HEADER -->
        <header class="panel-heading">

            <div class="panel-actions">
                <a href="javascript:void(0);" class="fa fa-refresh"></a>
            </div>

            <h2 class="panel-title">
                <i class="fa fa-history"></i>
                Last Campus Activity
            </h2>

            <p class="panel-subtitle">
                Recent activities across the campus
            </p>

        </header>


        <!-- BODY -->
        <div class="panel-body">

            <div class="campus-activity-list">';

            if(!empty($campusActivities)) {

                foreach($campusActivities as $activity) {

                    /* ==========================================
                       ACTIVITY TYPE
                    ========================================== */

                    switch($activity['action']) {

                        case '1':

                            $activityIcon  = 'fa-plus';
                            $activityClass = 'activity-add';
                            $badgeText     = 'Added';

                            break;


                        case '2':

                            $activityIcon  = 'fa-pencil';
                            $activityClass = 'activity-update';
                            $badgeText     = 'Updated';

                            break;


                        case '3':

                            $activityIcon  = 'fa-trash';
                            $activityClass = 'activity-delete';
                            $badgeText     = 'Deleted';

                            break;


                        case '4':

                            $activityIcon  = 'fa-sign-in';
                            $activityClass = 'activity-login';
                            $badgeText     = 'Login';

                            break;


                        default:

                            $activityIcon  = 'fa-history';
                            $activityClass = 'activity-default';
                            $badgeText     = 'Activity';

                            break;

                    }


                    /* ==========================================
                       DATA
                    ========================================== */

                    $userName = !empty($activity['user_name'])
                        ? $activity['user_name']
                        : 'System User';


                    $campusName = !empty($activity['campus_name'])
                        ? $activity['campus_name']
                        : '';


                    echo '

                    <!-- ACTIVITY ITEM -->
                    <div class="campus-activity-item">

                        <!-- ICON -->
                        <div class="campus-activity-icon '.$activityClass.'">

                            <i class="fa '.$activityIcon.'"></i>

                        </div>


                        <!-- CONTENT -->
                        <div class="campus-activity-content">

                            <!-- STATEMENT + BADGE -->
                            <div class="campus-activity-top">

                                <h4>
                                    '.$activity['statement'].'
                                </h4>

                                <span class="campus-activity-badge '.$activityClass.'">
                                    '.$badgeText.'
                                </span>

                            </div>


                            <!-- META -->
                            <div class="campus-activity-meta">';


                                /* CAMPUS */

                                if(!empty($campusName)) {

                                    echo '

                                    <span>
                                        <i class="fa fa-building-o"></i>
                                        '.$campusName.'
                                    </span>';

                                }


                                /* USER */

                                echo '

                                <span >
                                    <i class="fa fa-user"></i>
                                    '.$userName.'
                                </span>


                                <!-- DATE/TIME -->

                                <span>
                                    <i class="fa fa-clock-o"></i>
                                    '.date(
                                        'd M Y, h:i A',
                                        strtotime($activity['dated'])
                                    ).'
                                </span>


                            </div>

                        </div>

                    </div>';

                }

            } else {

                /* ==========================================
                   EMPTY STATE
                ========================================== */

                echo '

                <div class="campus-activity-empty">

                    <div class="empty-icon">

                        <i class="fa fa-history"></i>

                    </div>

                    <h4>
                        No Recent Activity
                    </h4>

                    <p>
                        There is no recent campus activity to display.
                    </p>

                </div>';

            }


            echo '

            </div>

        </div>

    </section>

</div>';
?>