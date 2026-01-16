<?php
include 'inc.php';
$stid = $_GET['id'];
include 'component/student-image-path.php';

?>

<main>
    <div class="containerx" style="width:100%;">
        <div class="card text-center" style="background:var(--dark); color:white; ">
            <div class="card-body page-top-box" style="height:175px;">
                <div class="menu-icon"><i class="bi bi-pencil-square"></i></div>
                <div class="menu-text "> Student's Profile Editor </div>
            </div>

            <div class="card-body page-info-box text-center" style="height:100px;">
                <img src="<?php echo $pth; ?>" class="st-pic-bigger" style="margin-left:-75px;" />
            </div>

        </div>

        <?php
        $sql0 = "SELECT * FROM students where stid='$stid' LIMIT 1";
        $result0 = $conn->query($sql0);
        if ($result0->num_rows > 0) {
            while ($row0 = $result0->fetch_assoc()) {
                $stnameeng = $row0["stnameeng"];
                $stnameben = $row0["stnameben"];
                //$  = $row0[" "];  $  = $row0[" "];
                $fname = $row0["fname"];
                $mname = $row0["mname"];
                $guarmobile = $row0["guarmobile"];
                $tel = substr($guarmobile, 0, 3) . ' ' . substr($guarmobile, 3, 2) . ' ' . substr($guarmobile, 5, 3) . ' ' . substr($guarmobile, -3);
                $previll = $row0["previll"];
                $prepo = $row0["prepo"];
                $preps = $row0["preps"];
                $predist = $row0["predist"];
                $dob = $row0["dob"];


                $sql0x = "SELECT * FROM sessioninfo where stid='$stid' and sessionyear LIKE '%$sy%'  and sccode='$sccode' LIMIT 1";
                $result0x = $conn->query($sql0x);
                if ($result0x->num_rows > 0) {
                    while ($row0x = $result0x->fetch_assoc()) {
                        $roll = $row0x["rollno"];
                        $cls = $row0x["classname"];
                        $sec = $row0x["sectionname"];
                    }
                }
                ?>
                <div class="card text-center" style="background:var(--lighter);">

                    <div class="card-body">


                        <div style="text-align:left; padding-top:2px;">
                            <table width="100%">
                                <tr>
                                    <td style="width:30px;" valign="top"></td>
                                    <td>
                                        <table width="100%">
                                            <tr>
                                                <td>
                                                    <div class="b" onclick="rel(<?php echo $stid; ?>);"><?php echo $stid; ?>
                                                    </div>
                                                    <div class="e">Identity Number</div>
                                                    <div style="height:5px;"></div>
                                                    <div class="b" style="font-size:16px;"><?php echo $cls; ?> /
                                                        <?php echo $sec; ?>
                                                    </div>
                                                    <div class="e">Student's of Class / Section | Group</div>

                                                </td>
                                                <td style="text-align:right; padding-top:15px;" valign="top">
                                                    <div class="roll-big"><?php echo $roll; ?></div>
                                                    <div class="e">Roll No</div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>



                            </table>
                        </div>
                    </div>
                </div>
                <div style="height:1px;"></div>

                <datalist id="ttt">
                    <option value="Banchharampur">
                    <option value="Homna">
                    <option value="Muradnagor">
                </datalist>
                <datalist id="ddd">
                    <option value="Bhrahmanbaria">
                    <option value="Cumilla">
                </datalist>
                <datalist id="ppp">
                    <?php
                    $sql0x = "SELECT prepo FROM students where sccode='$sccode' group by prepo order by prepo ";
                    $result0xp = $conn->query($sql0x);
                    if ($result0xp->num_rows > 0) {
                        while ($row0x = $result0xp->fetch_assoc()) {
                            $prepoq = $row0x["prepo"];
                            echo '<option value="' . $prepoq . '">';
                        }
                    }
                    ?>
                </datalist>


                <datalist id="vvv">
                    <?php
                    $sql0x = "SELECT previll FROM students where sccode='$sccode' group by previll order by previll ";
                    $result0xv = $conn->query($sql0x);
                    if ($result0xv->num_rows > 0) {
                        while ($row0x = $result0xv->fetch_assoc()) {
                            $previllq = $row0x["previll"];
                            echo '<option value="' . $previllq . '">';
                        }
                    }
                    ?>
                </datalist>



                <div id="editor-box" class="card text-center" style="background:var(--lighter);">
                    <div class="card-body">
                        <div style="text-align:left; padding-top:0px;">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                <input type="text" id="nameeng" name="nameeng" class="form-control"
                                    placeholder="Student's Name (in English)" value="<?php echo $stnameeng; ?>">
                            </div>
                        </div>
                        <div style="margin:0px 0; height:1px;"></div>
                        <div style="text-align:left; padding-top:0px;">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" id="nameben" name="nameben" class="form-control"
                                    placeholder="ছাত্র/ছাত্রীর নাম (বাংলায়)" value="<?php echo $stnameben; ?>">
                            </div>
                        </div>

                        <div style="margin:10px 0; height:2px; background:var(--lighter);"></div>

                        <div style="text-align:left; padding-top:0px;">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-standing"></i></span>
                                <input type="text" id="fname" name="fname" class="form-control" placeholder="Father's Name"
                                    value="<?php echo $fname; ?>">
                            </div>
                        </div>
                        <div style="margin:0px 0; height:1px;"></div>
                        <div style="text-align:left; padding-top:0px;">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-standing-dress"></i></span>
                                <input type="text" id="mname" name="mname" class="form-control" placeholder="Mother's Name"
                                    value="<?php echo $mname; ?>">
                            </div>
                        </div>

                        <div style="margin:10px 0; height:2px; background:var(--lighter);"></div>

                        <div style="text-align:left; padding-top:0px;">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-geo-alt-fill"></i></span>
                                <input type="text" list="vvv" id="vill" name="vill" class="form-control" placeholder="Village"
                                    value="<?php echo $previll; ?>">
                            </div>
                        </div>
                        <div style="margin:0px 0; height:1px;"></div>
                        <div style="text-align:left; padding-top:0px;">
                            <div class="input-group">
                                <span class="input-group-text"><i class=" bi bi-arrow-bar-down"
                                        style="color:lightgray;"></i></span>
                                <input type="text" list="ppp" id="po" name="po" class="form-control" placeholder="Post Office"
                                    value="<?php echo $prepo; ?>">
                            </div>
                        </div>
                        <div style="margin:0px 0; height:1px;"></div>
                        <div style="text-align:left; padding-top:0px;">
                            <div class="input-group">
                                <span class="input-group-text"><i class=" bi bi-arrow-bar-down"
                                        style="color:lightgray;"></i></span>
                                <input type="text" list="ttt" id="ps" name="ps" class="form-control" placeholder="Upzila/PS"
                                    value="<?php echo $preps; ?>">
                            </div>
                        </div>
                        <div style="margin:0px 0; height:1px;"></div>
                        <div style="text-align:left; padding-top:0px;">
                            <div class="input-group">
                                <span class="input-group-text"><i class=" bi bi-arrow-bar-down"
                                        style="color:lightgray;"></i></span>
                                <input type="text" list="ddd" id="dist" name="dist" class="form-control" placeholder="District"
                                    value="<?php echo $predist; ?>">
                            </div>
                        </div>
                        <div style="margin:10px 0; height:2px; background:var(--lighter);"></div>
                        <div style="margin:2px 0; height:1px;"></div>


                        <div style="text-align:left; padding-top:15px;">
                            <div class="input-group">
                                <span class="input-group-text"><i class=" bi bi-calendar-fill"></i></span>
                                <input type="date" id="dob" name="dob" class="form-control" placeholder="Date of Birth"
                                    value="<?php echo $dob; ?>">
                            </div>
                        </div>


                        <div style="text-align:left; padding-top:0px;">
                            <div class="input-group">
                                <span class="input-group-text"><i class=" bi bi-telephone-fill"></i></span>
                                <input type="tel" id="mno" name="mno" class="form-control" placeholder="Mobile Number"
                                    value="<?php echo $guarmobile; ?>">
                            </div>
                        </div>


                        <div style="text-align:left; padding-top :15px;">
                            <button type="button" class="btn btn-primary" onclick="upd(0);">Update Info</button>
                            <button type="button" class="btn btn-dark float-right" onclick="upd(1);">Update & Next</button>
                            <span id="px"></span>
                        </div>






                    </div>
                </div>
                <div style="height:1px;"></div>














                <div style="height:1px;"></div>




                <?php

            }
        }

        ?>



    </div>

