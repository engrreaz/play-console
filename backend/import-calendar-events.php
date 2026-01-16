<?php
include('inc.back.php');
$date_from = date('Y') . '-01-01';
$date_to = date('Y') . '-12-31';

$events_all = array();
$sql0 = "SELECT * FROM calendar where sccode='999999' and date between '$date_from' and '$date_to' order by date;";
// echo $sql0;
$result0rt_calendar = $conn->query($sql0);
if ($result0rt_calendar->num_rows >= 1) {
    while ($row0 = $result0rt_calendar->fetch_assoc()) {
        $events_all[] = $row0;
    }
}

// var_dump($events_all);

foreach ($events_all as $evenn) {
    $date = $evenn['date'];
    $day = $evenn['day'];
    $category = $evenn['category'];
    $descrip = $evenn['descrip'];
    $work = $evenn['work'];
    $class = $evenn['class'];
    $icon = $evenn['icon'];
    $color = $evenn['color'];

    $query332 = "INSERT INTO calendar(id, date, day, sccode, descrip, category, work, class, dateto, day_count, icon, color, modifieddate) VALUES (NULL, '$date', '$day', '$sccode', '$descrip', '$category', '$work', '$class', NULL,  1, '$icon', '$color', '$cur');";
    // if($conn->query($query332)===true){
    //     echo $date;
    // } else {
    //     echo '.';
    // }
    $conn->query($query332);
    // echo $query332. '<br><br>';
}