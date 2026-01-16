<?php
include 'inc.php';
?>

<main>
  <div class="container-fluidx">
    <div class="card text-left" style="background:var(--dark); color:var(--lighter);">
      <div class="card-body page-top-box">

        <div class="menu-icon"><i class="bi bi-vr"></i></div>
        <div class="menu-text"> SMS Campaign </div>
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
    $datam_campaign = array();
    $sql0 = "SELECT * FROM sms_campaign where sccode = '$sccode'  order by date desc, modifieddate desc, id desc";
    //echo $sql0;
    $result0 = $conn->query($sql0);
    if ($result0->num_rows > 0) {
      while ($row0 = $result0->fetch_assoc()) {
        $datam_campaign[] = $row0;
      }
    }

    $total_qnt  = 0;
    foreach ($datam_campaign as $camps) {
      ?>
      <div class="card text-start gg mb-1" style="background:var(--lighter); color:var(--darker);"
        onclick="class_section_list_for_student_list_edit('<?php echo $lnk; ?>')">
        <div class="card-body ">
          <div class="row">
            <div class="col-6">
              <div class="st-id text-muted">Name of Campaign</div>
              <div class="st-id mb-2"><?php echo $camps['camp_name']; ?></div>

              <div class="st-id text-muted">Campaign ID</div>
              <div class="st-roll text-small fw-bold mb-2"><?php echo $camps['camp_id']; ?></div>



            </div>


            <div class="col-6">
              <div class="st-id text-muted">Audience</div>
              <div class="st-id mb-2">
                <?php echo $camps['audi_param_1'] . ' : ' . $camps['audi_param_2'] . ' -> ' . $camps['audi_param_3']; ?>
              </div>

              <div class="st-id text-muted">Message (Qnt)</div>
              <div class="st-roll text-small fw-bold mb-2"><?php echo $camps['total_count'] ; ?>
              </div>


            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <div class="st-id text-muted">Message Body</div>
              <div class="st-id mb-2"><?php echo $camps['sms_text']; ?></div>
            </div>
          </div>
        </div>
      </div>
    <?php $total_qnt += $camps['total_count']; } ?>


  </div>

</main>

<div style="height:52px;"></div>

<script>

document.getElementById("cnt").innerHTML = <?php echo $total_qnt;?>;

</script>