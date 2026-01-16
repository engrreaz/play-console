<style>


.card {
    box-shadow: 0 0.46875rem 2.1875rem rgba(4,9,20,0.03), 0 0.9375rem 1.40625rem rgba(4,9,20,0.03), 0 0.25rem 0.53125rem rgba(4,9,20,0.05), 0 0.125rem 0.1875rem rgba(4,9,20,0.03);
    border-width: 0;
    transition: all .2s;
}

.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: var(--lighter);
    background-clip: border-box;
    border: 1px solid rgba(26,54,126,0.125);
    border-radius: .25rem;
}

.card-body {
    flex: 1 1 auto;
    padding:1rem 1.25rem;
}
.vertical-timeline {
    width: 100%;
    position: relative;
    padding: .5rem 0 0;
}

.vertical-timeline::before {
    content: '';
    position: absolute;
    top: 0;
    left: 10px;
    height: 100%;
    width: 2px;
    background: lightgray;
    border-radius: .25rem;
}

.vertical-timeline-element {
    position: relative;
    margin: 0 0 1rem;
}

.vertical-timeline--animate .vertical-timeline-element-icon.bounce-in {
    visibility: visible;
    animation: cd-bounce-1 .8s;
}
.vertical-timeline-element-icon {
    position: absolute;
    top: 0;
    left: 1px;
}

.vertical-timeline-element-icon .badge-dot-xl {

}

.badge-dot-xl {
    width: 18px;
    height: 18px;
    position: relative;
}
.badge:empty {
    display: none;
}


.badge-dot-xl::before {
    content: '';
    width: 12px;
    height: 12px;
    border-radius: 50%;
    position: absolute;
    left: 50%;
    top: 50%;
    margin: -5px 0 0 -5px;
   background: #f00;
}




.vertical-timeline-element-content {
    position: relative;
    margin-left: 25px;
    font-size: .8rem;
}

.vertical-timeline-element-content .timeline-title {
    font-size: .8rem;
    text-transform: uppercase;
    margin: 0 0 ;
    padding: 2px 0 0;
    font-weight: bold;
}

.vertical-timeline-element-content .vertical-timeline-element-date {
    display: block;
    position: absolute;
    left: -90px;
    top: 0;
    padding-right: 10px;
    text-align: right;
    color: #adb5bd;
    font-size: .7619rem;
    white-space: nowrap;
}

.vertical-timeline-element-content:after {
    content: "";
    display: table;
    clear: both;
}

    .wd{width:45px;}
    
    .nmbr {font-size:30px; font-weight:bold;}
    .nmbr small {font-size:14px; font-weight:500;}
    
    
    
    .time{font-size:24px; font-weight:bold;}
    .date{font-weight:500; color:var(--darker);}
    .lable{}
    .progress-box{background:var(--light);}
    .progress-val{width:69%; height:10px; background:var(--darker);}
    
    .right{float:right;}
    .attnd{font-size:24px; font-weight:500; color: var(--darker); margin:0; position:relative; }
   
   a, a:hover, a:link, a:visited {text-decoration:none; font-size:16px; font-weight:500; color:var(--dark);}
   .lnk-text{display:inline-block; padding:7px 7px; font-size:15px; font-weight:700; text-transform:uppercase;}
