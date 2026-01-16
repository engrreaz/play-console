<?php
$stid = $userid;
$sh = 0;
if ($stid * 1 < 1000 || $stid == '') {
    $stnameeng = '<span style="color:gray;">&nbsp;I AM A GUARDIAN</span>';
    $subline = 'Guardian Profile <b>(Click to Setup)';
} else {
    $stnameeng = '<span style="color:var(--dark);">&nbsp;' . $fullname . '</span>';
    $subline = $stid;
    $sh = 1;
}
?>


<div class="box">
    <?php if ($sh == 0) { ?>
        <div class="right" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#setguardianbox">
            <svg class="<?php echo '$hidden'; ?>" xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                fill="var(--dark)" class="bi bi-arrow-right-circle-fill" viewBox="0 0 16 16">
                <path
                    d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0zM4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z" />
            </svg>
        </div>
    <?php } ?>
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="gray" class="bi bi-people-fill"
        viewBox="0 0 16 16">
        <path
            d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7Zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm-5.784 6A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216ZM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" />
    </svg>
    <?php echo $stnameeng; ?>
    <br /><small>ID # <?php echo $subline; ?></small>
</div>



<!-- The Modal -->
<div class="modal" id="setguardianbox">
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
                    Please Fill out the form below :
                </div>

                <div class="input-group ">
                    <span class="input-group-text"><i class="bi bi-person-badge-fill"></i></span>
                    <input type="number" id="guarid" name="guarid" style="" class="form-control"
                        placeholder="Any one of Student ID associates with you." value="">
                </div>

                <div class="input-group ">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" id="guarname" name="guarname" style="" class="form-control"
                        placeholder="Your Name in English" value="">
                </div>

                <div class="input-group ">
                    <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                    <input type="tel" id="guarmobile" name="guarmobile" style="" class="form-control"
                        placeholder="Your Registered Mobile Number" value="">
                </div>

            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <div id="check"></div>
                <button type="button" class="btn btn-info" data-bs-dismiss="modalx" onclick="submitguar();">Verify Your
                    Info</button>
            </div>

        </div>
    </div>
</div>




<script>
    function submitguar() {
        var id = document.getElementById("guarid").value;
        var nam = document.getElementById("guarname").value;
        var mno = document.getElementById("guarmobile").value;
        var email = '<?php echo $usr; ?>';
        var level = 'Guardian';

        var infor = "sccode=<?php echo $sccode; ?>&id=" + id + "&mno=" + mno + "&nam=" + nam + "&level=" + level + "&email=" + email;
        $("#check").html("");

        $.ajax({
            type: "POST",
            url: "profilesetup.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                $('#check').html('<span class=""><center>Verifying Data</center></span>');
            },
            success: function (html) { //setstudentbox
                $("#check").html(html);
                var res = document.getElementById("check").innerHTML;
                if (res == '1') {
                    window.location.href = 'index.php';
                    $("#setguardianbox").modal('hide');
                }

            }
        });
    }
</script>