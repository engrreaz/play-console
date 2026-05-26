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