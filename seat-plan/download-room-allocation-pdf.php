<?php

include '../inc.light.php';
require_once '../vendor/autoload.php';

use Mpdf\Mpdf;

/*
|--------------------------------------------------------------------------
| HEADER / FOOTER
|--------------------------------------------------------------------------
*/

ob_start();
include 'pdf-header.php';
$custom_header = ob_get_clean();

ob_start();
include 'pdf-footer.php';
$custom_footer = ob_get_clean();

/*
|--------------------------------------------------------------------------
| INPUT
|--------------------------------------------------------------------------
*/

$room_id = (int) ($_GET['room_id'] ?? 0);
$examtitle = $_GET['examtitle'] ?? '';

if (!$room_id || !$examtitle) {
    die("Invalid Request");
}

$examtitle_esc = $conn->real_escape_string($examtitle);

/*
|--------------------------------------------------------------------------
| ROOM INFO
|--------------------------------------------------------------------------
*/

$room = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT r.*, f.floor_name, b.building_name
    FROM seat_rooms r
    JOIN seat_floors f ON r.floor_id = f.id
    JOIN seat_buildings b ON f.building_id = b.id
    WHERE r.id='$room_id'
"));

if (!$room) {
    die("Room not found");
}

/*
|--------------------------------------------------------------------------
| CLASS COLORS
|--------------------------------------------------------------------------
*/

$class_colors = [
    'Six' => '#073e64',
    'Seven' => '#269b30',
    'Eight' => '#f71e83',
    'Nine' => '#7a1e88',
    'Ten' => '#fc0255'
];

/*
|--------------------------------------------------------------------------
| ALLOCATIONS
|--------------------------------------------------------------------------
*/

$alloc_map = [];

