<?php
include '../inc.light.php';

$level = $_GET['level'] ?? 'slot';

$result = [];

if ($level == 'slot') {
    $slots = $conn->query("SELECT DISTINCT slotname FROM slots WHERE sccode='$sccode' ORDER BY slotname");
    while($s=$slots->fetch_assoc()){
        $result[] = ['name'=>$s['slotname']];
    }
}
elseif ($level == 'session') {
    $slot = $_GET['slot'];
    $sessions = $conn->query("SELECT DISTINCT syear FROM sessionyear WHERE sccode='$sccode' AND active=1 ORDER BY syear ASC");
    while($ses=$sessions->fetch_assoc()){
        $result[] = ['name'=>$ses['syear']];
    }
}
elseif ($level == 'class') {
    $slot = $_GET['slot'];
    $session = $_GET['session'];
    $classes = $conn->query("SELECT DISTINCT areaname FROM areas WHERE slot='$slot' AND sessionyear='$session' and sccode='$sccode' and slot='$slot' ORDER BY FIELD(areaname,'Six','Seven','Eight','Nine','Ten')");
    while($cls=$classes->fetch_assoc()){
        $result[] = ['name'=>$cls['areaname']];
    }
}
elseif ($level == 'section') {
    $slot = $_GET['slot'];
    $session = $_GET['session'];
    $class = $_GET['class'];
    $sections = $conn->query("SELECT DISTINCT subarea FROM areas WHERE slot='$slot' AND sessionyear='$session' AND areaname='$class' and sccode='$sccode' ORDER BY subarea");
    while($sec=$sections->fetch_assoc()){
        $result[] = ['name'=>$sec['subarea']];
    }
}
elseif ($level == 'students') {
    $slot = $_GET['slot'];
    $session = $_GET['session'];
    $class = $_GET['class'];
    $section = $_GET['section'];
    $students = $conn->query(
        "SELECT si.stid, si.rollno, st.stnameeng
         FROM sessioninfo si
         LEFT JOIN students st ON st.stid=si.stid
         WHERE si.slot='$slot' AND si.sessionyear='$session' AND si.classname='$class' AND si.sectionname='$section' and si.sccode='$sccode'
         ORDER BY si.rollno"
    );
    while($st=$students->fetch_assoc()){
        $result[] = ['name'=>$st['rollno'].' - '.$st['stnameeng'], 'stid'=>$st['stid']];
    }
}

header('Content-Type: application/json');
echo json_encode($result);
