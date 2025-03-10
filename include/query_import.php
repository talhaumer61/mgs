<?php
if (isset($_POST['upload_file'])) {
    $targetPath = $_SESSION['FILE']['FILE_NAME'];
    $Reader = new SpreadsheetReader($targetPath);
    $sheetCount = count($Reader->sheets());
    $fl = 0;
    $sql_1 = 'INSERT INTO '.STUDENTS.' ( std_status, id_session, id_campus, id_class, id_section, std_rollno, std_name, std_fathername, std_phone, std_dob, std_gender, std_admissiondate, id_added , date_added, std_regno ) VALUES ';
    for($i=0;$i<$sheetCount;$i++){
        $Reader->ChangeSheet($i);
        foreach ($Reader as $Row)  {
            $fl++;
            if ($fl>1) {
                $std_status         = '1';
                $id_session         = '3';
                $id_campus          = $_POST['id_campus'];
                $id_class           = cleanvars($Row[0]);
                $id_section         = cleanvars($Row[1]);
                $std_rollno         = cleanvars($Row[2]);
                $std_name           = ucwords(cleanvars(strtolower($Row[3])));
                $std_fathername     = ucwords(cleanvars(strtolower($Row[4])));
                $std_phone          = str_replace('-', '', $Row[5]);
                $std_dob            = (!empty($Row[6]))? date("Y-m-d",strtotime($Row[6])): '0000-00-00';
                $std_gender         = cleanvars($Row[7]);
                $std_admissiondate  = date('Y-m-d');
                $id_added           = cleanvars($_SESSION['userlogininfo']['LOGINIDA']);  
                $date_added         = date('Y-m-d G:i:s');

                // GENERATE REGISTERATION NUMBER 
                $sqllmscampus = $dblms->querylms("SELECT  c.campus_code, cg.group_code_numeric, b.brand_code_numeric, d.dist_code
                                                    FROM ".CAMPUS." c 
                                                    INNER JOIN ".CAMPUS_GROUPS." cg ON cg.group_id = c.id_group
                                                    INNER JOIN ".BRANDS." b ON b.brand_id = c.id_brand
                                                    INNER JOIN ".DISTRICTS." d ON d.dist_id  = c.id_dist
                                                    WHERE c.is_deleted = '0'
                                                    AND c.campus_id = '".cleanvars($id_campus)."'
                                                    LIMIT 1
                                                ");
                $value_campus = mysqli_fetch_array($sqllmscampus);

                $regnoStr = STD_PREFIX.$value_campus['group_code_numeric'].$value_campus['brand_code_numeric'].'-'.$value_campus['dist_code'].$value_campus['campus_code'].'-'.substr(date("Y"), -2);

                $sqllmsstudentregno = $dblms->querylms("SELECT std_regno
                                                        FROM ".STUDENTS." 
                                                        WHERE std_regno LIKE '".$regnoStr."%'
                                                        AND id_campus = '".cleanvars($id_campus)."'
                                                        ORDER by std_regno DESC LIMIT 1 ");
                $value_regno = mysqli_fetch_array($sqllmsstudentregno);
                if(mysqli_num_rows($sqllmsstudentregno) < 1) {
                    $std_regno	= $regnoStr.'-0001';
                }else{
                    $std_regno = $value_regno['std_regno'];
                    $std_regno++;
                }

                $sql_2 = '(\''.$std_status.'\', \''.$id_session.'\', \''.$id_campus.'\', \''.$id_class.'\', \''.$id_section.'\', \''.$std_rollno.'\', \''.$std_name.'\', \''.$std_fathername.'\', \''.$std_phone.'\', \''.$std_dob.'\', \''.$std_gender.'\', \''.$std_admissiondate.'\', \''.$id_added.'\', \''.$date_added.'\', \''.$std_regno.'\')';

                $sql_query = $sql_1.$sql_2.';';
                $sqllms  = $dblms->querylms($sql_query);
                // echo '<br>';
            }
        }        
        // exit;
    }

    if ($sqllms) {
        echo '<script>alert("Record Successfully Added")</script>';
        header("Location: dashboard.php", true, 301);
        exit();
    }
}
?>