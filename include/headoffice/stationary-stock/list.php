<?php
//---------------------- STOCK DETAILS -------------------------
if($_SESSION['userlogininfo']['LOGINAFOR'] == 1 || (arrayKeyValueSearch($_SESSION['userroles'], 'right_name', '34'))){
    echo'
    <section class="panel panel-featured panel-featured-primary">
        <header class="panel-heading">
            <h2 class="panel-title"><i class="fa fa-list"></i>  Stationary Stock</h2>
        </header>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Item Name</th>
                        <th>Item Code</th>
                        <th>Item Quantity</th>
                    </tr>
                </thead>
                <tbody>';
                //-----------------------------------------------------
                $sqllms_item	= $dblms->querylms("SELECT item_id, item_name, item_code
                                                FROM ".INVENTORY_ITEMS."
                                                ORDER BY item_name ASC");
                $srno = 0;
                //-----------------------------------------------------
                while($values_item = mysqli_fetch_array($sqllms_item)) {
                    //-------------------------- PURCHASE ---------------------------
                    $sqllms_pur	= $dblms->querylms("SELECT SUM(d.qty) AS totalpurchase
                                                FROM ".INVENTORY_PURCHASE." p 
                                                INNER JOIN ".INVENTORY_PUR_DETAIL." d ON d.id_setup = p.pur_id 
                                                WHERE p.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
                                                AND d.id_item = '".$values_item['item_id']."'");
                    //-----------------------------------------------------
                    $values_purchase = mysqli_fetch_array($sqllms_pur);
					//-----------------------------------------------------
					
                    //------------------------- SALES ----------------------------
                    $sqllms_sale = $dblms->querylms("SELECT SUM(qty) AS totalsale
                                                FROM ".INVENTORY_SALE." s 
                                                INNER JOIN ".INVENTORY_SALE_DETAIL." d ON d.id_setup = s.sal_id 
                                                WHERE s.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  
												AND s.sal_status IN (4, 5) AND d.id_item = '".$values_item['item_id']."'");
                    //-----------------------------------------------------
                    $values_sale = mysqli_fetch_array($sqllms_sale);
					//-----------------------------------------------------
					$items = $values_purchase['totalpurchase'] - $values_sale['totalsale'];
					//-----------------------------------------------------
					$srno++;
					//-----------------------------------------------------

                echo '
                    <tr>
                        <td class="text-center">'.$srno.'</td>
                        <td>'.$values_item['item_name'].'</td>
                        <td>'.$values_item['item_code'].'</td>
                        <td>'.$items.'</td>
                    </tr>';
                //-----------------------------------------------------
                }
                //-----------------------------------------------------
                echo '
                </tbody>
            </table>
        </div>
    </section>';
}
?>