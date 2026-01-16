<?php
include 'inc.php';
// $classname = $_GET['cls']; $sectionname = $_GET['sec']; 
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

  h4 {
    font-size: 18px;
    color: var(--darker);
    line-height: 12px;
    font-weight: 700;
  }

  small {
    font-size: 10px;
    color: var(--dark);
    line-height: 10px;
  }

  .card,
  .card-body {
    border-radius: 0;
    margin: 0;
  }
</style>
</head>

<body>
  <header>
    <!-- place navbar here -->
  </header>
  <main>
    <div class="container-fluidx ">

      <div class="card text-left" style="background:var(--dark); color:var(--lighter);" onclick="go(<?php echo $id; ?>)">

        <div class="card-body">
          <table width="100%" style="color:white;">
            <tr>
              <td>
                <div class="logoo">
                  <i class="bi bi-gear-wide-connected "></i>
                </div>
                <div
                  style="font-size:20px; text-align:center; padding: 2px 2px 8px; font-weight:700; line-height:10px;">

                  <br> Settings
                </div>
              </td>
            </tr>


          </table>
        </div>
      </div>


      <?php if ($userlevel == 'Administrator' || $userlevel == 'Head Teacher') { ?>





        <div class="card" style="background:var(--lighter); color:var(--darker);" onclick="lnk7();">
          <img class="card-img-top" alt="">
          <div class="card-body">
            <table >
              <tr>
                <td style="width:50px;color:var(--dark);"><i class="material-icons">school</i></td>
                <td>
                  <h4>Institute Information</h4>
                  <small>Update Institute Informations</small>
                </td>
              </tr>
            </table>
          </div>
        </div>

        <div class="card" style="background:var(--lighter); color:var(--darker);" onclick="lnk4();">
          <img class="card-img-top" alt="">
          <div class="card-body">
            <table >
              <tr>
                <td style="width:50px;color:var(--dark);"><i class="material-icons">description</i></td>
                <td>
                  <h4>Teachers & Staffs</h4>
                  <small>Add / Edit Teachers & Staffs</small>
                </td>
              </tr>
            </table>
          </div>
        </div>



        <div class="card" style="background:var(--lighter); color:var(--darker);" onclick="lnk6();">
          <img class="card-img-top" alt="">
          <div class="card-body">
            <table >
              <tr>
                <td style="width:50px;color:var(--dark);"><i class="material-icons">group</i></td>
                <td>
                  <h4>Create Classes</h4>
                  <small>Create/Edit Class | Section / Group</small>
                </td>
              </tr>
            </table>
          </div>
        </div>




        <div class="card" style="background:var(--lighter); color:var(--darker);" onclick="lnk1();">
          <img class="card-img-top" alt="">
          <div class="card-body">
            <table >
              <tr>
                <td style="width:50px;color:var(--dark);"><i class="material-icons">group</i></td>
                <td>
                  <h4>Subjects</h4>
                  <small>Class/Section wise Subject Setup with Marks Distributions</small>
                </td>
              </tr>
            </table>
          </div>
        </div>



        <div class="card" style="background:var(--lighter); color:var(--darker);" onclick="lnk3();">
          <img class="card-img-top" alt="">
          <div class="card-body">
            <table >
              <tr>
                <td style="width:50px;color:var(--dark);"><i class="material-icons">group</i></td>
                <td>
                  <h4>Binding Teacher with Subject</h4>
                  <small>Setup class teacher & binding teachers with their subjects</small>
                </td>
              </tr>
            </table>
          </div>
        </div>




        <div class="card" style="background:var(--lighter); color:var(--darker);" onclick="lnk8();">
          <img class="card-img-top" alt="">
          <div class="card-body">
            <table >
              <tr>
                <td style="width:50px;color:var(--dark);"><i class="material-icons">description</i></td>
                <td>
                  <h4>Users Management</h4>
                  <small>Manage your institution users, their access permission, and bind with related teacher.</small>
                </td>
              </tr>
            </table>
          </div>
        </div>




        <div class="card" style="background:var(--lighter); color:var(--darker);" onclick="lnk2();">
          <img class="card-img-top" alt="">
          <div class="card-body">
            <table >
              <tr>
                <td style="width:50px;color:var(--dark);"><i class="material-icons">receipt</i></td>
                <td>
                  <h4>Student's Manager</h4>
                  <small>Create Student's ID</small>
                </td>
              </tr>
            </table>
          </div>
        </div>


        <div class="card" style="background:var(--lighter); color:var(--darker);" onclick="lnk5();">
          <img class="card-img-top" alt="">
          <div class="card-body">
            <table >
              <tr>
                <td style="width:50px;color:var(--dark);"><i class="material-icons">receipt</i></td>
                <td>
                  <h4>Student's Profile</h4>
                  <small>To Manage/Update Student Profile tap this box,<br>Or, click on the person icon in the bottom
                    bar.</small>
                </td>
              </tr>
            </table>
          </div>
        </div>


      <?php } ?>


    </div>

  </main>
  <div style="height:52px;"></div>
  <footer>
    <!-- place footer here -->
  </footer>
  <!-- Bootstrap JavaScript Libraries -->

  <script>
    document.getElementById("cnt").innerHTML = "<?php echo $cnt; ?>";

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
    function lnk7() { window.location.href = "settingsinstituteinfo.php"; }
    function lnk6() { window.location.href = "settingsclass.php"; }
    function lnk4() { window.location.href = "settingsteacher.php"; }
    function lnk2() { window.location.href = "settingsstudent.php"; }
    function lnk1() { window.location.href = "settingssubject.php"; }
    function lnk5() { window.location.href = "classsection.php"; }
    function lnk8() { window.location.href = "userlist.php"; }
    function lnk3() { window.location.href = "classes.php"; }



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

      var infor = "sccode=<?php echo $sccode; ?>&cls=" + cls + "&sec=" + sec;
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
  </script>



</body>