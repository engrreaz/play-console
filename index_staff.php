<?php
// INSERT NECESSARY TO-DO-LIST ***************************************************************************************************************************
$ddd = 0;
$sql0 = "SELECT *  FROM todolist where date='$td' and sccode='$sccode' and user='$usr' and todotype='attendance'";
$result01x = $conn->query($sql0);
if ($result01x->num_rows == 0) {

    $query33pxy = "insert into todolist (id, sccode, date, user, todotype, descrip1, descrip2, status, creationtime, response, responsetxt, responsetime) 
                    values (NULL, '$sccode', '$td', '$usr', 'Attendance', '', '', 0, '$cur', 'geoattnd', 'Submit', NULL);";
    $conn->query($query33pxy);
}


//   echo 'xxx';
$sql0 = "SELECT sum(amount) as paisi FROM stpr where sessionyear='$sy' and sccode='$sccode' and entryby='$usr'";
$result01xe = $conn->query($sql0);
if ($result01xe->num_rows > 0) {
    while ($row0 = $result01xe->fetch_assoc()) {
        $paisi = $row0["paisi"];
    }
}

// INSERT NECESSARY TO-DO-LIST ***************************************************************************************************************************
?>


<style>

</style>

<?php
include 'front-page-block/schedule.php';
include 'front-page-block/holi-ramadan.php';

include 'front-page-block/task-teacher.php';
if($notice_block==1){
    include 'front-page-block/notice.php';
}
$randval = random_int(1000000, 99999999);


?>


<div style="height:52px;"></div>


<script>
    function goclsp() { window.location.href = 'finclssec.php'; }
    function goclsa() { window.location.href = 'finacc.php'; }

    function gor() { alert("OK"); window.location.href = 'resultprocess.php'; }
    function gorx() { window.location.href = 'settings.php'; }
    function sublist() { window.location.href = 'tools_allsubjects.php'; }
    function update() { window.location.href = 'whatsnew.php'; }
    function token() { window.location.href = 'accountsecurity.php'; }

    function goclsa() { window.location.href = 'finacc.php'; }
    function mypr() { window.location.href = 'mypr.php'; }

    function register(x1, x2) { window.location.href = 'stattndregister.php?cls=' + x1 + '&sec=' + x2; }




    function goclsattall() { window.location.href = 'attndclssec.php'; }
</script>



<?php
