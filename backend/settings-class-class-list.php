<?php
$sql00xgr = "SELECT * FROM areas where user='$rootuser' and sessionyear LIKE '%$sy%'  group by areaname order by idno, id";
$result00xgr = $conn->query($sql00xgr);
if ($result00xgr->num_rows > 0) {
    while ($row00xgr = $result00xgr->fetch_assoc()) {
        $idx = $row00xgr["id"];
        $cls2 = $row00xgr["areaname"];
        // $sec2 = $row00xgr["subarea"];


        $photo_path = $BASE_PATH_URL . 'class-icons/' . strtolower($cls2) . ".png";
        // echo $photo_path;
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


                    <div class="col-10">
                        <div class="row">
                            <div class="col-12">
                                <div class="stname-eng text-dark" id="clsx<?php echo $id; ?>"><?php echo $cls2; ?></div>
                                <div class="st-id mt-1  text-muted">Class Name</div>
                            </div>

                        </div>






                        <?php
                        $sql00xgr = "SELECT * FROM areas where user='$rootuser' and sessionyear LIKE '%$sy%'  and areaname= '$cls2' order by idno, id";
                        $result00xgr_2 = $conn->query($sql00xgr);
                        if ($result00xgr_2->num_rows > 0) {
                            while ($row00xgr = $result00xgr_2->fetch_assoc()) {
                                $id = $row00xgr["id"];
                                $cls22 = $row00xgr["areaname"];
                                $sec2 = $row00xgr["subarea"];

                     
                                ?>




                                <div class="row">

                                    <div class="col-8">
                                        <div class="stname-eng text-dark" id="cls<?php echo $id; ?>" hidden><?php echo $cls22; ?></div>
                                        <div class="stname-ben fw-bold mt-2" id="sec<?php echo $id; ?>"><?php echo $sec2; ?>
                                        </div>
                                        <div class="mt-1  st-id text-muted">Section / Group Name</div>
                                    </div>
                                    <div class="col-2">
                                        <button class="btn btn-white btn-rounded text-primary" onclick="edit(<?php echo $id; ?>);">
                                            <i class="bi bi-pencil-square"></i> </button>
                                    </div>
                                    <div class="col-2">
                                        <button class="btn btn-white btn-rounded text-danger" onclick="del(<?php echo $id; ?>);">
                                            <i class="bi bi-trash2-fill"></i> </button>
                                    </div>
                                </div>



                                <div class="row mt-1 mb-3" hidden>
                                    <div class="col stand-icon text-ceenter" onclick="">
                                        <button class="btn btn-lg btn-rounded"> <i class="bi bi-book-half"></i> </button>
                                    </div>
                                    <div class="col stand-icon text-ceenter" onclick="">
                                        <button class="btn btn-lg btn-rounded"> <i class="bi bi-book-half"></i> </button>
                                    </div>
                                    <div class="col stand-icon text-ceenter" onclick="">
                                        <button class="btn btn-lg btn-rounded"> <i class="bi bi-book-half"></i> </button>
                                    </div>
                                    <div class="col stand-icon text-ceenter" onclick="">
                                        <button class="btn btn-lg btn-rounded"> <i class="bi bi-book-half"></i> </button>
                                    </div>
                                    <div class="col stand-icon text-ceenter" onclick="">
                                        <button class="btn btn-lg btn-rounded"> <i class="bi bi-book-half"></i> </button>
                                    </div>



                                </div>






                            <?php }
                        } ?>


                    </div>

                </div>
            </div>





        </div>



    <?php }
} ?>