
<?php
    
    $timetime = date('h:i:s', strtotime($cur));
    
    $sql0x = "SELECT * FROM classschedule where sessionyear='$sy' and sccode='$sccode' and timestart<='$timetime' and timeend>='$timetime'";
    // echo $sql0x;
    $result0xtm = $conn->query($sql0x);
    if ($result0xtm->num_rows > 0) 
    {while($row0x = $result0xtm->fetch_assoc()) { 
        $period = $row0x["period"]; $timestart = $row0x["timestart"];  $timeend = $row0x["timeend"];  $duration = $row0x["duration"];
        $countdown = date('M d, Y') . ' ' . $timeend;
        
        $ptxt = '<b>Period # ' . $period . '</b> <span style="color:var(--dark);">(from ' . $timestart . ' to ' . $timeend . ')';
    }} else {
        $period = ''; $timestart = '00:00:00';  $timeend = '11:59:59';  $duration = 3600*24;
        $countdown = date('M d, Y') . ' ' . $timeend;
        $ptxt = '<span style="color:var(--dark); font-weight:700; font-size:14px;">Closed Now.</span>';}
    
    ?>
    
    
    <style>
          .progress-box {background: var(--light);}

    .progress-val {width: 69%; height: 3px; background: var(--darker); }
    </style>

<div class="main-card gg card">

    <div class="card-body">
        <div class="time" id="time"></div>
        <div class="date" id="day">
            <?php echo date('l, d, F, Y'); ?>
        </div>
        <div class="lable"><?php echo $ptxt;?></span>
                
                <span id="rest"></span></div>
        <div class="progress-box">
            <div class="progress-val" id="bar"></div>
        </div>
        <span id="restrest"></span>


    </div>
</div>








<script>



    // Update the count down every 1 second
    var x = setInterval(function () {



        // If the count down is finished, write some text
        if (distance < 0) {
            clearInterval(x);
            // document.getElementById("demo").innerHTML = "EXPIRED";
        }
    }, 1000);
</script>


<script>
    function time() {
        var date = new Date();
        var time = date.toLocaleTimeString();
        var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        var day = date.toLocaleDateString('en-US', options);
        document.getElementById('time').innerHTML = time;
        document.getElementById('day').innerHTML = day;


        var countDownDate = new Date("<?php echo $countdown;?>").getTime();
        var now = new Date().getTime();
        var distance = countDownDate - now;

        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        var rest = hours * 3600 + minutes * 60 + seconds * 1;
        // document.getElementById('restrest').innerHTML = rest;
        var peri = '<?php echo $period;?>';
        if(rest<=0 && peri !=''){
            document.location.href = 'index.php';
        }
        // Display the result in the element with id="demo"
        if (hours < 10) { hours = '0' + hours; }
        if (minutes < 10) { minutes = '0' + minutes; }
        if (seconds < 10) { seconds = '0' + seconds; }
        //document.getElementById("rest").innerHTML = hours + ":" + minutes + ":" + seconds;

        var length = <?php echo $duration;?>;
        var perc = 100 * rest / length;
        document.getElementById('bar').style.width = perc +'%';



    }
    setInterval(function () {
        time();
    }, 1000);
</script>