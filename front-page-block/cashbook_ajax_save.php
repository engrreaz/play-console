<?php
include '../inc.light.php';

if($_SERVER['REQUEST_METHOD']=='POST'){

    $date       = $_POST['date'];
    $partid     = $_POST['partid'];
    $head_id    = $_POST['head_code'];

    $particulars =
        mysqli_real_escape_string(
            $conn,
            $_POST['particulars']
        );

    $amount     = $_POST['amount'];
    $type       = $_POST['type'];

    $memono =
        mysqli_real_escape_string(
            $conn,
            (int)$_POST['memono']
        );

    $month = date('n', strtotime($date));
    $year  = date('Y', strtotime($date));

    $pending_sccode = $sccode * 10;

    $sql = "
        INSERT INTO cashbook
        (
            sccode,
            date,
            account_head,
            partid,
            particulars,
            amount,
            type,
            memono,
            month,
            year,
            entryby,
            entrytime,
            sessionyear
        )

        VALUES
        (
            '$pending_sccode',
            '$date',
            '$head_id',
            '$partid',
            '$particulars',
            '$amount',
            '$type',
            '$memono',
            '$month',
            '$year',
            '$usr',
            '$cur',
            '$sessionyear'
        )
    ";

    if(mysqli_query($conn,$sql)){

        echo 'success';

    }else{

        echo mysqli_error($conn);

    }

}
?>