</main>
<div style="height:52px;"></div>

<script>
    function rel(id) {
        window.location.href = "studentedit.php?id=" + id;
    }

    function edit(id) {
        window.location.href = "studentedit.php?id=" + id;
    }  
</script>

<script>
    function upd(act) {
        var nameeng = document.getElementById("nameeng").value;
        var nameben = document.getElementById("nameben").value;
        var fname = document.getElementById("fname").value;
        var mname = document.getElementById("mname").value;
        var vill = document.getElementById("vill").value;
        var po = document.getElementById("po").value;
        var ps = document.getElementById("ps").value;
        var dist = document.getElementById("dist").value;
        var mno = document.getElementById("mno").value;
        var dob = document.getElementById("dob").value;

        /*
        if (prno==""||cusid==""||prdate=="") 
        {
            if (prno==""){alert("আপনাকে অবশ্যই রশিদ নম্বর দিতে হবে।");} else{}
            if (cusid==""){alert("আপনাকে অবশ্যই গ্রাহকের পরিচিতি নম্বর দিতে হবে।");}else{}
            if (date==""){alert("আপনাকে অবশ্যই রশিদের তারিখ প্রদান করতে  হবে।"); document.getElementById("cusid").focus();}else{}
            
        }
        else*/
        {
            var infor = "stid=<?php echo $stid; ?>&nameeng=" + nameeng + "&nameben=" + nameben + "&fname=" + fname + "&mname=" + mname + "&vill=" + vill + "&po=" + po + "&ps=" + ps + "&dist=" + dist + "&mno=" + mno + "&dob=" + dob + "&roll=<?php echo $roll;?>&cls=<?php echo $cls; ?>&sec=<?php echo $sec; ?>";
            $("#px").html("");

            $.ajax({
                type: "POST",
                url: "backend/update-st-profile.php",
                data: infor,
                cache: false,
                beforeSend: function () {
                    $('#px').html('<span class="">Updating...</span>');
                },
                success: function (html) {
                    $("#px").html(html);

                    if(act==0){
                         history.back(-1);
                    } else {
                        var stidgo = document.getElementById("stidgo").innerHTML;
                        window.location.href = 'studentedit.php?id=' + stidgo;
                    }

                   
                    //alert('students.php?cls=<?php echo $cls; ?>&sec=<?php echo $sec; ?>#<?php echo $stid; ?>');
                    // window.location.href = 'students.php?cls=<?php echo $cls; ?>&sec=<?php echo $sec; ?>#block<?php echo $stid; ?>';
                }
            });
        }
    }
</script>