$alloc_q = mysqli_query($conn, "
    SELECT a.*
    FROM seat_plan_allocations a
    JOIN seat_plans p ON a.plan_id = p.id
    WHERE a.room_id='$room_id'
    AND p.examtitle='$examtitle_esc'
");

while ($row = mysqli_fetch_assoc($alloc_q)) {
    $alloc_map[$row['bench_id']][] = $row;
}

/*
|--------------------------------------------------------------------------
| BENCHES
|--------------------------------------------------------------------------
*/

$benches = [];

$bench_q = mysqli_query($conn, "
    SELECT *
    FROM seat_room_benches
    WHERE room_id='$room_id'
    ORDER BY row_no, col_no
");

while ($row = mysqli_fetch_assoc($bench_q)) {
    $row['allocations'] = $alloc_map[$row['id']] ?? [];
    $benches[] = $row;
}

/*
|--------------------------------------------------------------------------
| HTML START
|--------------------------------------------------------------------------
*/

$html = '
<style>

body{
    font-family: sans-serif;
    font-size: 10px;
}

.title{
    text-align:center;
    margin-bottom:10px;
}

.title h2{
    margin:0;
    font-size:18px;
}

.meta{
    font-size:11px;
    margin-top:3px;
}

.room-grid{
    border-collapse: collapse;
    width:100%;
}

.room-grid td{
    width:95px;

    border:1px solid #999;
    vertical-align:top;
    padding:3px;
}

.bench-no{
    font-weight:bold;
    font-size:9px;
    border-bottom:1px dashed #999;
    margin-bottom:3px;
}

.seat-wrap{
    width:100%;
    overflow:hidden;
}

.seat{
    width:48%;
    float:left;
    font-size:8px;
    padding:2px;
    // border:1px solid #ccc;
    box-sizing:border-box;
    min-height:40px;
}

.seat-right{
    float:right;
    text-align:right;
}

.roll{
    font-weight:bold;
    font-size:12px;
}

.class{
    font-size:10px;
    opacity:0.9;
}

.blocked{
    background:#e0e0e0;
}

.legend{
    margin:8px 0;
    text-align:center;
}

.legend-box{
    display:inline-block;
    padding:3px 6px;
    margin:2px;
    border-radius:4px;
    font-size:9px;
    border:1px solid #ccc;
}

</style>

' . '

<div class="title">
    <h2>Exam Seat Plan</h2>
    <div class="meta">
        <b>Exam:</b> ' . htmlspecialchars($examtitle) . ' |
        <b>Room:</b> ' . htmlspecialchars($room['room_name']) . ' |
        <b>Building:</b> ' . htmlspecialchars($room['building_name']) . '
    </div>
</div>

<div class="legend">
';

$html .= '<table style="margin:0 auto;"><tr>';

foreach($class_colors as $k => $v){

    $html .= '
    <td style="
        background:'.$v.';
        color:#fff;
        padding:6px 10px;
        font-size:10px;
        border-radius:4px;
        border:5px solid #ffffff;
        white-space:nowrap;
        cell-spacing:5px;
    ">
        '.$k.'
    </td>';
}

$html .= '</tr></table>';

$html .= '</div>

<table class="room-grid">
';

$total_cols = (int) $room['total_cols'];
$c = 0;

foreach ($benches as $bench) {

    if ($c == 0) {
        $html .= "<tr>";
    }

    $html .= '<td class="' . ($bench['is_blocked'] ? 'blocked' : '') . '">';

    $html .= '<div class="bench-no" style="text-align:center;">Bench No. ' . $bench['row_no'] . '-' . $bench['col_no'] . '</div>';

    if ($bench['is_blocked']) {

        $html .= '<div style="font-size:9px;">BLOCKED</div>';

    } else {

        $html .= '<table style="width:100%;font-size:9px;"><tr>';

        // group seats
        $seats = [];
        foreach ($bench['allocations'] as $a) {
            $seats[$a['seat_no']] = $a;
        }

        $html .= '<class="seat-wrap">';

        // LEFT SEAT (1)
        if (isset($seats[1])) {

            $a = $seats[1];
            $bg = $class_colors[$a['classname']] ?? '#f5f5f5';

            $html .= '<td  style="border:none; width:100%; min-height:30px;">
            <span class="seat" style="color:' . $bg . '">
                <span class="roll">' . $a['rollno'] . '</span>
                <span class="class"> - ' . $a['classname'] . ' - ' . $a['sectionname'] . '</span>
            </span></td>';
        } else {
            $html .= '<td style="border:none;"><div class="seat"></div></td>';
        }

        // RIGHT SEAT (2)
        if (isset($seats[2])) {

            $a = $seats[2];
            $bg = $class_colors[$a['classname']] ?? '#f5f5f5';

            $html .= '<td style="border:none; width:100%; min-height:30px; text-align:right;">
            <span class="seat seat-right" style="color:' . $bg . '">
                <span class="roll">' . $a['rollno'] . '</span>
                <span class="class"> - ' . $a['classname'] . ' - ' . $a['sectionname'] . '</span>
            </span></td>';
        } else {
            $html .= '<td style="border:none;"><div class="seat seat-right"></div></td>';
        }

        $html .= '<div style="clear:both;"></div>';



        $html .= '</tr></table>';

    }

    $html .= '</td>';

    $c++;

    if ($c >= $total_cols) {
        $html .= "</tr>";
        $c = 0;
    }
}

if ($c != 0) {
    while ($c < $total_cols) {
        $html .= "<td></td>";
        $c++;
    }
    $html .= "</tr>";
}

$html .= '
</table>

'

    . $custom_footer . '
';

/*
|--------------------------------------------------------------------------
| PDF GENERATE
|--------------------------------------------------------------------------
*/

$mpdf = new Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4'
]);

$mpdf->SetHTMLHeader($custom_header);

$mpdf->SetHTMLFooter('
<div style="text-align:right;font-size:9px;border-top:1px solid #ccc;padding-top:5px;">
Page {PAGENO}
</div>
');

$mpdf->WriteHTML($html);
$mpdf->Output("Seat-Plan-" . $room['room_name'] . ".pdf", "I");

exit;