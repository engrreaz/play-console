    

<?php  echo $clsf . $secf . $exam;?>
    
    
    
    <script>
    
        function totalprocess(roll){
            //document.getElementById("hd").style.display = 'none';
            document.getElementById("two").style.display = 'block';
            $("#iq").html( "" );
            $("#pux").html( "" );
            //fetchstd();
            markret2(roll);
        }
    </script>         
             
    
    
    <script>
    
        function markret() {
            document.getElementById("twos2").style.width = '0%';
    		var exam='<?php echo $exam;?>'; 
    		var classname='<?php echo $clsf;?>';
    		var sectionname='<?php echo $secf;?>';
    		var sccode='<?php echo $sccode;?>'; 
    		var stcnt=<?php echo $recstcount;?>;
    		var st=<?php echo $firstroll;?>;
    		var en= <?php echo $lastroll;?>;
    		en = 100;
        	let xx;
        	document.getElementById("fromto").innerHTML = ' ( Roll From ' + st + ' To ' + en + ' ) ';
        	let peep = st;
        	for(xx=st; xx<=en; xx++){
        	    var start=xx;
            	var infor= "classname=" + classname +  "&p=q&sectionname=" + sectionname  +  "&sccode=" + sccode +  "&stcnt=" + stcnt  +  "&exam=" + exam   +  "&start=" + start    ;
            	
            	$("#iq").append(peep + ". " + infor + "<hr>");
            	
            	document.getElementById("twos2").innerHTML = '';
            	$("#twot").html( "" );
            
            	 $.ajax({
            			type: "POST",
            			url: "markretpibi.php",
            			data: infor,
            			cache: false,
            			beforeSend: function () { 
            				$('#twot').html('~~~~~~~~~~~~~~~~~~');
            			},
            			success: function(html) {    
            			    
            				$("#twot").html( html ); 
            				let re = document.getElementById("ccc").innerHTML;
            				let rex = parseInt(re, 10);
            				//document.getElementById("twos").innerHTML = re ;
            				let rt = parseInt(100 * peep /(en-st+1));
            				document.getElementById("twos2").style.width  = rt + "%";
            				
            				
                                    document.getElementById("pux").innerHTML = peep + ' Students Processing Done. Process Continue...' ;
                                    
                                    if(peep==en){
                                        document.getElementById("proico").innerHTML = '<i class="material-icons ico"  style="color:seagreen;">check_circle</i>';
                                        document.getElementById("ccc").innerHTML = '' ;
                                        setTimeout(function(){
                                            document.getElementById("pux").innerHTML = en + ' (All) Students Counting Done.' ;
                                            document.getElementById("twos2").style.width  =  '100%';
                                           // calctotal();
                                        }, 1000);
                                    }
                                    peep++;
                                    
                                    
                                    
                                    
            				// setTimeout(function(){  }, 1000);
                                    
                            
            			}
            		});
            		
            		
            		//print str_pad('', intval(ini_get('output_buffering')))."\n";

            	//setTimeout(function(){document.getElementById("twos2").innerHTML = '';  }, 10);	
        	}
        		
        	
            	
        }
        </script>
        
        
        
        
        <script>
    
        function markret2(roll) {
            document.getElementById("twos2").style.width = '0%';
    		var exam='<?php echo $exam;?>'; 
    		var classname='<?php echo $clsf;?>';
    		var sectionname='<?php echo $secf;?>';
    		var sccode='<?php echo $sccode;?>'; 
    		var stcnt=<?php echo $recstcount;?>;
    		var last=<?php echo $lastroll;?>;
    		var st=roll;
    		var en= roll;
    		var perc = parseInt(100/stcnt);
    		//alert(stcnt + "/" + st);
    		if(last>=roll){
        	let xx;
        	document.getElementById("fromto").innerHTML = ' ( Roll From ' + st + ' To ' + en + ' ) ';
        	var mot = parseInt(document.getElementById("ones").innerHTML) ;
        	var slotperc = 100/mot; var wid = 0;
        	let peep = st;
        	for(xx=st; xx<=en; xx++){
        	    var start=xx;
            	var infor= "classname=" + classname +  "&p=q&sectionname=" + sectionname  +  "&sccode=" + sccode +  "&stcnt=" + stcnt  +  "&exam=" + exam   +  "&start=" + start    ;
            	
            	//$("#iq").append(peep + ". " + infor + "<hr>");
            	
            	document.getElementById("ccnntt").innerHTML = parseInt(document.getElementById("ccnntt").innerHTML) + 1;
            	document.getElementById("twos2").innerHTML = '';
            	$("#twot").html( "" );
            
            	 $.ajax({
            			type: "POST",
            			url: "markretpibi.php",
            			data: infor,
            			cache: false,
            			beforeSend: function () { 
            				$('#twot').html('~~~~~~~~~~~~~~~~~~');
            			},
            			success: function(html) {    
            			    
            				$("#twot").html( html ); 
            				let re = document.getElementById("ccc").innerHTML;
            				let rex = parseInt(re, 10);
            				//document.getElementById("twos").innerHTML = re ;
            				let rt = parseInt(100 * peep /(en-st+1));
            				document.getElementById("twos2").style.width  = rt + "%";
            				
            				
                                    document.getElementById("pux").innerHTML = peep + ' Students Processing Done. Process Continue...' ;
                                    
                                    if(peep==en){
                                        document.getElementById("proico").innerHTML = '<i class="material-icons ico"  style="color:seagreen;">check_circle</i>';
                                        document.getElementById("ccc").innerHTML = '' ;
                                        setTimeout(function(){
                                            document.getElementById("pux").innerHTML = en + ' (All) Students Counting Done.' ;
                                            document.getElementById("twos2").style.width  =  '100%';
                                           // calctotal();
                                        }, 1000);
                                    }
                                    peep++;
                                    
                                    
                                    
                                    
            				// setTimeout(function(){  }, 1000);
                                    
                            
            			}
            		});
            		
            		
            		//print str_pad('', intval(ini_get('output_buffering')))."\n";

            	//setTimeout(function(){document.getElementById("twos2").innerHTML = '';  }, 10);	
            	
            	wid = parseInt(document.getElementById("ccnntt").innerHTML) * slotperc;
            	document.getElementById("prog100").style.width  =  wid+'%';
            	
        	}
        		
        }	else {
            document.getElementById("pux").innerHTML = en + ' (All) Students Counting Done.' ;
                                            document.getElementById("twos2").style.width  =  '100%';
        }
            	
        }
        </script>
        
        
        
        
        
        
        
        
        
        
    <script>
        function calctotal() {	
            document.getElementById("two3").style.display = 'block';
            document.getElementById("two3").innerHTML = 'Process Continue...';
            var exam='Half Yearly'; 
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
        			$("#two3").html( 'Process Done.<br><button class="btn btn-info" id="process" style="margin-top:5px;" onclick="view();">View</button><br><br>' ); 
        			document.getElementById("proico3").innerHTML = '<i class="material-icons ico"  style="color:seagreen;">check_circle</i>';
        			}
        		});
        		
        
        }
        </script>
        <script>
        function view(){
            let exam='<?php echo $exam;?>'; 
            document.location.href = 'showtabulatingsheetpibi.php?classname=<?php echo $clsf;?>&sectionname=<?php echo $secf;?>&exam=' + exam;
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
                                Total Students Found :
                                <span id="ones" style="font-weight:700; color: purple;" class="sub"><?php echo $recstcount;?></span>
                                <span id="fromto" style="font-weight:700; color: black;" class="sub"></span>
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
                            <button class="btn btn-danger" id="process" style="margin-top:5px;" onclick="totalprocess();">Process Result</button>
                            <button class="btn btn-warning" id="process" style="margin-top:5px;" onclick="totalprocess(<?php echo $firstroll;?>);">Process Result - II</button>
                            <button class="btn btn-dark" id="process2" style="margin-top:5px;" onclick="view();">View</button>
                        </div>
                        <div id="two" style="display:none;">
                            <div id="twot" style="color:red; font-weight:bold;" class="tit">Retriving Assessments...</div>
                            <div id="pux" style="color:seagreen; font-weight:bold;" class="tit">________</div>
                            <div id="twos" class="sub" >
                            </div>
                            <div id="twosx" class="sub">
                                <div id="twos2" style="background:seagreen; height:3px; width:56%;"></div>
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
                            
                            <div id="twos3" class="sub" hidden>
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
            
             <div id="prog100" style="background:deeppink; height:3px; width:10%;"></div>
             <div id="ccnntt">0</div>
            
            
        </div>
    </div>
             
             
             
             
    
    <div id="iq"></div>
