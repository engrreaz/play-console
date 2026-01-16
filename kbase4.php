<?php
include 'inc.php';
$base1 = $_GET['base1'];
$base2 = $_GET['base2'];
$base3 = $_GET['base3'];
?>

<style>
    .pic {
        width: 45px;
        height: 45px;
        padding: 1px;
        border-radius: 50%;
        border: 1px solid var(--dark);
        margin: 5px;
    }

    .a {
        font-size: 18px;
        font-weight: 700;
        font-style: normal;
        line-height: 22px;
        color: var(--dark);
    }

    .b {
        font-size: 16px;
        font-weight: 600;
        font-style: normal;
        line-height: 22px;
    }

    .c {
        font-size: 11px;
        font-weight: 400;
        font-style: italic;
        line-height: 16px;
    }

    .card-bodyx {
        padding: 0 25px;
    }

    .lblx {
        font-size: 11px;
        margin: 3px 0px 5px 12px;
        color: gray;
    }


    .icon {
        font-size: 16px;
        color: var(--dark);
        vertical-align: top;
        width: 40px;
    }

    .title {
        display: block;
        font-size: 16px;
        font-weight: 500;
        color: var(--dark);
    }

    .subtitle {
        display: block;
        font-size: 14px;
        line-height: 18px;
        font-weight: 400;
        color: gray;
        vertical-align: top;
    }

    .rightpart {
        text-align: right;
        width;
        50px;
    }
</style>


<main>
    <div class="container-fluidx">
        <div class="card text-left" style="background:var(--dark); color:var(--lighter);" onclick="gox()">

            <div class="card-body">

                <div class="page-top-box">
                    <div class="menu-icon "><i class="bi bi-patch-question-fill"></i></div>

                    <div class="menu-text">
                        Lessons<br>পাঠসমূহ
                    </div>

                </div>


            </div>
        </div>


        <?php

        $sql0 = "SELECT * FROM kbasestep where kbase1='$base2' and kbase2='$base1' and kbase3='$base3' order by sl";
        // echo $sql0;
        $result0vfd = $conn->query($sql0);
        if ($result0vfd->num_rows > 0) {
            while ($row0 = $result0vfd->fetch_assoc()) {
                $step = $row0["step"];
                $stepid = $row0["id"];



                ?>
                <div class="card" style="background:var(--lighter); color:var(--darker); margin-bottom:2px;" >
                    <div class="card-body">
                        <div style="margin-bottom:10px;" class="title"><?php echo $step; ?></div>
                        <table style="width:100%; margin:0 10px;">
                            <?php
                            $sql0 = "SELECT * FROM kbase4 where kbase1='$base2' and kbase2='$base1' and kbase3='$base3' and stepid='$stepid' order by sl";
                            // echo $sql0;
                            $result0vf = $conn->query($sql0);
                            if ($result0vf->num_rows > 0) {
                                while ($row0 = $result0vf->fetch_assoc()) {
                                    $step = $row0["step"];
                                    $descrip = $row0["descrip"];
                                    $icon = $row0["pic"];
                                    $title = $row0["title"];
                                    $perc = rand(20, 100);

                                    ?>


                                    <tr>
                                        <td class="icon" style=""><span><i class="bi bi-check-circle-fill"></i></span></td>
                                        <td>
                                            <span class="subtitle"><?php echo $descrip; ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="height:10px;"></td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </table>

                    </div>
                </div>
                <?php



            }
        }





        $sql0 = "SELECT * FROM kbasedone where kbase1='$base2' and kbase2='$base3' and kbase3='$base1' and email='$usr'";
        $result0t = $conn->query($sql0);
        if ($result0t->num_rows > 0) {
            while ($row0 = $result0t->fetch_assoc()) {
                $idd = $row0['id'];
                $query333 = "UPDATE kbasedone set times = times+1, lastlearn = '$cur' where id='$idd'";
                $conn->query($query333);
            }
        } else {
            $query333 = "insert into kbasedone (id, sl, kbase1, kbase2, kbase3, email, times, lastlearn) values (NULL, 0, '$base2', '$base3', '$base1', '$usr', 1, '$cur');";
            $conn->query($query333);
        }

        // echo $query333;         
        








        ?>














    </div>

</main>
<div style="height:52px;"></div>


<script>

    function go() {
        var cls = document.getElementById("classname").value;
        var sec = document.getElementById("sectionname").value;
        var sub = document.getElementById("subject").value;
        var assess = document.getElementById("assessment").value;
        var exam = document.getElementById("exam").value;
        let tail = '?exam=' + exam + '&cls=' + cls + '&sec=' + sec + '&sub=' + sub + '&assess=' + assess;
        if (cls == 'Six' || cls == 'Seven') {
            window.location.href = "markpibi.php" + tail;
        } else {
            window.location.href = "markentry.php" + tail;
        }
    }  
</script>


<script>
    function fetchsection() {
        var cls = document.getElementById("classname").value;

        var infor = "user=<?php echo $rootuser; ?>&cls=" + cls;
        $("#sectionblock").html("");

        $.ajax({
            type: "POST",
            url: "fetchsection.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                $('#sectionblock').html('<span class=""><center>Fetching Section Name....</center></span>');
            },
            success: function (html) {
                $("#sectionblock").html(html);
            }
        });
    }
</script>

<script>
    function fetchsubject() {
        var cls = document.getElementById("classname").value;
        var sec = document.getElementById("sectionname").value;

        var infor = "sccode=<?php echo $sccode; ?>&tid=<?php echo $userid; ?>&cls=" + cls + "&sec=" + sec; //alert(infor);
        $("#subblock").html("");

        $.ajax({
            type: "POST",
            url: "fetchsubject.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                $('#subblock').html('<span class="">Retriving Subjects...</span>');
            },
            success: function (html) {
                $("#subblock").html(html);
            }
        });
    }

    function print() {
        window.print();
    }

    function godeep(id, id2) {
        window.location.href = "kbase3.php?base1=" + id + "&base2=" + id2;
    }
</script>



</body>

</html>