<?php
include 'inc.php';
$stid = $_GET['id'];
include 'component/student-image-path.php';

$profile_entry = '';
$profile_ind = array_search('Profile Entry', array_column($ins_all_settings, 'setting_title'));
if ($profile_ind != '' || $profile_ind != null) {
  $profile_entry = $ins_all_settings[$profile_ind]['settings_value'];
}
if (strpos($profile_entry, $userlevel) != null) {
  $profile_entry_permission = 1;
} else {
  $profile_entry_permission = 0;
}

// echo $profile_entry_permission;
?>

<main>
  <div class="containerx" style="width:100%;">

    <div class="card text-center" style="background:var(--dark); color:white; ">
      <div class="card-body page-top-box" style="height:175px;">
        <div class="menu-icon"><i class="bi bi-person-circle"></i></div>
        <div class="menu-text "> Student's Profile Editor </div>
      </div>
      <div class="card-body page-info-box" style="height:75px;">

        <?php if ($profile_entry_permission == 1) { ?>
          <div onclick="edit(<?php echo $stid; ?>)"
            style="padding:2px 10px; float:right; border-radius:10%;  top:130px; z-index:100;position:relative; background:black; color:white; ">
            <i class="bi bi-pencil" style="font-size:18px; padding-top:2px;"></i> Edit
          </div>

        <?php } ?>
        <img src="<?php echo $pth; ?>" class="st-pic-bigger text-center" />
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


        $sql0x = "SELECT * FROM sessioninfo where stid='$stid' and sessionyear LIKE '%$sy%'  and sccode='$sccode' LIMIT 1";
        $result0x = $conn->query($sql0x);
        if ($result0x->num_rows > 0) {
          while ($row0x = $result0x->fetch_assoc()) {
            $roll = $row0x["rollno"];
            $cls = $row0x["classname"];
            $sec = $row0x["sectionname"];
          }
        }


        $sql0x = "SELECT sum(paid) as paid FROM stfinance where stid='$stid' and sessionyear LIKE '%$sy%'  and sccode='$sccode' ";
        $result0xd = $conn->query($sql0x);
        if ($result0xd->num_rows > 0) {
          while ($row0x = $result0xd->fetch_assoc()) {
            $paid = $row0x["paid"];
          }
        }

        $dtk = '';
        if ($paid > 0) {
          $dtk = 'disabled';
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
                          <div class="b" onclick="rel(<?php echo $stid; ?>);"><?php echo $stid; ?></div>
                          <div class="e">Identity Number</div>
                          <div style="height:5px;"></div>
                          <div class="b" style="font-size:16px;"><?php echo $cls; ?> / <?php echo $sec; ?></div>
                          <div class="e">Student's of Class / Section | Group</div>
                          <div style="height:25px;"></div>
                        </td>
                        <td style="text-align:right; padding-top:15px;" valign="top">
                          <div class="a"><?php echo $roll; ?></div>
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

        <div class="card text-center" style="background:var(--lighter);">
          <div class="card-body">
            <div style="text-align:left; padding-top:10px;">
              <table width="100%">
                <tr>
                  <td style="width:30px; padding-right:10px;" valign="top"><i
                      class="bi bi-person-circle menu-item-icon "></i></td>
                  <td class="">
                    <div class="stname-eng"><?php echo $stnameeng; ?></div>
                    <div class="stname-ben"><?php echo $stnameben; ?></div>
                  </td>
                </tr>
                <tr>
                  <td colspan="2" style="height:10px;"></td>
                </tr>
              </table>
            </div>
          </div>
        </div>

        <div class="card text-center" style="background:var(--lighter);">
          <div class="card-body">
            <div style="text-align:left; padding-top:10px;">
              <table width="100%">
                <tr>
                  <td style="width:30px; padding-right:10px;" valign="top"><i class="bi bi-people-fill menu-item-icon"></i>
                  </td>
                  <td>
                    <div class="d"><?php echo $fname; ?></div>
                    <div class="e">Father's Name</div>

                    <div class="d" style="padding-top:8px;"><?php echo $mname; ?></div>
                    <div class="e">Mother's Name</div>
                  </td>
                </tr>
                <tr>
                  <td colspan="2" style="height:10px;"></td>
                </tr>
              </table>
            </div>
          </div>
        </div>




        <div class="card text-center" style="background:var(--lighter);">

          <div class="card-body">

            <div style="text-align:left; padding-top:5px;">
              <table width="100%">

                <tr>
                  <td style="width:30px; padding-right:10px;" valign="top"><i
                      class="bi bi-telephone-fill menu-item-icon"></i></td>
                  <td>
                    <div style="float:right;"><a href="tel://<?php echo $guarmobile; ?>" class="btn btn-primary">Call
                        Now</a>
                    </div>
                    <div class="d"><?php echo $tel; ?></div>
                    <div class="e">Guardian's Mobile Number</div>
                  </td>
                </tr>


              </table>
            </div>
          </div>
        </div>



        <div class="card text-center" style="background:var(--lighter);">
          <div class="card-body">
            <div style="text-align:left; padding-top:1px;">
              <table width="100%">
                <tr>
                  <td style="width:30px;  padding-right:10px;" valign="top"><i
                      class="bi bi-geo-alt-fill menu-item-icon"></i></td>
                  <td>
                    <div class="b" style="font-size:16px;">
                      <?php echo $previll . ', ' . $prepo . ',<br>' . $preps . ', ' . $predist; ?>.
                    </div>
                    <div class="e">Present Address</div>
                    <div style="height:25px;"></div>
                  </td>
                </tr>
                <tr>
                  <td colspan="2" style="height:1px;"></td>
                </tr>
              </table>
            </div>
          </div>
        </div>







        <?php

        if ($userlevel == 'Super Administrator') {
          ?>

          <div class="card text-center" style="background:var(--lighter);" >
            <div class="card-body">
              <div style="text-align:left; padding-top:1px;">
                <div style="border-bottom:1px solid gray;">
                  <b>Transfer Certificate</b>
                  <br>
                  <span style="font-size:11px; font-style:italic;">Issue a transfer certificate against the student</span>
                  <br>

                  <div style="text-align:left; padding-top:0px;">
                    <div class="input-group">
                      <span class="input-group-text"><i class="material-icons ico">report</i></span>
                      <select class="form-control" id="cause"
                        style="border:0; background:var(--lighter); border-bottom:1px solid lightgray;">
                        <option>Select a Cuase</option>
                        <option value="Willing of the guardian">Willing of the guardian</option>
                        <option value="End of the education to school">End of the education to school</option>
                        <option value="Change of residence">Change of residence</option>
                      </select>
                    </div>
                  </div>

                  <div style="text-align:left; padding-top:0px;">
                    <div class="input-group">
                      <span class="input-group-text"><i class="material-icons ico">monetization_on</i></span>
                      <input type="text" id="taka" name="taka" class="form-control" placeholder="Amount on Issue" value="">
                    </div>
                  </div>

                  <div style="padding:5px 60px;">
                    <button class="btn btn-info" onclick="tcamount();;"><b>Issue a TC</b></button>
                    <span id="settc"></span>
                  </div>



                </div>
                <div style="text-align:center; padding:50px 10px;">
                  <button class="btn btn-danger" onclick="remove(0);" <?php echo $dtk; ?>>Remove The Student</button>

                  <span id="deldel"></span>
                </div>

              </div>
            </div>
          </div>
          <?php
        }
      }
    }

    ?>



  </div>

