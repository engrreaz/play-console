
    
   
    
    
    
    <?php
     $sql242x = "select * from pibientry where sessionyear = '$sy' and sccode = '$sccode' and exam = '$exam' and subcode='$subcode' and classname = '$clsf' and sectionname = '$secf' and assesstype='$assess' order by entrytime desc limit 1 ";
            //echo $sql242x; 
            $result242xb = $conn->query($sql242x); if ($result242xb->num_rows > 0) {while($row242x= $result242xb->fetch_assoc()) {
            $lastentry=$row242x['entrytime']; 
            
            }} else {
                $lastentry = 'No Entry Found.';
            }
            ?>
    
    
                <table class="table table-zebra" style="width:100%; margin-top:0; ">
       
                    <tr>
                        
                        <td style="color:<?php echo $bcc;?>;  width:40px;">
                            
                        </td>
                        <td style = "color:<?php echo $bcc;?>; font-size:13px;" onclick="sho(<?php echo $idl;?>);">
                            <?php echo $assess ;?>
                            
                            <div style="margin-top:0px;">
                                <div id="req<?php echo $idl;?>" hidden ><?php echo $req;?></div>
                                
                            
                            </div>
                            
                            <?php if($rate>=0 ){ ?>
                            <div style="background:lightgray;">
                                <div id="prog<?php echo $idl;?>" style="width:<?php echo $rate;?>%; height:3px; background-color:<?php echo $bcc;?>; margin-top:3px;"></div>
                            </div>
                            
                            <?php } ?>
                            <div  style="float:left; margin-top:5px;">
                                    <?php echo '<span id="h' . $idl . '">' . $done . ' / ' . $req . ' <b>(' . $rate . '%)</b></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>@ ' . date('d/m/Y H:i:s', strtotime($lastentry));  ;?>
                                </div>
                            
                        </td>
                        <td style = "width:40px;color:<?php echo $bcc;?>; ;"><i class="material-icons ico" style="color:<?php echo $clr2;?>"><?php echo $fld2;?></i></td>
                    </tr>
                 
                            
               
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
                    
                    
                    <tr id="box1<?php echo $idl;?>" hidden="hidden" >
                    <?php if($a3==1 ){ if($rate==100){$bt = 'success';} else {$bt = 'danger';}?>
                    
                        <td style="width:40px;"></td>
                        <td >
                            <button class="btn btn-<?php echo $bt;?>" id="merge<?php echo $idl;?>" onclick="merge(<?php echo $idl;?>, 1);">Merge PI</button>
                            <span id="idl<?php echo $idl;?>"></span>
                            <span id="idl2<?php echo $idl;?>"></span>
                            <span id="idl3<?php echo $idl;?>" style="font-size:10px; font-weight:bold;"></span>
                        </td>
                        <td style="width:40px;"></td>
                    
                    <?php } ?>
                    </tr>
                    
                    <tr id="box2<?php echo $idl;?>" hidden="hidden" >
                        <td style = "width:40px; border:0px solid red;"></td>
                        <td  style=" font-size:10px; font-style:italic; color:red; border:0px solid red;">
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
                                    <a class="btn btn-dark" style="font-style:normal;" target="_balnk" href="markpibi.php?exam=<?php echo $exam;?>&cls=<?php echo $clsf;?>&sec=<?php echo $secf;?>&sub=<?php echo $subcode;?>&assess=<?php echo $assess;?>">Entry</a>
                            <?php }}
                            
                            if($assess=='Merged BI'){?>
                            
                                <button class="btn btn-warning" id="merge<?php echo $idl;?>" onclick="merge(<?php echo $idl;?>, 2);">Merge BI</button>
                        <span id="idl<?php echo $idl;?>"></span>
                        <span id="idl2<?php echo $idl;?>"></span>
                        <span id="idl3<?php echo $idl;?>"></span>
                            <?php } 
                            ?>
                        </td>
                        <td style="border:0px solid red;width:40px;"></td>
          
                    </tr>

                </table>