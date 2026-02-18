<?php
include('../inc.light.php');

$id = intval($_POST['fid'] ?? 0);
$amt = $fineamt =  floatval($_POST['amt'] ?? 0);
$tail = intval($_POST['tail'] ?? 0);

$month = date('m');

$conn->begin_transaction();

try {

    /* =======================
        TAIL = 1 (SPLIT)
    ======================== */
    if ($tail === 1) {

        // Copy row
        $sql = "
        INSERT INTO stfinance (
            sccode, sessionyear, classname, sectionname,
            stid, rollno, partid, itemcode,
            particulareng, particularben,
            amount, month, idmon,
            setupdate, setupby,
            payableamt,
            pr1date, pr1by,
            paid, paidx, dues,
            pr1, pr1no, pr1date, pr1by, cashbook1,
            pr2, pr2no, pr2date, pr2by, cashbook2,
            remark, extra, last_update,
            `validate`, validationtime,
            deleteby, deletetime,
            splitid, scan_status
        )
        SELECT
            sccode, sessionyear, classname, sectionname,
            stid, rollno, partid, itemcode,
            particulareng, particularben,
            amount, month, idmon,
            ?, ?,
            payableamt,
            ?, ?,
            paid, paidx, dues,
            pr1, pr1no, pr1date, pr1by, cashbook1,
            pr2, pr2no, pr2date, pr2by, cashbook2,
            remark, extra, last_update,
            `validate`, validationtime,
            deleteby, deletetime,
            splitid, scan_status
        FROM stfinance
        WHERE id = ? AND sccode = ?
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssis",
            $cur,
            $usr,
            $cur,
            $usr,
            $id,
            $sccode
        );
        $stmt->execute();

        if ($stmt->affected_rows !== 1) {
            throw new Exception("Split copy failed");
        }

        $newId = $conn->insert_id;

        // Update original
        $stmt = $conn->prepare(
            "UPDATE stfinance
             SET payableamt = ?, dues = ?, splitid = ?
             WHERE id = ? AND sccode = ?"
        );
        $stmt->bind_param("ddiis", $amt, $amt, $newId, $id, $sccode);
        $stmt->execute();

        // Update new
        $stmt = $conn->prepare(
            "UPDATE stfinance
             SET payableamt = payableamt - ?,
                 dues = dues - ?,
                 splitid = NULL
             WHERE id = ? AND sccode = ?"
        );
        $stmt->bind_param("ddis", $amt, $amt, $newId, $sccode);
        $stmt->execute();
    }

    /* =======================
        TAIL = 2 (MERGE)
    ======================== */ elseif ($tail === 2) {

        $splitid = $amt;

        $stmt = $conn->prepare(
            "SELECT splitid FROM stfinance
             WHERE id=? AND sccode=?"
        );
        $stmt->bind_param("is", $splitid, $sccode);
        $stmt->execute();
        $stmt->bind_result($connectid);
        $stmt->fetch();
        $stmt->close();

        if ($connectid == 0) {

            // Sum back
            $stmt = $conn->prepare("
                UPDATE stfinance
                SET payableamt = (
                        SELECT SUM(payableamt)
                        FROM stfinance
                        WHERE id IN (?, ?) AND sccode=?
                    ),
                    dues = (
                        SELECT SUM(dues)
                        FROM stfinance
                        WHERE id IN (?, ?) AND sccode=?
                    ),
                    splitid = NULL
                WHERE id=? AND sccode=?
            ");

            $stmt->bind_param(
                "isisisis",
                $id,
                $splitid,
                $sccode,
                $id,
                $splitid,
                $sccode,
                $id,
                $sccode
            );

            $stmt->execute();

            // delete split row
            $stmt = $conn->prepare(
                "DELETE FROM stfinance WHERE id=? AND sccode=?"
            );
            $stmt->bind_param("is", $splitid, $sccode);
            $stmt->execute();
        }
    }

    /* =======================
        TAIL = 3 (ADD FINE)
    ======================== */ elseif ($tail === 3) {

        $rowid = null;
        $itemcode = null;

        /* --------- 1️⃣ Try fine row ---------- */
        $stmt = $conn->prepare("
    SELECT id, itemcode FROM stfinance
    WHERE sccode=? AND stid=? AND sessionyear LIKE ?
      AND particulareng LIKE '%fine%'
    LIMIT 1
");

        $stmt->bind_param("sis", $sccode, $id, $sessionyear_param);
        $stmt->execute();
        $stmt->bind_result($rowid, $itemcode);
        $stmt->fetch();
        $stmt->close();


        /* --------- 2️⃣ Fallback ---------- */
        if ($rowid === null) {

            $stmt = $conn->prepare("
        SELECT id, itemcode FROM stfinance
        WHERE sccode=? AND stid=? AND sessionyear LIKE ?
        LIMIT 1
    ");

            $stmt->bind_param("sis", $sccode, $id, $sessionyear_param);
            $stmt->execute();
            $stmt->bind_result($rowid, $itemcode);
            $stmt->fetch();
            $stmt->close();

        }


        /* --------- 3️⃣ Still not found ---------- */
        if ($rowid === null) {
            $itemcode = uniqid();
            $rowid = 0;
        }


        // echo $itemcode . '/' . $rowid;




        $stmt = $conn->prepare("
            INSERT INTO stfinance (
            sccode, sessionyear, classname, sectionname,
            stid, rollno, partid, itemcode,
            particulareng, particularben,
            amount, month, idmon,
            setupdate, setupby,
            payableamt,
            paid, paidx, dues,
            pr1, pr1no, pr1date, pr1by, cashbook1,
            pr2, pr2no, pr2date, pr2by, cashbook2,
            remark, extra, last_update,
            `validate`, validationtime,
            deleteby, deletetime,
            splitid, scan_status
            )
            SELECT
            sccode, sessionyear, classname, sectionname,
            stid, rollno, partid, ?, 
            'FINE','জরিমানা',
            ?, ?, idmon,
            ?, ?,
            ?, 
            0, paidx, ?,
            0, null, null, null, cashbook1,
            pr2, pr2no, pr2date, pr2by, cashbook2,
            remark, extra, last_update,
            `validate`, validationtime,
            deleteby, deletetime,
            splitid, scan_status
            FROM stfinance
            WHERE id=?
            ");

        // 8 placeholders → 8 types
        $stmt->bind_param(
            "sdissddi",
            $itemcode,   // s
            $fineamt,        // d
            $month,      // i (or s if varchar)
            $cur,        // s
            $usr,        // s
            $fineamt,        // d
            $fineamt,        // d
            $rowid       // i
        );

        $stmt->execute();

        // echo "Affected rows = " . $stmt->affected_rows;
        // exit;




    }

    /* =======================
        TAIL = 4 (DELETE)
    ======================== */ elseif ($tail === 4) {

        $stmt = $conn->prepare(
            "DELETE FROM stfinance WHERE id=? AND sccode=?"
        );
        $stmt->bind_param("is", $id, $sccode);
        $stmt->execute();
    }

    $conn->commit();
    echo "OK";

} catch (Exception $e) {

    $conn->rollback();
    http_response_code(500);
    echo "DB ERROR: " . $e->getMessage();
}
