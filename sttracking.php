<?php

    $sql0w = "SELECT * FROM sttracking where sessionyear = '$sy' and stid='$userid' and date = '$td' order by subject, responsetime";
    $result0w = $conn->query($sql0w);
    if ($result0w->num_rows > 0) 
    {while($row0w = $result0w->fetch_assoc()) { 
        $dbid = $row0w["id"]; 
        $subcode = $row0w["subject"]; 
        $restm = $row0w["responsetime"];
        
        
        $sql0wd = "SELECT * FROM subjects where subcode = '$subcode' ";
        $result0wd = $conn->query($sql0wd);
        if ($result0wd->num_rows > 0) 
        {while($row0wd = $result0wd->fetch_assoc()) { 
            $suben = $row0wd["subject"]; 
            $subbn = $row0wd["subben"]; 
             
        }}
        
        ?>
         
        
        
        
        <div class="main-card gg card">
            <div class="card-body">
                <table style="width:100%">
                    <tr>
                        <td>
                            <div class="date" id="daydd">
                                <?php echo $suben; ?>
                            </div>
                            <small style="color:red;">
                                <?php echo $subbn; ?>
                            </small>
                            <?php if ($restm != NULL) { ?>
                                <div class="lable">Response @ <span id="rest">
                                        <?php echo $restm; ?>
                                    </span></div>
                            <?php } ?>
                        </td>
                        <td style=" text-align:right;">
                            <?php if ($restm != NULL) { ?>
                                <div style="font-size:36px; color:var(--dark);"><i class="bi bi-check-circle-fill"></i></div>
                            <?php } else { ?>
                                <div id="checkf<?php echo $dbid; ?>">
                                    <button style="font-size:12px;" onclick="dones(<?php echo $dbid; ?>, <?php echo $subcode; ?>, '<?php echo $suben; ?>');"
                                        class="btn btn-dark">Complete</button>
                                </div>
                            <?php } ?>
                        </td>
                    </tr>
                </table>
                            
            </div>
        </div>
        
        
        <?php 
    }} else {
        $sql0 = "SELECT * FROM subsetup where sessionyear = '$sy' and sccode='$sccode' and classname='$cls' and sectionname='$sec' order by subject";
        $result0ww = $conn->query($sql0);
        if ($result0ww->num_rows > 0) 
        {while($row0 = $result0ww->fetch_assoc()) { 
            $subcode = $row0["subject"];
            
            $query3x ="INSERT INTO sttracking (id, sessionyear, sccode, stid, classname, sectionname, rollno, date, subject, responsetime, distance)
                        VALUES (NULL, '$sy', '$sccode', '$stid', '$cls', '$sec', '$rollno', '$td', '$subcode', NULL, NULL);";
		    $conn->query($query3x);
		    
        }}
        ?>
		    <script>
		      //  window.location.href='index.php';
		        </script>
		    <?php
    }



?>


 <script>
    function dones(id,sub, sname) {
        var sms = "<?php echo $stnameeng . ' has been complete her task on ';?>" + sname ;
        
    	var infor="id=" + id + "&sub=" + sub + "&sms=" + sms + "&stid=<?php echo $userid;?>&stnamex=<?php echo $stnameeng;?>" ; 
    	//alert(infor);
        $("#checkf"+id).html( "" );
        $.ajax({
    		type: "POST",
    		url: "updtracking.php",
    		data: infor,
    		cache: false,
    		beforeSend: function () {
    			$('#checkf'+id).html('<i style="font-size:36px; color:red;" class="bi bi-cloud-arrow-up-fill"></i>');
    		},
    		success: function(html) {
    			$("#checkf"+id).html( html );
    			//window.location.href = 'index.php';
    		}
    	});
    }
</script>

