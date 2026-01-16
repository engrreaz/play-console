<?php
include 'inc.php';
$tid = $_GET['id'];

$pth = '../teacher/' . $tid . '.jpg';
if (file_exists($pth)) {
  $pth = 'https://eimbox.com/teacher/' . $tid . '.jpg';
} else {
  $pth = 'https://eimbox.com/teacher/noimg.jpg';
}

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


$teacher_pfofile_data = array();
$sql0 = "SELECT * FROM teacher where tid='$tid' LIMIT 1";
$result0 = $conn->query($sql0);
if ($result0->num_rows > 0) {
  while ($row0 = $result0->fetch_assoc()) {
    $teacher_pfofile_data[] = $row0;
  }
}
// echo $profile_entry_permission;
?>

<main>
  <div class="containerx" style="width:100%;">

    <div class="card text-center" style="background:var(--dark); color:white; ">
      <div class="card-body page-top-box">
        <div class="menu-icon"><i class="bi bi-person-circle"></i></div>
        <div class="menu-text "> Teacher's Profile </div>
      </div>
      <div class="card-body page-info-box d-flex">
        <img src="<?php echo $pth; ?>" style="border-radius:50%;" class="col-2 st-pic text-center" />
        <div class="ps-3 pt-2 text-start">
          <div class="stname-eng text-white"><?php echo $teacher_pfofile_data[0]['tname']; ?></div>
          <div class="stname-ben"><?php echo $teacher_pfofile_data[0]['tnameb']; ?></div>
          <div class="st-id">ID # <b><?php echo $teacher_pfofile_data[0]['tid']; ?></b></div>
        </div>
      </div>
    </div>
    <div id="scname2">........</div>

    <?php




    if ($userlevel == 'Administrator' || $tid == $userid) {
      include 'hr-profile-administrator.php';
    } else if ($userlevel == 'Teacher') {
      include 'hr-profile-teacher.php';
    } else if ($userlevel == 'Student') {
      include 'hr-profile-student.php';
    } else if ($userlevel == 'guardina') {
      include 'hr-profile-guardian.php';
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