<?php
include 'inc.back.php';


require_once dirname(__DIR__) . '/component/sms-func.php';


$count = $_POST['count'];
;   //$ = $_POST[''];;  
$stid = $_POST['stid'];
$rollno = $_POST['rollno'];
$cls = $_POST['cls'];
$sec = $_POST['sec'];
$neng = $_POST['neng'];
$nben = $_POST['nben'];
$prno = $_POST['prno'];
$prdate = $_POST['prdate'];
$mobileno = $_POST['mobileno'];  //$ = $_POST[''];  $ = $_POST[''];  $ = $_POST[''];  $ = $_POST[''];    



$tamt = 0;
for ($lp = 0; $lp < $count; $lp++) {
	$fid = $_POST['fid' . $lp];
	$pr1 = 0;
	$pr2 = 0;
	;
	$amt = $_POST['amt' . $lp];
	;
	$sql0r = "SELECT * FROM stfinance where id='$fid' ";
	$result0r = $conn->query($sql0r);
	if ($result0r->num_rows > 0) {
		while ($row0r = $result0r->fetch_assoc()) {
			$pr1 = $row0r["pr1"];
			$pr2 = $row0r["pr2"];
		}
	}
	if ($pr1 > 0) {
		$fld = 'pr2';
		$flddt = 'pr2date';
		$fldby = 'pr2by';
		$fldno = 'pr2no';
	} else {
		$fld = 'pr1';
		$flddt = 'pr1date';
		$fldby = 'pr1by';
		$fldno = 'pr1no';
	}
	$query3g = "update stfinance set $fld='$amt', $fldno='$prno', $flddt='$prdate', $fldby='$usr', paid=paid+'$amt', dues=dues-'$amt' where id='$fid';";
	$conn->query($query3g);
	$tamt = $tamt + $amt;
}


$smstxt = '';
$smscnt = 0;
$st = 0;
$stval = '';

$query33 = "insert into stpr(id, sessionyear, sccode, classname, sectionname, stid, rollno, prno, prdate, partid, amount, entryby, entrytime, smstxt, smscnt, mobileno, smsstatus, statusvalue)
		VALUES (NULL, '$sy', '$sccode', '$cls', '$sec', '$stid', '$rollno', '$prno', '$prdate', '', '$tamt', '$usr', '$cur', '$smstxt', '$smscnt', '$mobileno', '$st', '$stval' );";
$conn->query($query33);


$query3x = "update sessioninfo set lastpr='$prno' where stid='$stid' and sessionyear='$sy';";
$conn->query($query3x);


$ins_list = [103187, 700007];

if(in_array($sccode, $ins_list) ){
	


$message = "প্রিয় অভিভাবক, " . $nben . ", " . $cls . " (" . $sec . ") - " . $rollno . " এর নামে = " . $tamt . ".00 টাকা জমা হয়েছে। ধন্যবাদ।\nঅধ্যক্ষ,\n" . $short;

$len = strlen($message);
$response = json_decode(sms_send($mobileno, $message));

// echo '<pre>' . print_r($response) . '</pre>';

$response_code = $response->response_code ?? '';
$message_id = $response->message_id ?? '';
$success_message = $response->success_message ?? '';
$error_message = $response->error_message ?? '';
$count = ceil($len / 155);
$cost = 0.50 * $count;

// ডাটাবেজে লগ সংরক্ষণ
if ($response_code == 202) {
	$sqls = "INSERT INTO sms
    (sccode, sessionyear, date, campaign, sms_type, mobile_number, sms_text, sms_len, count, send_by, send_time, cost,
     response_code, message_id, success_message, error_message, status, modifieddate)
    VALUES
    ('$sccode', '$SY', '$prdate', '0', 'Payment Info', '$mobileno', '$message', '$len', '$count', '$usr', '$cur', '$cost',
     '$response_code', '$message_id', '$success_message', '$error_message', 'Sent', '$cur')";

	// echo $sqls;
	$conn->query($sqls);
}


}









?>