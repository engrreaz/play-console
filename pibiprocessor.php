<?php
date_default_timezone_set('Asia/Dhaka');;
$cur = date('Y-m-d H:i:s');; $sy = date('Y');
	include ('../db.php');;
	

	$sccode = $_POST['sccode'];; $rootuser = $_POST['rootuser'];;  $usr = $_POST['usr'];;   $id = $_POST['id'];;     $exam = $_POST['exam'];;     $tail = $_POST['tail'];;  
	$jax = $id;
    $sql00xgr = "SELECT * FROM areas where id='$id'";  
    $result00xgr = $conn->query($sql00xgr);
    if ($result00xgr->num_rows > 0) {while($row00xgr = $result00xgr->fetch_assoc()) {
    $clsf=$row00xgr["areaname"]; $secf=$row00xgr["subarea"];}}
    
    
    $sql242fr = "SELECT * FROM sessioninfo where sessionyear='$sy' and classname='$clsf' and sectionname='$secf' and sccode='$sccode' order by rollno limit 1"; 
    $result242fr = $conn->query($sql242fr); if ($result242fr->num_rows > 0) {while($row242fr = $result242fr->fetch_assoc()) {
    $firstroll=$row242fr['rollno'];}}
    $sql242fr = "SELECT * FROM sessioninfo where sessionyear='$sy' and classname='$clsf' and sectionname='$secf' and sccode='$sccode' order by rollno desc limit 1"; 
    $result242fr = $conn->query($sql242fr); if ($result242fr->num_rows > 0) {while($row242fr = $result242fr->fetch_assoc()) {
    $lastroll=$row242fr['rollno'];}}
    /*
    $sql00xgrf = "SELECT * FROM sessioninfo where sccode='$sccode' order by stid desc LIMIT 1";  
    $result00xgrf = $conn->query($sql00xgrf);
    if ($result00xgrf->num_rows > 0) {while($row00xgrf = $result00xgrf->fetch_assoc()) {
    $lastid=$row00xgrf["stid"]; }} else {$lastid = $sccode * 10000 ;} $lastid = $lastid + 1;
    */
    
    
    // reset or previous process.................................................
    
    
        if($tail == 0 ){
            $query31 = "DELETE from pibiprocess where sessionyear = '$sy' and sccode = '$sccode' and exam = '$exam' and classname = '$clsf' and sectionname = '$secf' and assess='Merged BI'";
            //$conn->query($query31) ;
            
            $query35 = "DELETE from pibientry where sessionyear = '$sy' and sccode = '$sccode' and exam = '$exam' and classname = '$clsf' and sectionname = '$secf' and assesstype LIKE 'Merged%'";
            //$conn->query($query35) ;
            
            if($clsf=='Eight' || $clsf=='Nine'||$clsf=='Ten'){
                $query31x = "DELETE from pibiprocess where sessionyear = '$sy' and sccode = '$sccode' and exam = '$exam' and classname = '$clsf' and sectionname = '$secf' and assess='Total Exam'";
                $conn->query($query31x) ;
            }
                
                        
            $sql242 = "SELECT * FROM subsetup where classname='$clsf' and sectionname = '$secf' and sccode='$sccode' order by subject";
            $result242 = $conn->query($sql242); if ($result242->num_rows > 0) {while($row242 = $result242->fetch_assoc()) {
            $subcode=$row242['subject']; $id=$row242['id']; 
            $ss=$row242['subj'];  $oo=$row242['obj'];  $pp=$row242['pra'];  $fm=$row242['fullmarks'];  
            $en = 0; if($ss>0) $en++; if($oo>0) $en++; if($pp>0) $en++;
            
                    $sql242f = "SELECT * FROM subjects where subcode='$subcode' "; 
                    $result242f = $conn->query($sql242f); if ($result242f->num_rows > 0) {while($row242f = $result242f->fetch_assoc()) {
                    $subname=$row242f['subject']; $subben=$row242f['subben']; }}
                    
                    $jex = 0;
                    $sql242fr = "SELECT religion, count(*) as cnt FROM sessioninfo where sessionyear='$sy' and classname='$clsf' and sectionname='$secf' and sccode='$sccode' and status = 1 group by religion"; 
                    //echo $sql242fr; 
                    $result242fr = $conn->query($sql242fr); if ($result242fr->num_rows > 0) {while($row242fr = $result242fr->fetch_assoc()) {
                    $rel=$row242fr['religion']; $stcount=$row242fr['cnt'];  $jex= $jex + $stcount;
                    //echo $rel . '/' . $stcount . '<br>';
                        if($rel == 'Hindu'){$hindu = $stcount;}
                        if($rel == 'Islam'){$islam = $stcount;}
                        
                    }}
                    
                    if($subcode == 906){ $stcount = $islam;} else 
                    if($subcode == 907){ $stcount = $hindu;} else 
                                        { $stcount = $hindu + $islam;}
                    //echo '**'.$stcount.'***<br>';
                        
                        if($clsf == 'Six' || $clsf == 'Seven'){
                            
                            for($lp = 0; $lp<=3; $lp++){
                        if($lp == 0) {$assesstype = 'Continious Assessment'; $fld = 'continious'; $bcc = '#aaaaaa';} else 
                        if($lp == 1) {$assesstype = 'Total Assessment'; $fld = 'total'; $bcc = '#666666';} else 
                        if($lp == 2) {$assesstype = 'Merged PI'; $fld = 'total'; $bcc = '#000000';} else 
                        if($lp == 3) {$assesstype = 'Behavioural Assessment'; $fld = 'behave'; $bcc = 'var(--darker)';} 
                        $txt = $assesstype . ' for ' . $subname . ' on class ' . $clsf . ' | ' . $secf;
                            
                            
                            if($lp == 0){
                                $pibicount=0;
                                $sql242frs = "SELECT topicid as cnt FROM pibientry where sessionyear='$sy' and classname='$clsf' and sectionname = '$secf' and exam ='$exam' and subcode ='$subcode' and sccode = '$sccode' and assesstype = 'Continious Assessment' and assessment>0 group by topicid"; 
                                $result242frs = $conn->query($sql242frs); if ($result242frs->num_rows > 0) {while($row242frs = $result242frs->fetch_assoc()) {
                                //$pibicount=$row242frs['cnt']; 
                                $pibicount++;}} else {$pibicount = 0;}
                            } else if($lp == 1 || $lp == 2) {
                                $sql242frs = "SELECT count(*) as cnt FROM pibitopics where sessionyear='$sy' and class='$clsf' and exam ='$exam' and subcode ='$subcode' and $fld = 1 "; 
                                $result242frs = $conn->query($sql242frs); if ($result242frs->num_rows > 0) {while($row242frs = $result242frs->fetch_assoc()) {
                                $pibicount=$row242frs['cnt']; }}
                            } else if($lp == 3){
                                $pibicount = 10;
                            } 
                                
                            $req = $pibicount * $stcount;
                            
                            $sql242fra = "SELECT count(*) as cnt FROM pibientry where sessionyear='$sy' and sccode='$sccode' and classname='$clsf' and sectionname = '$secf' and exam ='$exam' and subcode ='$subcode' and assesstype = '$assesstype'  and assessment>0 "; 
                            $result242fra = $conn->query($sql242fra); if ($result242fra->num_rows > 0) {while($row242fra = $result242fra->fetch_assoc()) {
                            $pibidone=$row242fra['cnt']; }}
                            $rate = ceil($pibidone * 100 / $req);
                            if($rate >= 100){if($req-$pibidone <= 0){$rate = 100;} else {$rate = 99;} }
                            if($rate == 100){$sta = 1;} else {$sta = 0;}
                            
                        $sql242frax = "SELECT * FROM pibiprocess where sessionyear='$sy' and classname='$clsf' and sectionname = '$secf' and exam ='$exam' and subcode ='$subcode' and assess = '$assesstype' and sccode = '$sccode'"; 
                        //echo $sql242frax;
                        $result242frax = $conn->query($sql242frax); if ($result242frax->num_rows > 0) {while($row242frax = $result242frax->fetch_assoc()) { $iid=$row242frax['id'];
                            $query3 ="UPDATE pibiprocess set jobdone='$pibidone', jobreq='$req', jobrate='$rate' where id='$iid';";
                            //echo $query3;
                            
                        }} else {
                            $query3 ="insert into pibiprocess (id, sessionyear, sccode, exam, classname, sectionname, subcode, subname, assess, txt, jobdone, jobreq, jobrate, status, jobby, jobdate) values 
                            (NULL, '$sy', '$sccode', '$exam', '$clsf','$secf','$subcode', '$subname', '$assesstype', '$txt', '$pibidone', '$req', '$rate', '$sta', '$usr', '$cur')";
                        }
                        //echo $query3;
                        $conn->query($query3) ;
                    }
                    
                    
                    
                    
                    
                    
                      }  else {
                        $exams = $exam; $entrydone = 0; $stcount = $jex;
                        $sql242frag = "SELECT count(subj) as sss, count(obj) as ooo, count(pra) as pra FROM stmark where sessionyear='$sy' and classname='$clsf' and sectionname = '$secf' and exam ='$exams' and subject ='$subcode' and sccode = '$sccode' and markobt>0 "; 
                        $result242frag = $conn->query($sql242frag); if ($result242frag->num_rows > 0) {while($row242frag = $result242frag->fetch_assoc()) {
                        $sss=$row242frag['sss']; $ooo=$row242frag['ooo']; $pra=$row242frag['pra']; }}
                            //if($ss>0){$entrydone = $entrydone + $sss;}
                            //if($oo>0){$entrydone = $entrydone + $ooo;}
                            //if($pp>0){$entrydone = $entrydone + $pra;}
                            $entrydone = $sss;
                        $assesstype = 'Total Exam';
                        $txt = $assesstype . ' for ' . $subname . ' on class ' . $clsf . ' | ' . $secf;
                        $req = $stcount;// * $en;  
                        //echo $en . '*' . $req;;
                        $rate = ceil($entrydone * 100 / $req);
                        if($rate == 100){$sta = 1;} else {$sta = 0;}
                        if($rate == 100){if($req-$entrydone == 0){$rate = 100;} else {$rate = 99;} }
                            
                        $query3 ="insert into pibiprocess (id, sessionyear, sccode, exam, classname, sectionname, subcode, subname, assess, txt, jobdone, jobreq, jobrate, status, jobby, jobdate) values 
                        (NULL, '$sy', '$sccode', '$exam', '$clsf','$secf','$subcode', '$subname', '$assesstype', '$txt', '$entrydone', '$req', '$rate', '$sta', '$usr', '$cur')";
                        $conn->query($query3) ;
                          
                      }
                      
                      
                      
                      
                    
                    
                    
                    
            }}
                        if($clsf == 'Six' || $clsf == 'Seven'){
                        $txt = 'Merged BI Assessment for class ' . $clsf . ' | ' . $secf;
                        $req = $stcount * 100; $rate = 0; $sta = 0;
                        
                        $query3dY ="DELETE FROM pibiprocess where sessionyear = '$sy' and sccode = '$sccode' and exam = '$exam' and classname = '$clsf' and sectionname = '$secf' and subcode = '100' and assess = 'Merged BI'";
                        $conn->query($query3dY) ;
                        //echo $query3dY;
                        $sql242xr = "select count(*) as ccnt from pibientry where sessionyear = '$sy' and sccode = '$sccode' and exam = '$exam' and classname = '$clsf' and sectionname = '$secf' and subcode=100 and assesstype='Merged BI'";
                        //echo $sql242xr; 
                        $result242xr = $conn->query($sql242xr); if ($result242xr->num_rows > 0) {while($row242xr= $result242xr->fetch_assoc()) {
                        $jd=$row242xr['ccnt'] *10;}}
                        
                        $rate = $jd * 100 / $req;
                        if($rate == 100){$sta = 1;}
                        
                        
                        
                        
                        $query3d ="insert into pibiprocess (id, sessionyear, sccode, exam, classname, sectionname, subcode, subname, assess, txt, jobdone, jobreq, jobrate, status, jobby, jobdate) values 
                        (NULL, '$sy', '$sccode', '$exam', '$clsf','$secf','100', 'Behavioural Assessment', 'Merged BI', '$txt', '$jd', '$req', '$rate', '$sta', '$usr', '$cur')";
                        $conn->query($query3d) ;
                        }
        }
        
                        
        
            ?>
            <div style="text-align:center; margin-bottom:8px;  background:var(--darker); color:var(--lighter);padding:5px 0 10 0; font-size:16px; font-weight:600;">
                Job Progress Report
                <br>
                <small style="color:white; font-size: 11px; font-weight:400; padding-bottom:10px;"><?php echo 'Report for ' . $exam . ' of ' . $clsf . ' | ' . $secf ;?></small>
                
                </div>
            
            
            <?php
            $tjob = 0; $tdon = 0; $checkmerge = 1; $a1 = 1; $a2 = 1; $newsub = '';
            $sql242x = "select * from pibiprocess where sessionyear = '$sy' and sccode = '$sccode' and exam = '$exam' and classname = '$clsf' and sectionname = '$secf'";
            $result242x = $conn->query($sql242x); if ($result242x->num_rows > 0) {while($row242x= $result242x->fetch_assoc()) {
            $subcode=$row242x['subcode'];  $subname = $row242x['subname'];  $assess = $row242x['assess'];  
            $txt = $row242x['txt'];  $done = $row242x['jobdone'];  $req = $row242x['jobreq'];  $rate = $row242x['jobrate'];  $status = $row242x['status'];  $idl = $row242x['id']; 
            $tjob = $tjob + $req; $tdon = $tdon + $done;          
                        if($assess == 'Continious Assessment'){$a1 = $status; $sub1 = $subcode; }
                        if($assess == 'Total Assessment'){$a2 = $status; $sub2 = $subcode; }
                        if($assess == 'Merged PI'){if($sub1 == $sub2){$a3 = $a1*$a2;} else {$a3 = 0;} } else {$a3 = 0;}
                        
                        
                        
                        $r = 100 - $rate * 1;
                        $b = $rate * 2.5;
                        $cgl = 'rgb(' . $r . ', 0, ' . $b . ')';
                        
                        if($assess == 'Continious Assessment'){$fld = 'assignment_turned_in'; $bcc = '#aaaaaa';} else 
                        if($assess == 'Total Assessment'){$fld = 'assignment_turned_in'; $bcc = '#666666';} else 
                        if($assess == 'Merged PI'){$fld = 'assignment_turned_in'; $bcc = '#000000';} else 
                        if($assess == 'Merged BI'){$fld = 'bookmark'; $bcc = 'purple';} else 
                        if($assess == 'Total Exam'){$fld = 'book'; $bcc = 'teal';} else 
                        if($assess == 'Behavioural Assessment'){$fld = 'bookmark'; $bcc = 'deeppink';}
     
                        if($rate >= 100 ){$fld2 = 'check_circle'; $clr2 = 'seagreen'; $bcc = 'seagreen;';} else 
                        if($rate == 0){$fld2 = 'donut_small'; $clr2 = 'red';} else 
                              {$fld2 = 'watch_later'; $clr2 = $cgl;}
                        
                        if($status == 1 ){$fld2 = 'check_circle'; $clr2 = 'seagreen'; $bcc = 'seagreen;';}
                        
                        $rw =0;
                        if($clsf=='Six' || $clsf=='Seven'){
                            
                           
            ?>
                <table class="table table-zebra" style="width:100%">
       
                    <tr>
                        <td style = "width:40px; color:<?php echo $bcc;?>"><i class="material-icons ico" style="color:<?php echo $bcc;?>"><?php echo $fld;?></i></td>
                        <td style="color:<?php echo $bcc;?>">
                            <b><?php echo $subname;?></b><br><span style="font-size:10px; font-style:italic; color:<?php echo $bcc;?>;"><?php echo $txt;?></span>
                        </td>
                        <td style = "width:60px;color:<?php echo $bcc;?>"><?php echo $done . ' / ' . $req;?></td>
                        <td style = "width:30px;color:<?php echo $bcc;?>"><?php echo $rate;?>%</td>
                        <td style = "width:40px;color:<?php echo $bcc;?>"><i class="material-icons ico" style="color:<?php echo $clr2;?>"><?php echo $fld2;?></i></td>
                    </tr>
                    <?php if($rate>0 ){ ?>
                    <tr>
                        <td></td><td colspan="3">
                            <div >
                                <div style="width:<?php echo $rate;?>%; height:10px; background-color:<?php echo $bcc;?>;"></div>
                            </div>
                        </td>
                        <td></td>
                    </tr>
                    <?php } ?>
                    <!--
                    <?php if($done>0 && $assess!='Merged PI' && ($clsf=='Six'|| $clsf=='Seven')){ ?>
                    <tr>
                        <td></td>
                        <td colspan="4">
                            <a class="btn btn-dark" href="markpibi.php?exam=<?php echo $exam;?>&cls=<?php echo $clsf;?>&sec=<?php echo $secf;?>&sub=<?php echo $subcode;?>&assess=<?php echo $assess;?>">Check Entry</a>
                        </td>
                    </tr>
                    <?php } ?>
                    -->
                    
                    
                    
                    <?php if($a3==1 ){ if($rate==100){$bt = 'success';} else {$bt = 'danger';}?>
                    <tr>
                        <td></td>
                        <td colspan="4">
                            <button class="btn btn-<?php echo $bt;?>" id="merge<?php echo $idl;?>" onclick="merge(<?php echo $idl;?>, 1);">Merge PI</button>
                            <span id="idl<?php echo $idl;?>"></span>
                            <span id="idl2<?php echo $idl;?>"></span>
                        </td>
                    </tr>
                    <?php } ?>
                    
                    
                        <tr>
                            <td style = "width:40px;"></td>
                            <td colspan="3" style=" font-size:10px; font-style:italic; color:red;">
                                <?php if($status==0 && $req == 0 ){ ?>
                                You didn't touch any topics for <b>Continious Assessment</b>.<br>
                                
                                <?php } ?>
                                
                                <?php if($clsf=='Eight'|| $clsf=='Nine' || $clsf == 'Ten'){ ?>
                                    <a class="btn btn-dark" href="markentry.php?exam=<?php echo $exam;?>&cls=<?php echo $clsf;?>&sec=<?php echo $secf;?>&sub=<?php echo $subcode;?>&assess=<?php echo $assess;?>">Check Entry</a>
                                <?php } else { 
                                    if(substr($assess,0,6) != 'Merged'){ ?>
                                        <?php if($status==00){?>
                                        <button class="btn btn-danger" id="btn<?php echo $idl;?>" style="margin-top:5px;" onclick="dones(<?php echo $idl;?>);">Mark As Complete</button>
                                        <?php } ?>
                                        <a class="btn btn-dark" style="font-style:normal;" href="markpibi.php?exam=<?php echo $exam;?>&cls=<?php echo $clsf;?>&sec=<?php echo $secf;?>&sub=<?php echo $subcode;?>&assess=<?php echo $assess;?>">Entry</a>
                                <?php }}
                                
                                if($assess=='Merged BI'){?>
                                    <button class="btn btn-warning" id="merge<?php echo $idl;?>" onclick="merge(<?php echo $idl;?>, 2);">Merge BI</button>
                            <span id="idl<?php echo $idl;?>"></span>
                                <?php } 
                                ?>
                            </td>
                            <td></td>
                        </tr>
                    
                </table>
            
            <?php
            $newsub = $subname;
            
            $rw++;
            if($rw%4 == 0){
                echo '<div style="height:30px;"></div>';
            }
            
                        } else {
?>

                <table class="table table-zebra" style="width:100%">
                    <tr>
                        <td style = "width:40px; color:<?php echo $bcc;?>"><i class="material-icons ico" style="color:<?php echo $bcc;?>"><?php echo $fld;?></i></td>
                        <td style="color:<?php echo $bcc;?>">
                            <b><?php echo $subname;?></b><br><span style="font-size:10px; font-style:italic; color:<?php echo $bcc;?>;"><?php echo $txt;?></span>
                        </td>
                        <td style = "width:60px;color:<?php echo $bcc;?>"><?php echo $done . ' / ' . $req;?></td>
                        <td style = "width:30px;color:<?php echo $bcc;?>"><?php echo $rate;?>%</td>
                        <td style = "width:40px;color:<?php echo $bcc;?>"><i class="material-icons ico" style="color:<?php echo $clr2;?>"><?php echo $fld2;?></i></td>
                    </tr>
                    <?php if($rate>0 ){ ?>
                    <tr>
                        <td></td><td colspan="3">
                            <div >
                                <div style="width:<?php echo $rate;?>%; height:10px; background-color:<?php echo $bcc;?>;"></div>
                            </div>
                        </td>
                        <td></td>
                    </tr>
                    <?php } ?>
                    <!--
                    <?php if($done>0 && $assess!='Merged PI' && ($clsf=='Six'|| $clsf=='Seven')){ ?>
                    <tr>
                        <td></td>
                        <td colspan="4">
                            <a class="btn btn-dark" href="markpibi.php?exam=<?php echo $exam;?>&cls=<?php echo $clsf;?>&sec=<?php echo $secf;?>&sub=<?php echo $subcode;?>&assess=<?php echo $assess;?>">Check Entry</a>
                        </td>
                    </tr>
                    <?php } ?>
                    -->
                    
                    
                    
                    <?php if($a3==1 ){ if($rate==100){$bt = 'success';} else {$bt = 'danger';}?>
                    <tr>
                        <td></td>
                        <td colspan="4">
                            <button class="btn btn-<?php echo $bt;?>" id="merge<?php echo $idl;?>" onclick="merge(<?php echo $idl;?>, 1);">Merge PI</button>
                            <span id="idl<?php echo $idl;?>"></span>
                        </td>
                    </tr>
                    <?php } ?>
                    
                    
                        <tr>
                            <td style = "width:40px;"></td>
                            <td colspan="3" style=" font-size:10px; font-style:italic; color:red;">
                                <?php if($status==0 && $req == 0 ){ ?>
                                You didn't touch any topics for <b>Continious Assessment</b>.<br>
                                
                                <?php } ?>
                                
                                <?php if($clsf=='Eight'|| $clsf=='Nine' || $clsf == 'Ten'){ ?>
                                    <a class="btn btn-dark" href="markentry.php?exam=<?php echo $exam;?>&cls=<?php echo $clsf;?>&sec=<?php echo $secf;?>&sub=<?php echo $subcode;?>&assess=<?php echo $assess;?>">Check Entry</a>
                                <?php } else { 
                                    if(substr($assess,0,6) != 'Merged'){ ?>
                                        <?php if($status==00){?>
                                        <button class="btn btn-danger" id="btn<?php echo $idl;?>" style="margin-top:5px;" onclick="dones(<?php echo $idl;?>);">Mark As Complete</button>
                                        <?php } ?>
                                        <a class="btn btn-dark" style="font-style:normal;" href="markpibi.php?exam=<?php echo $exam;?>&cls=<?php echo $clsf;?>&sec=<?php echo $secf;?>&sub=<?php echo $subcode;?>&assess=<?php echo $assess;?>">Entry</a>
                                <?php }}
                                
                                if($assess=='Merged BI'){?>
                                    <button class="btn btn-warning" id="merge<?php echo $idl;?>" onclick="merge(<?php echo $idl;?>, 2);">Merge BI</button>
                            <span id="idl<?php echo $idl;?>"></span>
                                <?php } 
                                ?>
                            </td>
                            <td></td>
                        </tr>
                    
                </table>


<?php
             

                        }
            }} else {
                $tjob = 0; $tdon = 0; echo 'No Record Found';
            }
            
            
            $recstcount = $lastroll - $firstroll + 1;
           
            if($clsf=='Eight'|| $clsf=='Nine' || $clsf == 'Ten'){
                include 'resulttotalprocess.php';
              
            } else {
                include 'resulttotalprocesspibi.php';
            }
            $ses = floor($tdon * 100 / $tjob);
    

        $sql00xgrx0 = "update areas set half = '$tjob' where id='$jax'"; 
        $conn->query($sql00xgrx0) ;
    //////////////////////////////////////

	?>
	
	
	  <script>
      document.getElementById("perc").innerHTML = '<?php echo $ses;?> %';
  </script>