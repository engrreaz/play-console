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

$room_id   = (int) ($_GET['room_id'] ?? 0);
$examtitle = $_GET['examtitle'] ?? '';


if (!$room_id || !$examtitle || !$sccode) {
    die("Invalid Request");
}

$examtitle_esc = $conn->real_escape_string($examtitle);
$sccode_esc    = $conn->real_escape_string($sccode);

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

if (!$room) die("Room not found");

/*
|--------------------------------------------------------------------------
| ALLOCATION + STUDENT JOIN
|--------------------------------------------------------------------------
*/

$alloc_map = [];

$alloc_q = mysqli_query($conn, "
    SELECT 
        a.*,
        s.stnameeng,
        s.stnameben
    FROM seat_plan_allocations a
    JOIN seat_plans p ON a.plan_id = p.id
    LEFT JOIN students s 
        ON s.stid = a.stid 
        AND s.sccode = '$sccode_esc'
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
| HTML
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
    margin-top:4px;
}

table{
    width:100%;
    border-collapse:collapse;

}

th,td{
    border:1px solid #333;
    padding:5px;
    font-size:11px;
    text-align:center;
    height:30px;
}

th{
    background:#f2f2f2;
    font-weight:bold;
}

.bench{
    font-weight:bold;
}

</style>

<div class="title">
    <h2>Room Wise Attendance Sheet</h2>
    <div class="meta">
        Exam: ' . htmlspecialchars($examtitle) . ' |
        Room: ' . htmlspecialchars($room['room_name']) . ' |
        Building: ' . htmlspecialchars($room['building_name']) . '
    </div>
</div>

<table>
<tr>
    <th style="width: 60px; font-size:9px;">Bench-Seat</th>
    <th style="width:150px;">Class - Section</th>
    <th style="width:50px;">Roll</th>
    <th style="width:200px;">Student Name</th>
    <th style="width:80px;">ID</th>
    <th >Signature</th>
</tr>
';

foreach ($benches as $bench) {

    if ($bench['is_blocked']) {
        continue;
    }

    foreach ($bench['allocations'] as $a) {

        $name = $a['stnameeng'] ?: $a['stnameben'];

        $html .= '
        <tr>
            <td class="bench">'.$bench['row_no'].'-'.$bench['col_no'].'-'.$a['seat_no'].'</td>
            <td>'.$a['classname'].' - '.$a['sectionname'].'</td>
            <td>'.$a['rollno'].'</td>
            <td>'.$name.'</td>
            <td>'.$a['stid'].'</td>
            <td></td>
        </tr>
        ';
    }
}

$html .= '
</table>

' . $custom_footer;

/*
|--------------------------------------------------------------------------
| PDF
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
$mpdf->Output("Attendance-" . $room['room_name'] . ".pdf", "D");

exit;