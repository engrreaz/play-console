<?php

require_once '../db.php';
require_once '../inc.php';


$slot = $_COOKIE['chain-slot'] ?? $sctype;
$sy = $_COOKIE['chain-session'] ?? $SY;


echo '<option value=""></option>';

$q = "SELECT DISTINCT areaname
      FROM areas
      WHERE sccode='$sccode'
        AND sessionyear LIKE '%$sy%'
        AND slot='$slot'
      ORDER BY idno";

$r = $conn->query($q);
while ($row = $r->fetch_assoc()) {
    echo "<option value='{$row['areaname']}'>{$row['areaname']}</option>";
}
