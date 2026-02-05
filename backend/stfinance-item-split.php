<?php
date_default_timezone_set('Asia/Dhaka');
include('../inc.light.php');

$id = $_POST['fid'];
$amt = $_POST['amt'];
$tail = $_POST['tail'];
$month = date('m');


if ($tail == 1) {
    // echo $id . '/' . $amt . '/' . $tail;


    $sql = "INSERT INTO stfinance 
            SELECT NULL, sccode, sessionyear, classname, sectionname, stid, rollno, partid, itemcode, particulareng, particularben, amount, month, idmon, '$cur', '$usr', payableamt, '$cur', '$usr', paid, paidx, dues, pr1, pr1no, pr1date, pr1by, cashbook1, pr2, pr2no, pr2date, pr2by, cashbook2, remark, extra, last_update, validate, validationtime,  deleteby, deletetime, splitid, scan_status
            FROM stfinance 
            WHERE id = $id and sccode='$sccode'";

    // echo $sql;
    $conn->query($sql);
    $newId = $conn->insert_id;

    // echo "New copied row ID = " . $newId;

    $query331 = "UPDATE stfinance set payableamt='$amt', dues='$amt', splitid='$newId' where id = '$id' and sccode='$sccode'";
    $conn->query($query331);
    $query331 = "UPDATE stfinance set payableamt= payableamt-$amt, dues=dues-$amt, splitid=NULL where id = '$newId' and sccode='$sccode'";
    $conn->query($query331);

} else if ($tail == 2) {
    $splitid = $amt;
    // echo $id . '/' . $splitid . '/' . $tail;
    $connectid = 0;
    $sql5 = "SELECT * FROM stfinance where  sccode='$sccode' and id='$splitid'  ";
    $result5 = $conn->query($sql5);
    if ($result5->num_rows > 0) {
        while ($row5 = $result5->fetch_assoc()) {
            $connectid = $row5["splitid"];
        }
    }
    if ($connectid > 0) {
        // echo '--' . $connectid;

    } else {
        // echo '--ready';


        // $sqll = "  INSERT INTO stfinance 
        //         SELECT NULL, sccode, sessionyear, classname, sectionname, stid, rollno, partid, itemcode, particulareng, particularben, amount, month, idmon, '$cur', '$usr', sum(payableamt), '$cur', '$usr', sum(paid), paidx, sum(dues), sum(pr1), pr1no, pr1date, pr1by, cashbook1, pr2, pr2no, pr2date, pr2by, cashbook2, remark, extra, last_update, validate, validationtime,  deleteby, deletetime, NULL, scan_status
        //         FROM stfinance 
        //         WHERE id IN ($id, $splitid)
        //         GROUP BY stid, itemcode;";
        // $conn->query($sqll);

        // $query3311 = "DELETE FROM stfinance where id = '$id' and sccode='$sccode'";
        // $conn->query($query3311);
        // $query3312 = "DELETE FROM stfinance where id = '$splitid' and sccode='$sccode'";
        // $conn->query($query3312);

        $query331r = "UPDATE stfinance
                        SET payableamt = (
                                SELECT SUM(payableamt) 
                                FROM stfinance 
                                WHERE id IN ($id, $splitid) AND sccode='$sccode'
                            ),
                            dues = (
                                SELECT SUM(dues) 
                                FROM stfinance 
                                WHERE id IN ($id, $splitid) AND sccode='$sccode'
                            ),
                            splitid = NULL
                        WHERE id = $id AND sccode='$sccode';";

        //    echo $query331r;

        $conn->query($query331r);
        $query331x = "DELETE FROM stfinance  where id = '$splitid' and sccode='$sccode'";
        $conn->query($query331x);


    }



} else if ($tail == 3) {
    // echo 'Tail 3';


    $sql5t = "SELECT * FROM stfinance where  sccode='$sccode' and stid='$id' and sessionyear like '%$sessionyear_param%'  and particulareng like '%fine%'   LIMIT 1  ";
    $result5t = $conn->query($sql5t);
    if ($result5t->num_rows > 0) {
        while ($row5 = $result5t->fetch_assoc()) {
            $rowid = $row5["id"];
            // $itemcode = $row5["itemcode"];

            $sql = "INSERT INTO stfinance 
            SELECT NULL, sccode, sessionyear, classname, sectionname, stid, rollno, partid, itemcode, 'FINE', 'জরিমানা', '$amt', month, idmon, '$cur', '$usr', '$amt', '$cur', '$usr', paid, paidx, '$amt', pr1, pr1no, pr1date, pr1by, cashbook1, pr2, pr2no, pr2date, pr2by, cashbook2, remark, extra, last_update, validate, validationtime,  deleteby, deletetime, splitid, scan_status
            FROM stfinance 
            WHERE id = $rowid and sccode='$sccode'";

            // echo $sql;
            $conn->query($sql);
            // $newId = $conn->insert_id;


        }
        echo 'Pre';
    } else {
        $rowid = 0;
        $itemcode = uniqid();

        $sql5x = "SELECT * FROM stfinance where  sccode='$sccode' and stid='$id' and sessionyear like '%$sessionyear%' LIMIT 1 ";
        echo $sql5x;
        $result5x = $conn->query($sql5x);
        if ($result5x->num_rows > 0) {
            while ($row5 = $result5x->fetch_assoc()) {
                $rowid = $row5["id"];

                echo $rowid;

                $sql = "INSERT INTO stfinance 
            SELECT NULL, sccode, sessionyear, classname, sectionname, stid, rollno, partid, '$itemcode', 'FINE', 'জরিমানা', '$amt', '$month', idmon, '$cur', '$usr', '$amt', '$cur', '$usr', paid, paidx, '$amt', pr1, pr1no, pr1date, pr1by, cashbook1, pr2, pr2no, pr2date, pr2by, cashbook2, remark, extra, last_update, validate, validationtime,  deleteby, deletetime, splitid, scan_status
            FROM stfinance 
            WHERE id = $rowid and sccode='$sccode'";

                echo $sql;
                $conn->query($sql);


            }
        }

        // echo 'no fine record found';
    }

echo 'OK';

} else if ($tail == 4){
    echo 'Remove fine called';
    $kk = "DELETE FROM stfinance where  sccode='$sccode' and id='$id' ";
    $conn->query($kk);
}
