<?php
include 'inc.php';

if (isset($_GET['id'])) {
  $cid = $_GET['id'];
} else {
  $cid = 0;
}

?>

<!doctype html>
<html lang="en">

<head>
  <title>Title</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  <link rel="stylesheet" href="css.css?v=a">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

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
      font-size: 20px;
      color: var(--darker);
      line-height: 12px;
      font-weight: 700;
    }

    h5 {
      font-size: 16px;
      color: var(--dark);
      line-height: 12px;
      font-weight: 500;
    }

    small {
      font-size: 10px;
      color: gray;
      line-height: 10px;
      margin: 3px 0 8px;
    }
  </style>
</head>

<body style="background:var(--lighter);">
  <header>
    <!-- place navbar here -->
  </header>
  <main>
    <div class="container-fluid">
      <div style="height:8px;"></div>
      <div class="card text-left" style="background:var(--dark); color:var(--lighter);">

        <div class="card-body">
          <table width="100%" style="color:white;">
            <tr>
              <td>
                <div
                  style="font-size:20px; text-align:center; padding: 2px 2px 8px; font-weight:700; line-height:15px;">
                  Marks & Assessment Entry Scanner
                </div>
              </td>
            </tr>
          </table>
        </div>
      </div>
      <div style="height:8px;"></div>

      <?php if ($userlevel == 'Administrator' || $userlevel == 'Head Teacher') { ?>



        <div class="card" style="background:#cccccc; color:var(--darker);" onclick="lnk1();">
          <img class="card-img-top" alt="">
          <div class="card-body">
            <table style="width:100%">
              <tr>
                <td style="font-size:13px;"><b>Select a Class / Section to View/Scanning... :</b></td>
                <td></td>
              </tr>
              <tr>
                <td>

                  <div style="text-align:left; padding-top:0px; display:none;">
                    <div class="input-group">
                      <span class="input-group-text"><i class="material-icons ico">group</i></span>
                      <input type="text" id="id" name="id" class="form-control" placeholder="Enter Section/Group Name"
                        value="">
                    </div>
                  </div>
                  <div class="form-group input-group">
                    <span class="input-group-text"><i class="material-icons ico">group</i></span>
                    <select class="form-control" id="cls" <?php if ($cid > 0) {
                      echo 'disabled';
                    } ?>>

                      <option value="">Choose a Class & Section</option>
                      <?php
                      $sql00xgr = "SELECT * FROM areas where user='$rootuser' and sessionyear = '$sy' order by idno, id";
                      $result00xgr = $conn->query($sql00xgr);
                      if ($result00xgr->num_rows > 0) {
                        while ($row00xgr = $result00xgr->fetch_assoc()) {
                          $id = $row00xgr["id"];
                          $cls2 = $row00xgr["areaname"];
                          $sec2 = $row00xgr["subarea"];
                          ?>
                          <option value="<?php echo $id; ?>" <?php if ($cid == $id) {
                              echo 'selected';
                            } ?>>
                            <?php echo $cls2 . ' | ' . $sec2; ?> </option>
                        <?php }
                      } ?>
                    </select>
                  </div>

                  <div style="margin:0px 0; height:5px; "></div>

                </td>
                <td>
                  <div id="perc"
                    style="text-align:right; margin-left:8px; font-size:20px; font-weight:700; color: purple; width:50px;">
                  </div>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <div style="margin:0px 0; height:5px; ;"></div>
                  <button class="btn btn-info" onclick="submit(1);">View</button>
                  <button class="btn btn-success" onclick="submit(0);">Scan</button>
                  <button class="btn btn-danger" onclick="merge(0,3);" id="rst">Reset</button>
                  <div id="put"></div>
                </td>
              </tr>
            </table>
            <span style="">To get a perfect report, please mark down the absent / drop out students from <a
                href="classsection.php">Students List</a>.</span>
          </div>
        </div>
        <div style="height:8px;"></div>


        <div class="card" style="background:#cccccc; color:var(--darker);" onclick="lnk1();">
          <img class="card-img-top" alt="">
          <div class="card-body">
            <button class="btn btn-info" onclick="checkabsentmarkentry(0);">Check Absent Student Mark Entry</button>

          </div>
        </div>
        <div style="height:8px;"></div>

        <div class="card" style="background:#cccccc; color:var(--darker);" onclick="lnk1();">
          <img class="card-img-top" alt="">
          <div class="card-body">
            <button class="btn btn-info" onclick="checkabsentmarkentry(2);">Duplicate Mark Entry</button>
            <div id="checkblock">aaa</div>
          </div>
        </div>
        <div style="height:8px;"></div>






        <div id="block">


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
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
    </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

  <script>
    function checkabsentmarkentry(tail) {
      var infor = "rootuser=<?php echo $rootuser; ?>&tail=" + tail;
      $("#checkblock").html("");

      $.ajax({
        type: "POST",
        url: "dbscanning.php",
        data: infor,
        cache: false,
        beforeSend: function () {
          $('#checkblock').html('<span class="">Scanning Database</span>');
        },
        success: function (html) {
          $("#checkblock").html(html);
        }
      });
    }

  </script>

  <script>
    function adddel(id, tail) {
      var id = document.getElementById("cls").value;


      var infor = "rootuser=<?php echo $rootuser; ?>&id=" + id + "&sccode=<?php echo $sccode; ?>
      $("#block").html("");

      $.ajax({
        type: "POST",
        url: "pibiprocessor.php",
        data: infor,
        cache: false,
        beforeSend: function () {
          $('#block').html('<span class=""><center>Processing, Please Wait....</center></span>');
        },
        success: function (html) {
          $("#block").html(html);
        }
      });
    }

  </script>

  <script>

    function del(id) {

      var infor = "id=" + id;
      $("#btn" + id).html("");

      $.ajax({
        type: "POST",
        url: "dupdel.php",
        data: infor,
        cache: false,
        beforeSend: function () {
          $('#btn' + id).html('....');
        },
        success: function (html) {
          $("#btn" + id).html(html);
        }
      });
    }


  </script>



  <script>

    function merge(id, ch) {
      if (ch == 3) { var id = document.getElementById("cls").value; document.getElementById('rst').innerHTML = "Resetting...."; }
      var infor = "id=" + id + "&ch=" + ch;
      $("#idl" + id).html("");
      $.ajax({
        type: "POST",
        url: "pibimerge.php",
        data: infor,
        cache: false,
        beforeSend: function () {
          $('#idl' + id).html('<small><b>Merging...</b></small>');
        },
        success: function (html) {
          //$("#put").html( html );
          $("#idl" + id).html(html);
          if (ch == 3) { document.getElementById('rst').innerHTML = "Reset"; submit(0); }
        }
      });
    }


  </script>

  <?php
  if ($cid > 0) {
    ?>
    <script>submit(0);</script><?php
  }
  ?>



</body>

</html>