<?php
include 'inc.php';
?>

<main>
  <div class="container-fluidx">
    <div class="card text-left" style="background:var(--dark); color:var(--lighter);">
      <div class="card-body page-top-box">

        <div class="menu-icon"><i class="bi bi-clock-history"></i></div>
        <div class="menu-text"> SMS History </div>
      </div>
      <div class="card-body page-info-box">
        <table width="100%" style="color:white;">
          <tr>
            <td>
              <div style="font-size:20px; font-weight:700; line-height:15px;"></div>
              <div style="font-size:12px; font-weight:400; font-style:italic; line-height:18px;">_
              </div>
              <br>
              <div style="font-size:16px; font-weight:700; line-height:15px;"> </div>
              <div style="font-size:12px; font-weight:400; font-style:italic; line-height:18px;">-</div>
            </td>
            <td style="text-align:right;">
              <div style="font-size:30px; font-weight:700; line-height:20px;" id="cnt"></div>
              <div style="font-size:12px; font-weight:400; font-style:italic; line-height:24px;">Total Sent</div>
            </td>
          </tr>

        </table>
      </div>
    </div>


    <?php
    $datam_sms = array();
    $sql0 = "SELECT * FROM sms where sccode = '$sccode'  order by date desc, modifieddate desc, id desc";
    //echo $sql0;
    $result0 = $conn->query($sql0);
    if ($result0->num_rows > 0) {
      while ($row0 = $result0->fetch_assoc()) {
        $datam_sms[] = $row0;
      }
    }

    $total_qnt = 0;
    foreach ($datam_sms as $camps) {
      $ccode = $camps['response_code'];
                $cost = $camps['cost'];
                if ($ccode == 1002) {
                  $icon = 'check-circle-fill';
                  $color = 'seagreen';
                } else {
                  $icon = 'x-circle-fill';
                  $color = '#800';
                }
      ?>
      <div class="card text-start gg mb-1" style="background:var(--lighter); color:var(--darker);"
        onclick="class_section_list_for_student_list_edit('<?php echo $lnk; ?>')">
        <div class="card-body ">
          <div class="row">
            <div class="col-9">
              <div class="stname-ben" style="color:<?php echo $color;?>;"><?php echo $camps['mobile_number']; ?></div>
            </div>


            <div class="col-3">
              <div class=" ">
                <?php
                


                ?>
                <div class="text-right">
                  <i class="bi bi-<?php echo $icon; ?>" style="color: <?php echo $color; ?>"></i>

                </div>
              </div>


            </div>


          </div>


          <div class="row">
            <div class="col-12">
              <div class="st-id text-black"><?php echo $camps['sms_text']; ?></div>
              <div class="st-id text-muted mb-2"><?php echo date('d-m-Y H:i:s', strtotime($camps['send_time'])); ?></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php 
    } ?>


  </div>

</main>

<div style="height:52px;"></div>

<script>

  document.getElementById("cnt").innerHTML = <?php echo $total_qnt; ?>;

</script>