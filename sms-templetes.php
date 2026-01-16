<?php
include 'inc.php';
?>

<main>
  <div class="container-fluidx">
    <div class="card text-left" style="background:var(--dark); color:var(--lighter);">
      <div class="card-body page-top-box">

        <div class="menu-icon"><i class="bi bi-file-post-fill"></i></div>
        <div class="menu-text"> SMS Templetes </div>
      </div>
      <div class="card-body page-info-box">
        <table width="100%" style="color:white;">
          <tr>
            <td>
              <div style="font-size:20px; font-weight:700; line-height:15px;"></div>
              <div style="font-size:12px; font-weight:400; font-style:italic; line-height:18px;">No. of Class & Section
              </div>
              <br>
              <div style="font-size:16px; font-weight:700; line-height:15px;"> </div>
              <div style="font-size:12px; font-weight:400; font-style:italic; line-height:18px;">Name of Section</div>
            </td>
            <td style="text-align:right;">
              <div style="font-size:30px; font-weight:700; line-height:20px;" id="cnt"></div>
              <div style="font-size:12px; font-weight:400; font-style:italic; line-height:24px;">No. of Students</div>
            </td>
          </tr>

        </table>
      </div>
    </div>


    <?php
    $sql0 = "SELECT * FROM sms_templete where (sccode = '$sccode' or sccode='0') order by id";
    //echo $sql0;
    $result0 = $conn->query($sql0);
    if ($result0->num_rows > 0) {
      while ($row0 = $result0->fetch_assoc()) {
        $id = $row0["id"];
        $smstext = $row0["smstemp"];
      
       
        ?>
        <div class="card text-center gg mb-1" style="background:var(--lighter); color:var(--darker);"
          onclick="class_section_list_for_student_list_edit('<?php echo $lnk; ?>')">
          <div class="card-body ">
            <table width="100%">
              <tr>
                <td style="text-align:left; padding-left:5px;">
                  <div class="st-id"><?php echo strtoupper($smstext); ?></div>
                 
                </td>
                <td style="text-align:right;"><img src="<?php echo $ico; ?>" class="st-pic-small" /></td>
              </tr>
            </table>
          </div>
        </div>
      <?php }
    } ?>


  </div>

</main>

<div style="height:52px;"></div>

<script>



</script>