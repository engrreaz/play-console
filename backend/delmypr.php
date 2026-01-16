<?php
include 'inc.back.php';



$prno = $_POST['prno'];

$sql0r = "SELECT * FROM stfinance where sccode = '$sccode' and sessionyear LIKE '%$sy%' and  pr1no='$prno'  ";
$result0r = $conn->query($sql0r);
if ($result0r->num_rows > 0) {
    while ($row0r = $result0r->fetch_assoc()) {
        $taka = $row0r["pr1"];
        $id = $row0r["id"];

        $query3g = "update stfinance set pr1=0, pr1no=NULL, pr1date=NULL, pr1by=NULL, paid=paid-$taka, dues=dues+$taka where id='$id'; "; //echo $query3g. '<br>';
        $conn->query($query3g);
    }
}

$sql0r = "SELECT * FROM stfinance where sccode = '$sccode' and sessionyear LIKE '%$sy%'  and  pr2no='$prno'  ";
$result0r = $conn->query($sql0r);
if ($result0r->num_rows > 0) {
    while ($row0r = $result0r->fetch_assoc()) {
        $taka = $row0r["pr2"];
        $id = $row0r["id"];

        $query3g = "update stfinance set pr2=0, pr2no=NULL, pr2date=NULL, pr2by=NULL, paid=paid-$taka, dues=dues+$taka where id='$id'; ";//echo $query3g. '<br>';
        $conn->query($query3g);
    }
}



$query33 = "DELETE from stpr where prno='$prno' and sccode = '$sccode' and sessionyear LIKE '%$sy%' ;";//echo $query3g. '<br>';
$conn->query($query33);


$sql0r = "SELECT * FROM sessioninfo where lastpr = '$prno'  ";
$result0r = $conn->query($sql0r);
if ($result0r->num_rows > 0) {
    while ($row0r = $result0r->fetch_assoc()) {
        $sid = $row0r["id"];
        $npr = $prno - 1;

        $query3g = "update sessioninfo set lastpr='$npr' where id='$sid'; "; //echo $query3g. '<br>';
        $conn->query($query3g);
    }
}
echo 'Deleted';



?>