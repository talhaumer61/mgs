<?php
// if($count>$Limit) {
    echo'
    <div class="row"><div class="col-sm-12 col-md-6"><div class="dataTables_info" id="table_export_info" role="status" aria-live="polite">Showing '.((($page - 1) * $Limit) + 1).' to '.$srno.' of '.$count.' entries</div></div>
    <div class="col-sm-12 col-md-6">
        <ul class="pagination pull-right">';
            $current_page = strstr(basename($_SERVER['REQUEST_URI']), '.php', true);
            $pagination = "";
            if($lastpage >= 1){
                // PREVIOUS BUTTON
                if($page > 0){
                    $pagination.= '<li class="'.($page==1 ? 'disabled-link' : '').'"><a href="'.$current_page.'.php?'.$filters.'&page='.$prev.$sqlstring.'"><span class="fa fa-chevron-left"></span></a></li>';
                }

                // PAGES 
                if($lastpage < 7 + ($adjacents * 1)){ //not enough pages to bother breaking it up
                    for($counter = 1; $counter <= $lastpage; $counter++){
                        if($counter == $page){
                            $pagination.= '<li class="active"><a href="">'.$counter.'</a></li>';
                        }else{
                            $pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page='.$counter.$sqlstring.'">'.$counter.'</a></li>';
                        }
                    }
                }elseif($lastpage > 5 + ($adjacents * 1)){
                    //enough pages to hide some
                    //close to beginning - only hide later pages
                    if($page < 1 + ($adjacents * 1)){
                        for($counter = 1; $counter < 4 + ($adjacents * 1); $counter++){
                            if($counter == $page){
                                $pagination.= '<li class="active"><a href="">'.$counter.'</a></li>';
                            }else{
                                $pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page='.$counter.$sqlstring.'">'.$counter.'</a></li>';
                            }
                        }
                        $pagination.= '<li><a href="#"> ... </a></li>';
                        $pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page='.$lpm1.$sqlstring.'">'.$lpm1.'</a></li>';
                        $pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page='.$lastpage.$sqlstring.'">'.$lastpage.'</a></li>';   
                    }elseif($lastpage - ($adjacents * 1) > $page && $page > ($adjacents * 1)){
                        //in middle; hide some front and some back
                        $pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page=1'.$sqlstring.'">1</a></li>';
                        $pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page=2'.$sqlstring.'">2</a></li>';
                        $pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page=3'.$sqlstring.'">3</a></li>';
                        $pagination.= '<li><a href="#"> ... </a></li>';
                        for($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++){
                            if($counter == $page){
                                $pagination.= '<li class="active"><a href="">'.$counter.'</a></li>';
                            }else{
                                $pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page='.$counter.$sqlstring.'">'.$counter.'</a></li>';                 
                            }
                        }
                        $pagination.= '<li><a href="#"> ... </a></li>';
                        $pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page='.$lpm1.$sqlstring.'">'.$lpm1.'</a></li>';
                        $pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page='.$lastpage.$sqlstring.'">'.$lastpage.'</a></li>';   
                    }else{
                        //close to end; only hide early pages
                        $pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page=1'.$sqlstring.'">1</a></li>';
                        $pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page=2'.$sqlstring.'">2</a></li>';
                        $pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page=3'.$sqlstring.'">3</a></li>';
                        $pagination.= '<li><a href="#"> ... </a></li>';
                        for($counter = $lastpage - (3 + ($adjacents * 1)); $counter <= $lastpage; $counter++){
                            if($counter == $page){
                                $pagination.= '<li class="active"><a href="">'.$counter.'</a></li>';
                            }else{
                                $pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page='.$counter.$sqlstring.'">'.$counter.'</a></li>';                 
                            }
                        }
                    }
                }

                // NEXT BUTTON
                if($page < $counter){
                    $pagination.= '<li class="'.($page==$counter-1 ? 'disabled-link' : '').'"><a href="'.$current_page.'.php?'.$filters.'&page='.$next.$sqlstring.'"><span class="fa fa-chevron-right"></span></a></li>';
                }else{
                    $pagination.= "";
                }
                echo $pagination;
            }
            echo'
        </ul>
        <div class="clearfix"></div>
    </div>';
// }
?>