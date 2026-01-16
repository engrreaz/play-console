<?php
include 'inc.php';
?>

<main>
    <div class="container-fluidx">
        <div class="card text-left" style="background:var(--dark); color:var(--lighter);">
            <div class="card-body page-top-box">
                <table width="100%" style="color:white;">
                    <tr>
                        <td>
                            <div class="menu-icon"><i class="bi bi-people-fill"></i></div>
                            <div class="menu-text"> Student's ID Generator </div>
                        </td>
                    </tr>


                </table>
            </div>
        </div>

        <?php
        if ($userlevel == 'Administrator' || $userlevel == 'Head Teacher' || $userlevel == 'Principal') { ?>



            <div class="card" style="background:var(--lighter); color:var(--darker); display:none;" onclick="lnk1();">
                <img class="card-img-top" alt="">
                <div class="card-body">
                    <table style="">
                        <tr>
                            <td></td>
                            <td><b>Add a new class</b></td>
                        </tr>
                        <tr>
                            <td style="width:50px;color:var(--dark);"><i class="material-icons">note_add</i></td>
                            <td>

                                <div style="text-align:left; padding-top:0px; display:none;">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="material-icons ico">group</i></span>
                                        <input type="text" id="id" name="id" class="form-control"
                                            placeholder="Enter Section/Group Name" value="">
                                    </div>
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-text"><i class="material-icons ico">group</i></span>
                                    <select class="form-control" id="cls">

                                        <option value="">Choose a Class</option>
                                        <option value="Six">Six</option>
                                        <option value="Seven">Seven</option>
                                        <option value="Eight">Eight</option>
                                        <option value="Nine">Nine</option>
                                        <option value="Ten">Ten</option>
                                    </select>
                                </div>

                                <div style="margin:0px 0; height:5px; background:var(--lighter);"></div>

                                <div style="text-align:left; padding-top:0px;">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="material-icons ico">view_week</i></span>
                                        <input type="text" id="sec" name="sec" class="form-control"
                                            placeholder="Enter Section/Group Name" value="">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <div style="margin:0px 0; height:5px; background:var(--lighter);"></div>
                                <button class="btn btn-success" onclick="submit();">Submit</button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>


            <div id="block" style="backgroudn:var(--light);">
                <?php
                $sql00xgr = "SELECT * FROM areas where user='$rootuser' and sessionyear LIKE '%$sy%' order by idno, id";
                $result00xgr = $conn->query($sql00xgr);
                if ($result00xgr->num_rows > 0) {
                    while ($row00xgr = $result00xgr->fetch_assoc()) {
                        $id = $row00xgr["id"];
                        $cls2 = $row00xgr["areaname"];
                        $sec2 = $row00xgr["subarea"];
                        $from2 = $row00xgr["rollfrom"];
                        $to2 = $row00xgr["rollto"];
                        ?>

                        <div class="card mb-2" style="background:var(--lighter); color:var(--darker);">

                            <div class="card-body">
                                <table style="">
                                    <tr>
                                        <td style="vertical-align:top; padding-right:25px;" class="menu-item-icon"><i
                                                class="bi bi-diagram-3-fill"></i></td>
                                        <td class=" menu-item-block" style="border:0;">
                                            <h4 class="text-dark" id="cls<?php echo $id; ?>"><?php echo $cls2; ?></h4>
                                            <div class="menu-item-sub-text">Class Name</div>


                                        </td>
                                        <td class="text-end">
                                            <h6 class="text-dark mt-3" id="sec<?php echo $id; ?>"><?php echo $sec2; ?></h6>
                                            <div class="menu-item-sub-text">Section / group Name</div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td></td>
                                        <td colspan="2" class="stname-ben pt-2">Enter Student Roll/ID Range :</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td colspan="2" style="padding-top:5px;">
                                            <table>
                                                <tr>
                                                    <td>
                                                        <div style="text-align:left; padding-top:0px;">
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i
                                                                        class="bi bi-file-earmark-person-fill"></i></span>
                                                                <input type="number" id="from<?php echo $id; ?>" name="id2"
                                                                    class="form-control text-box" placeholder="From"
                                                                    value="<?php echo $from2; ?>">
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style="width:10px;"></td>
                                                    <td>
                                                        <div style="text-align:left; padding-top:0px;">
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i
                                                                        class="bi bi-file-earmark-person-fill"></i></span>
                                                                <input type="number" id="to<?php echo $id; ?>" name="id1"
                                                                    class="form-control text-box" placeholder="To"
                                                                    value="<?php echo $to2; ?>">
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end"><small>Start From</small></td>
                                                    <td></td>
                                                    <td class="text-end"><small>End To</small></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="padding-top:15px;" colspan="2">
                                            <button class="btn btn-info btn-block" onclick="genid(<?php echo $id; ?>);">
                                                <small>Generate Student's ID</small></button>
                                            <div id="gen<?php echo $id; ?>"></div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>


                    <?php }
                } ?>
            </div>
        </div>
    <?php } else {
            echo 'Login Again Please...';
        } ?>
    </div>
</main>
<div style="height:52px;"></div>


<script>
    function genid(id) {
        let a = document.getElementById("from" + id).value;
        let b = document.getElementById("to" + id).value;
        if (a > 0 && b > 0) {
            var infor = "rootuser=<?php echo $rootuser; ?>&id=" + id + "&sccode=<?php echo $sccode; ?>&from=" + a + "&to=" + b;
            $("#gen" + id).html("");

            $.ajax({
                type: "POST",
                url: "backend/generate-stid.php",
                data: infor,
                cache: false,
                beforeSend: function () {
                    $('#gen' + id).html('<span class=""><center>Processing, Please Wait....</center></span>');
                },
                success: function (html) {
                    $("#gen" + id).html(html);
                }
            });
        } else {
            alert('Please Enter Valid Roll Range');
        }

    }
</script>