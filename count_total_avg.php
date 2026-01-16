<?php 
//***************************************************** COUNT TOTAL MARKS, FULL MARKS, AVG MARKS **********************************************************
                        $sql22vr="SELECT * FROM tabulatingsheet where classname ='$cn'  and sectionname='$secname' and sessionyear ='$sessionyear' and sccode = '$sccode' and stid='$stid' and exam='$exam'" ;
                        //echo $sql22vr;
                        $result22vr = $conn->query($sql22vr);
                        if ($result22vr->num_rows > 0) 
                        {while($row22vr = $result22vr->fetch_assoc()) {
                        $sub_1 = $row22vr["sub_1"] ;    $sub_2 = $row22vr["sub_2"] ;    $sub_3 = $row22vr["sub_3"] ;    $sub_4 = $row22vr["sub_4"] ;    $sub_5 = $row22vr["sub_5"] ;
                        $sub_6 = $row22vr["sub_6"] ;    $sub_7 = $row22vr["sub_7"] ;    $sub_8 = $row22vr["sub_8"] ;    $sub_9 = $row22vr["sub_9"] ;    $sub_10 = $row22vr["sub_10"] ;
                        $sub_11 = $row22vr["sub_11"] ;  $sub_12 = $row22vr["sub_12"] ;  $sub_13 = $row22vr["sub_13"] ;  $sub_14 = $row22vr["sub_14"] ;  $sub_15 = $row22vr["sub_15"] ;
                        
                        $sub_1_total = $row22vr["sub_1_total"] ;    $sub_2_total = $row22vr["sub_2_total"] ;    $sub_3_total = $row22vr["sub_3_total"] ;    $sub_4_total = $row22vr["sub_4_total"] ;    $sub_5_total = $row22vr["sub_5_total"] ;
                        $sub_6_total = $row22vr["sub_6_total"] ;    $sub_7_total = $row22vr["sub_7_total"] ;    $sub_8_total = $row22vr["sub_8_total"] ;    $sub_9_total = $row22vr["sub_9_total"] ;    $sub_10_total = $row22vr["sub_10_total"] ;
                        $sub_11_total = $row22vr["sub_11_total"] ;  $sub_12_total = $row22vr["sub_12_total"] ;  $sub_13_total = $row22vr["sub_13_total"] ;  $sub_14_total = $row22vr["sub_14_total"] ;  $sub_15_total = $row22vr["sub_15_total"] ;
                        
                        $sub_1_gp = $row22vr["sub_1_gp"] ;    $sub_2_gp = $row22vr["sub_2_gp"] ;    $sub_3_gp = $row22vr["sub_3_gp"] ;    $sub_4_gp = $row22vr["sub_4_gp"] ;    $sub_5_gp = $row22vr["sub_5_gp"] ;
                        $sub_6_gp = $row22vr["sub_6_gp"] ;    $sub_7_gp = $row22vr["sub_7_gp"] ;    $sub_8_gp = $row22vr["sub_8_gp"] ;    $sub_9_gp = $row22vr["sub_9_gp"] ;    $sub_10_gp = $row22vr["sub_10_gp"] ;
                        $sub_11_gp = $row22vr["sub_11_gp"] ;  $sub_12_gp = $row22vr["sub_12_gp"] ;  $sub_13_gp = $row22vr["sub_13_gp"] ;  $sub_14_gp = $row22vr["sub_14_gp"] ;  $sub_15_gp = $row22vr["sub_15_gp"] ;
                        
                        //echo $sub_1_total . $sub_2_total .'@';
                        include 'sub_15_count.php';
                        
                        include 'combined_1_2.php';
                        
                        
                        //echo $totalfullmarks . '-'. $totalmarks. '-'. $tfail. '-'. $totalgp. '-'. $totalsubject . '//////////////////////';
                        
                        
                        
                        if($cn!='Eight'){
                            $totalgp = $totalgp + $ben_gp + $eng_gp;
                           
                        } else {
                            $totalgp = $totalgp;
                        }
                         $totalsubject = $totalsubject + 2; //BENGALI + ENGLISH
                        //echo $stid;
                        
                        }}
					//**************************************************************************************************************************************************************
					