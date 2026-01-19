<?php

require_once '../db.php';
require_once '../inc.php';

$slot = $_POST['slot'];
$session = $_POST['session'];

$q = mysqli_query($conn, "SELECT examtitle FROM examlist 
    WHERE sccode='$sccode' AND slot='$slot' AND sessionyear='$session'");

$data = [];
while ($r = mysqli_fetch_assoc($q)) {
    echo "<option value='{$r['examtitle']}'>{$r['examtitle']}</option>";
}
echo "<option value='Grand'>Grand</option>";

