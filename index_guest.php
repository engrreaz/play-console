<?php
if (isset($_GET['sccode'])) {
    $eiin = $_GET['sccode'];
} else {
    $eiin = 0;
}

$sql0 = "SELECT * from scinfo where sccode = '$eiin'";
$result0 = $conn->query($sql0);
if ($result0->num_rows > 0) {
    while ($row0 = $result0->fetch_assoc()) {
        $appyn = $row0["app"];

        if ($appyn == 1) {
            $query33 = "UPDATE usersapp set sccode = '$eiin', userlevel='Visitor'  where email = '$usr' ";
            $conn->query($query33);
            header("Location: index.php");
        }

    }
}


if ($usr == '' || substr($usr, 0, 1) > 0) {
    include 'login.php';
} else {




    ?>

    <div id="pack" style="padding:15px 10px;">
        <p>
            <?php if ($pxx == '') { ?>
                <small>
                    Dear User, We didn't identify you. Please, provide us the information below to detect you.<br>
                </small>
            <?php } else {
                echo $pxx;
            } ?>
        </p>
        <div>Please Enter Your 6-Digit EIIN :</div>
        <div class="input-group">
            <span class="input-group-text"><i class="material-icons">spa</i></span>
            <input type="number" class="form-control" id="eiin" placeholder="Enter Institution EIIN"
                value="<?php echo $sccode; ?>">
        </div>

        <br>
        <div id="btn">
            <button type="button" id="btn" class="btn btn-dark" onclick="eiin();">Submit</button><br><br>
        </div>

        <div id="status" class="status"></div>




    </div>



    <div
        style="text-align:center; background:var(--darker); color:var(--lighter); border-radius:5px; font-size:13px; font-weight:600; padding:8px; margin-bottom:8px;">
        How To Start?
    </div>
    <div>
        <ul>
            <li>Enter your 6-digit institution EIIN Number. If you're the first user of your institute. You'll be set as an
                <b>Administrator.</b> Otherwise contact with your Head Teacher or Administrator.<br><br>
            </li>
            <li>After submitting EIIN, Click <b>Proceed</b> (Green Button).<br><br></li>
            <li>Institution information page will display and update your institute information with<br>Institute Name,
                Address, Upzila/Police Station, District, Mobile Number.<br>Click <b>Update Info</b><br><br></li>
            <li>You're done!<br>Your Account is ready now to manage your institution.</li>
        </ul>
    </div>


<?php } ?>


<script>
    function eiin() {
        var eiin = document.getElementById("eiin").value;
        if (eiin > 100) {
            var infor = "user=<?php echo $usr; ?>&eiin=" + eiin; //alert(infor);
            $("#status").html("");

            $.ajax({
                type: "POST",
                url: "checkeiin.php",
                data: infor,
                cache: false,
                beforeSend: function () {
                    $('#status').html('<span class=""><center>Processing....</center></span>');
                },
                success: function (html) {
                    $("#status").html(html);
                }
            });
        }
    }

    function proceed() {
        window.location.href = 'index.php?email=<?php echo $usr; ?>';
    }
</script>