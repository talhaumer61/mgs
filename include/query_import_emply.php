<?php
if (isset($_POST['upload_file'])) {
    $targetPath = $_SESSION['FILE']['FILE_NAME'];
    $Reader = new SpreadsheetReader($targetPath);
    $sheetCount = count($Reader->sheets());
    $fl = 0;
    $sql_1 = 'INSERT INTO '.EMPLOYEES.' (
                                            `emply_status`, `emply_ordering`, `emply_regno`, `emply_name`, `id_dept`, `id_designation`, `id_type`, `emply_gender`, `emply_dob`, `emply_joindate`, `emply_education`, `emply_religion`, `emply_address`, `emply_phone`, `emply_whatsapp`, `emply_basicsalary`, `id_campus`, `id_added`, `date_added`, `is_deleted`
                                        ) 
                                        VALUES ';
    for($i=0;$i<$sheetCount;$i++)
    {
        $Reader->ChangeSheet($i);
        foreach ($Reader as $Row)  {
            $fl++;
            if ($fl>1) {
                $emply_status       = '1';
                $id_campus          = $_POST['id_campus'];

                $sqllms	= $dblms->querylms("SELECT e.emply_regno, e.emply_ordering, c.campus_regno 
                                            FROM ".CAMPUS." c 
                                            LEFT JOIN ".EMPLOYEES." e ON c.campus_id = e.id_campus 
                                            WHERE c.campus_id = '".$id_campus."' 
                                            ORDER BY e.emply_id DESC LIMIT 1");
                $rowsemployee = mysqli_fetch_array($sqllms);

                $order = $rowsemployee['emply_ordering'] + 1;
                $regno = "MES-".$rowsemployee['campus_regno']."-Emp-".str_pad($order,4,"0",STR_PAD_LEFT);

                $emply_ordering     = $order;
                $emply_regno        = $regno;
                $emply_name         = ucwords(cleanvars(strtolower($Row[2])));
                $id_dept            = $Row[9];
                $id_designation     = $Row[10];
                $id_type            = $Row[8];
                $emply_gender       = (strtolower($Row[6]) == 'm' || strtolower($Row[6]) == 'male')? 'Male': 'Female';
                $emply_dob          = (!empty($Row[7]))? date("Y-m-d",strtotime($Row[7])): '0000-00-00';
                $emply_joindate     = (!empty($Row[13]))? date("Y-m-d",strtotime($Row[13])): '0000-00-00';
                $emply_education    = $Row[11];
                $emply_religion     = ucwords(cleanvars(strtolower($Row[14])));
                $emply_phone        = str_replace(',', '', str_replace('-', '', $Row[4]));
                $emply_whatsapp     = str_replace(',', '', str_replace('-', '', $Row[5]));
                $emply_address      = ucwords(cleanvars(strtolower($Row[18])));
                $emply_basicsalary  = $Row[19];
                $id_added           = cleanvars($_SESSION['userlogininfo']['LOGINIDA']);  
                $date_added         = date('Y-m-d h:i:s');
                $is_deleted         = '0';

                $values = array (
                                      "emply_status"	    =>	cleanvars($emply_status)
                                    , "id_campus"	        =>	cleanvars($id_campus)
                                    , "emply_ordering"	    =>	cleanvars($emply_ordering)
                                    , "emply_regno"	        =>	cleanvars($emply_regno)
                                    , "emply_name"	        =>	cleanvars($emply_name)
                                    , "id_dept"	            =>	cleanvars($id_dept)
                                    , "id_designation"	    =>	cleanvars($id_designation)
                                    , "id_type"	            =>	cleanvars($id_type)
                                    , "emply_gender"	    =>	cleanvars($emply_gender)
                                    , "emply_dob"	        =>	cleanvars($emply_dob)
                                    , "emply_joindate"	    =>	cleanvars($emply_joindate)
                                    , "emply_education"	    =>	cleanvars($emply_education)
                                    , "emply_religion"	    =>	cleanvars($emply_religion)
                                    , "emply_address"	    =>	cleanvars($emply_address)
                                    , "emply_phone"	        =>	cleanvars($emply_phone)
                                    , "emply_whatsapp"	    =>	cleanvars($emply_whatsapp)
                                    , "emply_basicsalary"	=>	cleanvars($emply_basicsalary)
                                    , "id_campus"	        =>	cleanvars($id_campus)
                                    , "id_added"	        =>	cleanvars($id_added)
                                    , "date_added"	        =>	cleanvars($date_added)
                                    , "is_deleted"	        =>	cleanvars($is_deleted)
                                );		
                $sqllms  = $dblms->insert(EMPLOYEES, $values);

            }
        }
    }

    if ($sqllms) {
        echo '<script>alert("Record Successfully Added")</script>';
        header("Location: dashboard.php", true, 301);
        exit();
    }
}
?>