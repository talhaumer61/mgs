<?php
if(($_SESSION['userlogininfo']['LOGINAFOR'] == 1 && $_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('54', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '54', 'edit' => '1'))) {
    
    if(isset($_GET['id'])){
        $sqllms	= $dblms->querylms("SELECT sal_id, sal_status, receipt_no, sal_total_amount, dated, note
                                        FROM ".INVENTORY_SALE." 
                                        WHERE sal_id ='".$_GET['id']."' LIMIT 1");
        $rowsvalues = mysqli_fetch_array($sqllms);

        $sqllms_det	= $dblms->querylms("SELECT d.id_setup, d.id_item, d.qty, d.unit_price, i.item_name, i.school_price	 
                                            FROM ".INVENTORY_SALE_DETAIL." d
                                            INNER JOIN ".INVENTORY_ITEMS." i ON i.item_id =  d.id_item
                                            WHERE d.id_setup ='".$_GET['id']."' ");
        echo '
        <form action="stationary_sale.php" class="validate" method="post" enctype="multipart/form-data" accept-charset="utf-8" novalidate="novalidate">
            <section class="panel panel-featured panel-featured-primary appear-animation fadeInRight appear-animation-visible" data-appear-animation="fadeInRight" data-appear-animation-delay="100" style="animation-delay: 100ms;">
                <header class="panel-heading">
                    <h2 class="panel-title"><i class="glyphicon glyphicon-eye-open"></i> Sale Detail </h2>
                </header>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <section class="panel" style="box-shadow: 0 3px 12px 0 rgba(0, 0, 0, 0.15);">
                                <header class="panel-heading" style="border-bottom: 2px solid #0088cc;">
                                    <h2 class="panel-title"><i class="glyphicon glyphicon-list"></i> Items</h2>
                                </header>
                                <div class="panel-body">
                                    <div class="row mb-md">
                                        <input type="hidden" name="sal_id" value="'.$rowsvalues['sal_id'].'">';
                                        while($values_det = mysqli_fetch_array($sqllms_det)) {
                                            $item_price = $values_det['school_price'] * $values_det['qty'];
                                            echo'
                                            <div class="col-sm-3 mt-md">
                                                <label for="radioExample2">Item</label>
                                                <input class="form-control" name="unit_price[]" placeholder="Unit Price" id="unit_price" type="text" value="'.$values_det['item_name'].'" readonly>
                                            </div>
                                            <div class="col-sm-3 mt-md">
                                                <label for="radioExample2">Unit Price</label>
                                                <input class="form-control" name="unit_price[]" placeholder="Unit Price" id="unit_price" type="number" value="'.$values_det['school_price'].'" readonly>
                                            </div>
                                            <div class="col-sm-3 mt-md">
                                                <label for="radioExample2">Qunatity</label>
                                                <input class="form-control" name="qty[]" placeholder="Quantity" id="qty" type="number" value="'.$values_det['qty'].'" readonly>
                                            </div>
                                            <div class="col-sm-3 mt-md">
                                                <label for="radioExample2">Price</label>
                                                <input class="total form-control" name="price[]" placeholder="Price" id="price" type="number" value="'.$item_price.'" readonly>
                                            </div>';
                                        }
                                        echo'
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-offset-6">
                            <section class="panel" style="box-shadow: 0 3px 12px 0 rgba(0, 0, 0, 0.15);">
                                <header class="panel-heading" style="border-bottom: 2px solid #0088cc;">
                                    <h2 class="panel-title">Sale Detail</h2>
                                </header>
                                <div class="panel-body">
                                    <table class="table h5 text-dark">
                                        <tbody>
                                            <tr class="b-top-none">
                                                <td colspan="2">Receipt Number</td>
                                                <td class="text-left">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-file"></i></span>
                                                        <input type="text" class="form-control" name="receipt_no" readonly="" value="'.$rowsvalues['receipt_no'].'" >
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="b-top-none">
                                                <td colspan="2">Purchase Note</td>
                                                <td class="text-left">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-file"></i></span>
                                                        <textarea class="form-control" name="note" readonly>'.$rowsvalues['note'].'</textarea>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">Total Amount</td>
                                                <td class="text-left">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                                        <input type="text" class="form-control" name="sal_total_amount" readonly="" id="sal_total_amount" value="'.$rowsvalues['sal_total_amount'].'">
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
                <!-- <div class="panel-footer text-right">
                    <button type="submit" name="change_sale" class="btn btn-primary"> Update Sale </button>
                </div> -->
            </section>
        </form>';
    }
}else{
    header("location: stationary_sale.php");
}
?>