<?php

$bell_cnt = 0;
$sql0 = "SELECT count(*) as cnt FROM notification where sccode='$sccode' and tomail='$usr' and rwstatus=0;";
// echo $sql0;
$result0rtx_top_bar_notification_count = $conn->query($sql0);
if ($result0rtx_top_bar_notification_count->num_rows > 0) {
    while ($row0 = $result0rtx_top_bar_notification_count->fetch_assoc()) {
        $bell_cnt = $row0['cnt'];
    }
}

$kbase_cnt = 2;
$kbase_clr = 'var(--dark)';

if ($bell_cnt == 0) {
    $bell_clr = 'var(--light)';
    $bell_dsbl = 'disabled';
} else {
    $bell_clr = 'var(--dark)';
    $bell_dsbl = '';
}

$todo_cnt = 2;
$todo_clr = 'var(--light)';
$todo_dsbl = 'disabled';

$sms_cnt = 5;
$sms_clr = 'var(--light)';
$sms_dsbl = 'disabled';
?>

<div style="text-align:center; padding: 10px 15px;">
    <table style=" width:100%; ">
        <tr>
            <?php if ($userlevel == 'Administrator') {
                if ($ssx > 0) {
                    $ccx = 'dark';
                } else {
                    $ccx = 'lighter';
                }
                echo '<td class="wd" style="color:var(--' . $ccx . ');"><span class="" onclick="issue();"><i class="bi bi-patch-question-fill"></i></span></td>';
            }
            ?>


            <td class="wd" style="font-size:36px; color:<?php echo $kbase_clr; ?>"><span class=""
                    onclick="top_bar_kbase();"><i class="bi bi-node-plus-fill"></i></span></td>


            <td class="wd"><span class="" style="<?php echo 'color:' . $bell_clr . ';'; ?>"
                    onclick="top_bar_notification();" <?php echo $bell_dsbl; ?>><i class="bi bi-bell-fill"></i></span>
            </td>


            <td class="wd"><span class="" style="<?php if ($y + $n > 0 && $perc < 100) {
                echo 'color:' . $todo_clr . ';';
            } ?>" onclick="act3();"><i class="bi bi-check2-circle"></i></span></td>


            <td class="wd" style="font-size:28px; ">
                <div onclick="act4" style=" position:relative;">
                    <span class="" style="color:<?php echo $sms_clr; ?>; font-size:32px;"><i
                            class="bi bi-chat-square-fill"></i></span>
                    <div class="d-flex"
                        style="text-align:center; position:absolute; color:var(--lighter); top:1.05em; left:48%; font-size:11px;">
                        <?php echo $sms_cnt; ?>
                    </div>
                </div>
            </td>




        </tr>

    </table>
</div>