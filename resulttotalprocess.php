   
        
    <script>
    
        function totalprocess(){
            document.getElementById("hd").style.display = 'none';
            document.getElementById("two").style.display = 'block';
            //fetchstd();
            markret();
        }
    </script>         
             
    
    
    <script>
        function markret() {
            document.getElementById("twos2").style.width = '1%';
    		var exam='<?php echo $exam;?>'; 
    		var classname='<?php echo $clsf;?>';
    		var sectionname='<?php echo $secf;?>';
    		var sccode='<?php echo $sccode;?>'; 
    		var stcnt=<?php echo $recstcount;?>;
    		var st=<?php echo $firstroll;?>;
    		var en=<?php echo $lastroll;?>;
    		var part = document.getElementById('partpart').value;
    // 		alert(part);
        	let xx;
        	
        // 	st = 1; en = 1;
        	for(xx=st; xx<=en; xx++){
        	    var start=xx;
            	var infor= "classname=" + classname +  "&sectionname=" + sectionname  +  "&sccode=" + sccode +  "&stcnt=" + stcnt  +  "&exam=" + exam   +  "&start=" + start  + "&part=" + part ;
            // 	alert(infor);
            	$("#twot").html( "" );
            
            	 $.ajax({
            			type: "POST",
            			url: "markret.php",
            			data: infor,
            			cache: false,
            			beforeSend: function () { 
            				$('#twot').html('...');
            			},
            			success: function(html) {    
            			    
            				$("#twot").html( html ); 
            				let re = document.getElementById("ccc").innerHTML;
            				let rex = parseInt(re, 10);
            				//document.getElementById("twos").innerHTML = re ;
            				
            				document.getElementById("twos2").style.width  = parseInt(100 * rex /stcnt) + "%";
                            if(rex==stcnt){
                                document.getElementById("twos2").style.width  = parseInt(100) + "%";
                                document.getElementById("proico").innerHTML = '<i class="material-icons ico"  style="color:seagreen;">check_circle</i>';
                                document.getElementById("ccc").innerHTML = '' ;
                                setTimeout(function(){
                                    calctotal();
                                }, 1000);
                            }
            			}
            		});
            		//print str_pad('', intval(ini_get('output_buffering')))."\n";
        	}
        }
        
        
               </script>
    <script> 
        
        
        function calctotal() {	
            document.getElementById("two3").style.display = 'block';
            document.getElementById("two3").innerHTML = 'Process Continue...';
            var exam='<?php echo $exam;?>'; 
    		var classname='<?php echo $clsf;?>';
    		var sectionname='<?php echo $secf;?>';
    		var sccode='<?php echo $sccode;?>'; 
    		var stcnt=<?php echo $stcount;?>;
       		var infor= "classname=" + classname  +  "&sectionname=" + sectionname  +  "&sccode=" + sccode +  "&stcnt=" + stcnt  +  "&exam=" + exam   ;
        	//alert(infor);
        	$("#twot3").html( "" );
        
        	 $.ajax({
        			type: "POST",
        			url: "calctotal.php",
        			data: infor,
        			cache: false,
        			beforeSend: function () {
        				$('#twot3').html('<span class="button cycle-button success"><span class="mif-spinner4 mif-ani-pulse"></span></span> Calculating...... Please Wait, Processing...');
        			},
        			success: function(html) {   
        				$("#twot3").html( html );
        			$("#two3").html('Process Done.<br><button class="btn btn-info" id="process" style="margin-top:5px;" onclick="view();">View</button><br><br>'); 
        			document.getElementById("proico3").innerHTML = '<i class="material-icons ico"  style="color:seagreen;">check_circle</i>';
        			}
        		});
        }
        </script>
    <script>
        function view(){
            document.location.href = 'showtabulatingsheet.php?classname=<?php echo $clsf;?>&sectionname=<?php echo $secf;?>&exam=<?php echo $exam;?>';
        }
    </script>
    
    
    
    
    <button class="btn btn-warning" id="process" style="margin-top:5px; display:none;" onclick="calctotal();">Calculation</button>
    
    
    <div class="card" style="background:#eee; color:var(--darker);" onclick="lnk1();" >
        <div class="card-body">
            <table style="width:100%">
                <tr>
                    <td>
                        <div id="one">
                            <div id="onet" class="tit">
                                <?php  echo $clsf . $secf . $exam;?>
                                Total Students Found
                                <span id="ones" style="font-weight:700; color: purple;" class="sub"><?php echo $recstcount ;?></span>
                            </div>
                        </div>
                    </td>
                    <td style="width:40px; text-align:right;">
                        <i class="material-icons ico"  style="color:seagreen;">check_circle</i>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div id="hd">
                            <button class="btn btn-success" id="process" style="margin-top:5px;" onclick="totalprocess();">Process Result</button>
                            <button class="btn btn-info" id="process" style="margin-top:5px;" onclick="view();">View</button>
                        </div>
                        <div id="two" style="display:none;">
                            <div id="twot" style="color:red;" class="tit">Retriving marks...</div>
                            <div id="twos" class="sub" >
                            </div>
                            <div id="twosx" class="sub">
                                <div id="twos2" style="background:seagreen; height:10px; width:1%;"></div>
                            </div>
                        </div>
                    </td>
                    <td style="width:40px;  text-align:right;" id="proico" >
                        
                    </td>
                </tr>
                <tr>
                    <td>
                        <div id="hd3">
                            
                        </div>
                        <div id="two3" style="display:none;">
                            <div id="twot3" style="color:red;" class="tit">Retriving marks...</div>
                            <div id="twos3" class="sub" >
                            </div>
                            <div id="twosx3" class="sub">
                                <div id="twos23" style="background:seagreen; height:10px; width:1%;"></div>
                            </div>
                        </div>
                    </td>
                    <td style="width:40px;  text-align:right;" id="proico3" >
                        
                    </td>
                </tr>
            </table>
        </div>
    </div>
             
             
       
       
    
    
                   
        