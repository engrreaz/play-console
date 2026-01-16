<?php
include('inc.back.php');
date_default_timezone_set('Asia/Dhaka');
$sy = date('Y');


$id = $_POST['id'];
$action = $_POST['action'];
$cls = $_POST['cls'];
$sec = $_POST['sec'];

if ($action == 1) {
    if ($id == '') {
        $query33 = "INSERT INTO areas (id, idno, user, areaname, subarea, sessionyear, yesno) VALUES (null, 0, '$rootuser', '$cls', '$sec', '$sy', 1);";
    } else {
        $query33 = "UPDATE areas set areaname = '$cls', subarea = '$sec' where id='$id';";
    }
} else {
    $query33 = "DELETE from areas  where id='$id';";
}
$conn->query($query33);


//************************************************************************************************************************************************
//****************************************************************************************************************************************************************

include 'settings-class-class-list.php';

/*
$sql00xgr = "SELECT * FROM areas where user='$rootuser' and sessionyear='$sy' order by idno, id";
$result00xgr = $conn->query($sql00xgr);
if ($result00xgr->num_rows > 0) {
    while ($row00xgr = $result00xgr->fetch_assoc()) {
        $id = $row00xgr["id"];
        $cls2 = $row00xgr["areaname"];
        $sec2 = $row00xgr["subarea"];


        $photo_path = $BASE_PATH_URL . 'class-icons/' . strtolower($cls2) . ".png";
        //   echo $photo_path;
        if (!file_exists($photo_path)) {
            $photo_path = "https://eimbox.com/teacher/no-img.jpg";
        } else {
            $photo_path = $BASE_PATH_URL_FILE . 'class-icons/' . strtolower($cls2) . ".png";
        }
        ?>

        <div class="card mb-1" style="background:var(--lighter); color:var(--darker);">
            <div class="card-body">


                <div class="row">
                    <div class="col-2">
                        <img src="<?php echo $photo_path; ?>" class="st-pic-small" />
                    </div>
                    <div class="col-6">
                        <div class="stname-eng text-dark" id="cls<?php echo $id; ?>"><?php echo $cls2; ?></div>
                        <div class="st-id mt-1  text-muted">Class Name</div>
                        <div class="stname-ben fw-bold mt-2" id="sec<?php echo $id; ?>"><?php echo $sec2; ?></div>
                        <div class="mt-1  st-id text-muted">Section / Group Name</div>
                    </div>
                    <div class="col-4 text-end">
                        <button class="btn btn-white btn-rounded text-primary" onclick="edit(<?php echo $id; ?>);">
                            <i class="bi bi-pencil-square"></i> </button>
                        <button class="btn btn-white btn-rounded text-danger" onclick="del(<?php echo $id; ?>);">
                            <i class="bi bi-trash2-fill"></i> </button>
                    </div>
                </div>
            </div>
        </div>



    <?php }
} 
    */