</style>


        <div class="clearfix"></div>
        
        <div class="main-card gg card">
            
            <div class="card-body">
                <div class="time" id="time"></div>
                <div class="date" id="day"><?php echo date('l, d, F, Y');?></div>
                <small style="color:red;"> Weekend</small>
                <div class="lable">3rd Period<span id="rest"></span></div>
                <div class="progress-box">
                    <div class="progress-val" id="bar"></div>
                </div>
                
                
            </div>
        </div>
        
        
      <?php include 'notice.php';?>
        
        
        <style>
            .steng { font-size:16px; font-weight:700; color: var(--darker);  }
            .stben { font-size:16px; font-weight:500; color: gray;  }
            .stid { font-size:11px; font-weight:500; color: black;  }
            .cls { font-size:11px; font-weight:500; color: var(--dark);  }
            .lebel { font-size:12px; font-weight:500; color: black; padding-top:8px; }
            .fix { margin-bottom:8px; }
        </style>
          
         
        <?php 
        
        
        $sql0xffffffffffg = "SELECT * FROM students where guarmobile='$usrmobile' order by stid desc";
        $result0xffffffffffg = $conn->query($sql0xffffffffffg);
        if ($result0xffffffffffg->num_rows > 0) 
        {while($row0xffffffffffg = $result0xffffffffffg->fetch_assoc()) { 
            $stdid = $row0xffffffffffg["stid"];
            $stnameeng = $row0xffffffffffg["stnameeng"];
            $stnameben = $row0xffffffffffg["stnameben"];
            
            $sql0xffffffffffw = "SELECT * FROM sessioninfo where stid='$stdid' and sessionyear='$sy' LIMIT 1";
            $result0xffffffffffw = $conn->query($sql0xffffffffffw);
            if ($result0xffffffffffw->num_rows > 0) 
            {while($row0xffffffffffw = $result0xffffffffffw->fetch_assoc()) { 
                $cls = $row0xffffffffffw["classname"];
                $sec = $row0xffffffffffw["sectionname"];
                $roll = $row0xffffffffffw["rollno"];
            }}
            
            
            ?>
            
                <div class="main-card gb card">
                     
                     <table style="margin-left:30px; margin-top:20px;">
                         <tr>
                             <td style="width:75px;"><img src="<?php echo 'https://eimbox.com/students/' . $stdid . '.jpg';?>" style="width:64px; height:64px; border-radius:50%;" /></td>
                             <td>
                                 <div class="steng"><?php echo $stnameeng;?></div>
                                 <div class="stben"><?php echo $stnameben;?></div>
                                 <div class="stid">STID # <?php echo $stdid;?></div>
                                 <div class="cls">Class : <?php echo $cls;?> | Section : <?php echo $sec;?></div>
                                 <div class="cls">Roll : <?php echo $roll;?></div>
                             </td>
                         </tr>
                     </table>
                     
                     <table style="margin: 20px 60px 10px 30px;">
                         <tr>
                             <td style="text-align:center;">
                                 <div class="" onclick="bypass(<?php echo $stdid;?>, 1);">
                                    <svg class="fix" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-mortarboard-fill" viewBox="0 0 16 16">
                                    <path d="M8.211 2.047a.5.5 0 0 0-.422 0l-7.5 3.5a.5.5 0 0 0 .025.917l7.5 3a.5.5 0 0 0 .372 0L14 7.14V13a1 1 0 0 0-1 1v2h3v-2a1 1 0 0 0-1-1V6.739l.686-.275a.5.5 0 0 0 .025-.917l-7.5-3.5Z"/>
                                    <path d="M4.176 9.032a.5.5 0 0 0-.656.327l-.5 1.7a.5.5 0 0 0 .294.605l4.5 1.8a.5.5 0 0 0 .372 0l4.5-1.8a.5.5 0 0 0 .294-.605l-.5-1.7a.5.5 0 0 0-.656-.327L8 10.466 4.176 9.032Z"/>
                                    </svg><br><span class="lebel">Results</span>
                                 </div>
                             </td>
                             
                             <td style="text-align:center;">
                                 <div class="" onclick="bypass(<?php echo $stdid;?>, 3);">
                                    <svg class="fix" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-mortarboard-fill" viewBox="0 0 16 16">
                                    <path d="M8.211 2.047a.5.5 0 0 0-.422 0l-7.5 3.5a.5.5 0 0 0 .025.917l7.5 3a.5.5 0 0 0 .372 0L14 7.14V13a1 1 0 0 0-1 1v2h3v-2a1 1 0 0 0-1-1V6.739l.686-.275a.5.5 0 0 0 .025-.917l-7.5-3.5Z"/>
                                    <path d="M4.176 9.032a.5.5 0 0 0-.656.327l-.5 1.7a.5.5 0 0 0 .294.605l4.5 1.8a.5.5 0 0 0 .372 0l4.5-1.8a.5.5 0 0 0 .294-.605l-.5-1.7a.5.5 0 0 0-.656-.327L8 10.466 4.176 9.032Z"/>
                                    </svg><br><span class="lebel">Attendance</span>
                                 </div>
                             </td>
                             
                             <td style="text-align:center;">
                                 <div class="" onclick="bypass(<?php echo $stdid;?>, 3);">
                                    <svg  class="fix" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                                          <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
                                        </svg><br><span class="lebel">More</span>
                                 </div>
                             </td>
                         </tr>
                     </table>
                </div>
            
            <?php
            
        }}
        
        
        
        

            
        
        
        ?>
         
         
         
         
         
         
         
         
         
         
         
         
         
                 
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
                 <div style="height:50px;" >
      
        </div>
        
        
        
        
        <script>


            function bypass(id, fu){
                if(fu==1){
                    window.location.href = 'stguarresult.php?stid=' + id;
                } else if(fu==2){
                    window.location.href = 'stguarattnd.php?stid=' + id;
                } else if(fu==1){
                    window.location.href = 'stguarmore.php?stid=' + id;
                }
            }


// Update the count down every 1 second
var x = setInterval(function() {

 

  // If the count down is finished, write some text
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demo").innerHTML = "EXPIRED";
  }
}, 1000);
</script>


<script>
  function time(){
    var date = new Date();    
    var time = date.toLocaleTimeString();
    var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    var day = date.toLocaleDateString('en-US',options);
    document.getElementById('time').innerHTML = time;
    document.getElementById('day').innerHTML = day;
    
      
      var countDownDate = new Date("Oct 21, 2023 03:00:00").getTime();
      var now = new Date().getTime();
      var distance = countDownDate - now;
    
      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);
      var rest = hours * 3600 + minutes * 60 + seconds * 1;
    
      // Display the result in the element with id="demo"
      if(hours<10){hours = '0' + hours;}
      if(minutes<10){minutes = '0' + minutes;}
      if(seconds<10){seconds = '0' + seconds;}
      //document.getElementById("rest").innerHTML = hours + ":" + minutes + ":" + seconds;
      
      var length = 5*60;
      var perc = 100 * rest / length;
      document.getElementById('bar').style.width = "37%";//perc +'%';
    
    
    
  }
  setInterval(function(){
    time();
  },1000);
</script>

       