<?php
include('../inc.light.php');

$adate = $_POST['adate'] ?? '';
$cn = $_POST['cls'] ?? '';
$sn = $_POST['sec'] ?? '';
$opt = (int) ($_POST['opt'] ?? 0);

/* =========================
   OPTION 2 : SINGLE STUDENT
========================= */
if ($opt === 2) {

    $stid = $_POST['stid'] ?? '';
    $roll = $_POST['roll'] ?? '';
    $yn = (int) ($_POST['val'] ?? 0);
    $per = (int) ($_POST['per'] ?? 1);

    /* ---------- submission lock check ---------- */
    $submit_found = 0;

    $chk = $conn->prepare("
        SELECT 1 FROM stattndsummery
        WHERE date=? AND sccode=? AND sessionyear LIKE ?
        AND classname=? AND sectionname=? LIMIT 1
    ");
    $chk->bind_param("sssss", $adate, $sccode, $sessionyear_param, $cn, $sn);
    $chk->execute();
    if ($chk->get_result()->num_rows > 0) {
        $submit_found = 1;
    }
    $chk->close();

    /* ---------- attendance exists? ---------- */
    $chk2 = $conn->prepare("
        SELECT id FROM stattnd
        WHERE stid=? AND adate=? AND sccode=?
        AND sessionyear LIKE ? AND classname=? AND sectionname=? LIMIT 1
    ");
    $chk2->bind_param("ssssss", $stid, $adate, $sccode, $sessionyear_param, $cn, $sn);
    $chk2->execute();
    $exists = ($chk2->get_result()->num_rows > 0);
    $chk2->close();

    /* ---------- CASE 1: attendance NOT exists ---------- */
    if (!$exists) {

        $one = 1;
        $zero = 0;
        $stmt = $conn->prepare("
            INSERT INTO stattnd
            (sccode, sessionyear, stid, adate, yn, bunk,
             period1, period2, period3, period4,
             period5, period6, period7, period8,
             entryby, classname, sectionname, rollno)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
        ");

        $stmt->bind_param(
            "isssiiiiiiiiissssi",
            $sccode,
            $sessionyear,
            $stid,
            $adate,
            $one,
            $zero,
            $one,
            $one,
            $one,
            $one,
            $one,
            $one,
            $one,
            $one,
            $usr,
            $cn,
            $sn,
            $roll
        );
        $stmt->execute();
        $stmt->close();

        echo "OK";
        exit;
    }

    /* ---------- CASE 2: attendance EXISTS ---------- */

    // submit_found = 0 → full absent
    if ($submit_found == 0) {

        $stmt = $conn->prepare("
            UPDATE stattnd SET
                bunk = 1 - bunk,
                period1 = 1 - period1,
                period2 = 1 - period2,
                period3 = 1 - period3,
                period4 = 1 - period4,
                period5 = 1 - period5,
                period6 = 1 - period6,
                period7 = 1 - period7,
                period8 = 1 - period8,
                entryby = ?
            WHERE stid = ?
            AND adate = ?
            AND sccode = ?
            AND sessionyear LIKE ?
            AND classname = ?
            AND sectionname = ?

        ");

        $stmt->bind_param(
            "sssssss",
            $usr,
            $stid,
            $adate,
            $sccode,
            $sessionyear_param,
            $cn,
            $sn
        );
        $stmt->execute();
        $stmt->close();

        echo "DUI";
        exit;
    }

    /* ---------- submit_found = 1 ---------- */

    if ($per < 2) {
        $per = 2;
    }

    // dynamic period zeroing
    $sets = [];
    for ($i = $per; $i <= 8; $i++) {
        $sets[] = "period$i=1 - period$i";
    }
    $set_sql = implode(", ", $sets);

    $stmt = $conn->prepare("
        UPDATE stattnd SET
        yn=1-yn,
        bunk=1-bunk,
        $set_sql,
        entryby=?
        WHERE stid=? AND adate=? AND sccode=?
        AND sessionyear LIKE ? AND classname=? AND sectionname=?
    ");

    $stmt->bind_param(
        "sssssss",
        $usr,
        $stid,
        $adate,
        $sccode,
        $sessionyear_param,
        $cn,
        $sn
    );
    $stmt->execute();
    $stmt->close();

    echo "TIN";
    exit;
}


/* =========================
   OPTION 5 : FINAL SUMMARY
========================= */
if ($opt === 5) {
    $cnt = (int) ($_POST['cnt'] ?? 0);
    $fnd = (int) ($_POST['fnd'] ?? 0);

    $rate = ($cnt > 0) ? ($fnd * 100 / $cnt) : 0;

    $stmt = $conn->prepare("
        INSERT INTO stattndsummery
        (sccode, sessionyear, date, classname, sectionname,
         totalstudent, attndstudent, attndrate, submitby, submittime)
        VALUES (?,?,?,?,?,?,?,?,?,?)
    ");
    $stmt->bind_param(
        "issssiidss",
        $sccode,
        $sessionyear,
        $adate,
        $cn,
        $sn,
        $cnt,
        $fnd,
        $rate,
        $usr,
        $cur
    );
    $stmt->execute();
    $stmt->close();

    echo "SUBMITTED";
    exit;
}
