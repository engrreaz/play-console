<?php
$stid = $userid;
$sh = 0;
if ($stid * 1 < 1000 || $stid == '') {
    $stnameeng = '<span style="color:gray;">&nbsp;I AM A STUDENT</span>';
    $subline = 'Student Profile <b>(Click to Setup)';

} else {

    $sql0xffffffffff = "SELECT * FROM students where stid='$stid' LIMIT 1";
    $result0xffffffffff = $conn->query($sql0xffffffffff);
    if ($result0xffffffffff->num_rows > 0) {
        while ($row0xffffffffff = $result0xffffffffff->fetch_assoc()) {
            $stnameeng = $row0xffffffffff["stnameeng"];
            $subline = $stid;
            $sh = 1;

        }
    } else {
        $stnameeng = '~~~~~~~~~~~';
        $subline = $stid;
    }
}


?>

<div class="box">
    <?php if ($sh == 0) { ?>
        <div class="right" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#setstudentbox">
            <svg class="<?php echo $hidden; ?>" xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="var(--dark)"
                class="bi bi-arrow-right-circle-fill" viewBox="0 0 16 16">
                <path
                    d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0zM4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z" />
            </svg>
        </div>
    <?php } ?>
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="gray" class="bi bi-person-fill"
        viewBox="0 0 16 16">
        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
    </svg>
    <?php echo $stnameeng; ?>
    <br /><small>Student's ID # <?php echo $subline; ?></small>

</div>





<!-- The Modal -->
<div class="modal" id="setstudentbox">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Setup My Student Profile</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div style="color:red; font-size:11px;">
                    Follow your Identity Card to fill out the form below :
                </div>

                <div class="input-group ">
                    <span class="input-group-text"><i class="bi bi-person-badge-fill"></i></span>
                    <input type="number" id="studentid" name="studentid" style="" class="form-control"
                        placeholder="Your ID Number" value="">
                </div>

                <div class="input-group ">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" id="studentname" name="studentname" style="" class="form-control"
                        placeholder="Your Name in English" value="">
                </div>

                <div class="input-group ">
                    <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                    <input type="tel" id="studentmobile" name="studentmobile" style="" class="form-control"
                        placeholder="Your Registered Mobile Number" value="">
                </div>

            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <div id="check2"></div>
                <button type="button" class="btn btn-info" data-bs-dismiss="modalx" onclick="submitstudent();">Verify
                    Your Info</button>
            </div>

        </div>
    </div>
</div>




<script>
    function submitstudent() {

        var id = document.getElementById("studentid").value;
        var nam = document.getElementById("studentname").value;
        var mno = document.getElementById("studentmobile").value;
        var email = '<?php echo $usr; ?>';
        var level = 'Student';

        var infor = "sccode=<?php echo $sccode; ?>&id=" + id + "&mno=" + mno + "&nam=" + nam + "&level=" + level + "&email=" + email;

        $("#check2").html("");

        $.ajax({


            type: "POST",
            url: "profilesetup.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                $('#check2').html('<span class=""><center>Verifying Data</center></span>');
            },
            success: function (html) { //setstudentbox
                $("#check2").html(html);
                var res = document.getElementById("check2").innerHTML;
                if (res == '1') {
                    window.location.href = 'index.php';
                    $("#setstudentbox").modal('hide');
                }

            }
        });
    }
</script>