</main>
<div style="height:52px;"></div>
<footer>
  <!-- place footer here -->
</footer>
<!-- Bootstrap JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
  integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
  integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<script>
  function rel(id) {
    window.location.href = "student.php?id=" + id;
  }

  function edit(id) {
    window.location.href = "studentedit.php?id=" + id;
  }  
</script>


<script>
  function remove(tail) {
    var infor = "sccode=<?php echo $sccode; ?>&stid=<?php echo $stid; ?>&tail=" + tail;
    $("#deldel").html("");

    $.ajax({
      type: "POST",
      url: "removestudent.php",
      data: infor,
      cache: false,
      beforeSend: function () {
        $('#deldel').html('<span class=""><center>Removing Student</center></span>');
      },
      success: function (html) {
        $("#deldel").html(html);
      }
    });
  }
</script>

<script>
  function tcamount() {
    var cause = document.getElementById("cause").value;
    var taka = document.getElementById("taka").value;
    var infor = "sccode=<?php echo $sccode; ?>&stid=<?php echo $stid; ?>&taka=" + taka + "&cause=" + cause;
    $("#settc").html("");

    $.ajax({
      type: "POST",
      url: "settc.php",
      data: infor,
      cache: false,
      beforeSend: function () {
        $('#settc').html('<span class=""><center>Removing Student</center></span>');
      },
      success: function (html) {
        $("#settc").html(html);
      }
    });
  }
</script>



</body>

</html>