<?php
require_once '../inc.light.php';


$slot = $_COOKIE['chain-slot'] ?? $sctype;
$sessionyear = $_COOKIE['chain-session'] ?? $SY;


echo '<option value=""></option>';

$q = "SELECT DISTINCT areaname
      FROM areas
      WHERE sccode='$sccode'
        AND sessionyear LIKE '%$sessionyear%'
        AND slot='$slot'
      ORDER BY areaname";

$r = $conn->query($q);
while ($row = $r->fetch_assoc()) {
    echo "<option value='{$row['areaname']}'>{$row['areaname']}</option>";
}
