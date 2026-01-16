<?php
include 'inc.php';
$diff = strtotime($cur) - strtotime($otptime);
if ($diff > 120) {
  $otp = '';
}

?>

<main>
  <div class="container-fluidx">
    <div class="card text-left">
      <div class="card-body page-top-box">
        <table width="100%" style="color:white;">
          <tr>
            <td>
              <div class="menu-icon"><i class="bi bi-lock-fill"></i></div>
              <div class="menu-text"> Login Security</div>
            </td>
          </tr>
        </table>
      </div>
    </div>


    <div class="card menu-item-block" onclick="generate_otp();">
      <div class="card-body">
        <table class=" m-0 " style="width:100%;">
          <tr>
            <td class="menu-item-icon"><i class="bi bi-lock-fill "></i></td>
            <td>
              <h4 class="menu-title mt-2">Web Login Token</h4>
              <div class="menu-sub-title pt-2">Generate a one time web login token</div>
            </td>
            <td>
            </td>
          </tr>
          <?php if ($otp != '') { ?>
            <tr>
              <td></td>
              <td>
                <div id="keykey">
                  <small><br>Your Generated Web Login Toke is </small>
                  <div style="font-size:30px; color:gray; letter-spacing:10px; font-weight:bold;"><?php echo $otp; ?>
                  </div>
                </div>
              </td>
            </tr><?php } ?>
        </table>
      </div>
    </div>



    <div class="menu-separator"></div>


    <div class="card menu-item-block">
      <div class="card-body">
        <table style="width:100%;">
          <tr>
            <td class="menu-item-icon"><i class="bi bi-key-fill"></i></td>
            <td>
              <h4>Password</h4>
              <div class="menu-sub-title pt-2">Generate a fixed pin</div>
            </td>
            <td style="border:0px solid gray;width:20%;">
              <div class="form-check form-switch" style="text-align:right; ">
                <input class="form-control form-check-input" style="transform:scale(2.0); " type="checkbox"
                  id="passswitch" value="yes" onclick="pass();" checked>
              </div>


            </td>
          </tr>
          <tr>
            <td></td>
            <td colspan="2">
              <div class="" id="passbox">
                <div class="input-group" style="width:90%;">
                  <span class="input-group-text"> <i class="bi bi-key"></i> </span>
                  <input type="password" class="form-control text-box" style="font-size:15px" id="password" placeholder="password" disabled/>
                  <butoon class="btn btn-dark" type="button" onclick="update_password();" disabled>Update</butoon>
                </div>
              </div>
            </td>
          </tr>




        </table>
      </div>
    </div>
    <div class="menu-separator"></div>

    <div class="card menu-item-block" onclick="lnk1d();">
      <div class="card-body">
        <table style="width:100%">
          <tr>
            <td class="menu-item-icon"><i class="bi bi-google"></i></td>
            <td>
              <h4 class="menu-title mt-2">Login With Google</h4>
              <div class="menu-sub-title pt-2">Login with your gmail account</div>
            </td>
            <td style="border:0px solid gray; width:20%;">
              <div class="form-check form-switch" style="text-align:right;">
                <input class="form-check-input" style="transform:scale(2.0); " type="checkbox" id="mySwitch" value="yes"
                  checked>
              </div>
            </td>
          </tr>
          <tr>
            <td>

            </td>
            <td>

            </td>
          </tr>
        </table>
      </div>
    </div>

    <style>

    </style>
    <div class="menu-separator"></div>
    <div class="card menu-item-block" onclick="lnk11();">
      <div class="card-body">
        <table style="width:100%">
          <tr>
            <td class="menu-item-icon"> <i class="bi bi-qr-code"></i></td>
            <td>
              <h4 class="menu-title mt-2">Login With QR Code</h4>
              <div class="menu-sub-title pt-2">Login with a auto generated QR Code</div>
            </td>
            <td style=" width:20%;">
              <div class="form-check form-switch" style=" text-align:right;">
                <input class="form-check-input" style="transform:scale(2.0); " type="checkbox" id="mySwitch" value="yes"
                  checked>
              </div>
            </td>
          </tr>
          <tr>
            <td></td>
            <td>

            </td>
          </tr>
        </table>
      </div>
    </div>


    <div class="menu-separator"></div>

  </div>

</main>
<div style="height:52px;"></div>

<script>

  function pass() {

    var passswitch = document.getElementById("passswitch").checked;
    // alert(passswitch);
    if (passswitch === true) {
      document.getElementById("passbox").style.display = 'block';
    } else {
      document.getElementById("passbox").style.display = 'none';
    }
  }

  pass();



</script>


<script>
  function generate_otp() {
    infor = "user=<?php echo $usr; ?>";
    // alert(infor);
    $("#keykey").html("");

    $.ajax({
      type: "POST",
      url: "backend/genotp.php",
      data: infor,
      cache: false,
      beforeSend: function () {
        $('#keykey').html('<i class="material-icons">key</i> <br><small>Token is generating now. Please wait....</small>');
      },
      success: function (html) {
        $("#keykey").html(html);
      }
    });
  }
</script>