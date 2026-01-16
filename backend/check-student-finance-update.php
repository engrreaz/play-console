<?php

$iix = array_search($itemcode, array_column($finsetupind, 'itemcode'));
if($iix != '' || $iix != NULL){
    $paya = $finsetupind[$iix]['amount'];
}

$taka = $amt;
$idmon = '';
if ($stfinid == 0) {

    // echo '<span class="text-success">3 / INSERT</span>';
    $query3pxg = "INSERT INTO stfinance (id, sccode, sessionyear, classname, sectionname, stid, rollno, partid, itemcode,  particulareng, particularben, amount, month, idmon, setupdate, setupby, payableamt, modifieddate, paid, dues, last_update, validate, validationtime) 
                          VALUES (NULL, '$sccode', '$syear', '$cls', '$sec', '$stid', '$roll', '$partid', '$itemcode', '$partex', '$partbx', '$taka', '$z', '$idmon', '$cur', '$usr', '$paya', '$cur', '0', '$paya', '$cur', 1, '$cur') ;";
    $conn->query($query3pxg);
    // echo $query3pxg;
    $new++;

} else {

    if ($paid > 0) {

        // echo '<span class="text-muted">5 / NO NEED</span>';
        $query3pxg = "UPDATE stfinance set validate=1 where id='$stfinid' ;";
        $conn->query($query3pxg);
        // echo $query3pxg;
        $noneed++;

    } else {

        // echo '<span class="text-warning">4 / UPDATE</span> ';
        $query3pxg = "UPDATE stfinance set validate=1, amount='$amt', payableamt='$paya', modifieddate='$cur', modifiedby='$usr', dues=payableamt - paid where id='$stfinid' ;";
        $conn->query($query3pxg);
        // echo $query3pxg;
        $update++;

    }

}