<?php

require_once '../db.php';
require_once '../inc.php';

// incoming filters
$slot = $_POST['slot'];
$session = $_POST['session'];
$class = $_POST['cls'];
$section = $_POST['sec'];

// subjects list
$q = mysqli_query($conn, "SELECT subject FROM subsetup 
    WHERE sccode='$sccode' 
    AND slot='$slot' 
    AND sessionyear='$session'
    AND classname='$class' 
    AND sectionname='$section'");

$data = [];

while ($r = mysqli_fetch_assoc($q)) {

    $code = $r['subject'];

    // subject name from subjects table
    $q2 = mysqli_query($conn, "SELECT subject 
        FROM subjects 
        WHERE (sccode='$sccode' OR sccode='0')
        AND sccategory='$sctype'
        AND subcode='$code'
        LIMIT 1");

    $subname = "";
    if ($s2 = mysqli_fetch_assoc($q2)) {
        $subname = $s2['subject'];
    }

    $label = $code . " - " . $subname;

      echo "<option value='$code'>$label</option>";

}

