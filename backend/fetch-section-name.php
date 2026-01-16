<?php
date_default_timezone_set('Asia/Dhaka');
include('inc.back.php');
$cls = $_POST['sec'];
// echo $cls;
?>

<label class="text-small ps-1" for="param3"> Filter Level 2 </label>
<select class="form-control" id="param3" onchange="store_data(5);">
	<option value=""></option>
	<?php
	$sql0x = "SELECT subarea FROM areas where sccode = '$sccode' and sessionyear LIKE '%$sy%' and user='$rootuser' and areaname='$cls' group by subarea order by subarea;";
	$result0rtagx = $conn->query($sql0x);
	if ($result0rtagx->num_rows > 0) {
		while ($row0x = $result0rtagx->fetch_assoc()) {
			$anamex = $row0x["subarea"];
			// echo $anamex . '<br><br><br>';
			echo '<option value="' . $anamex . '">' . $anamex . '</option>';
		}
	}

	?>
</select>

<script>
	campu = sessionStorage.getItem("param-3");
	document.getElementById("param3").value = campu;
</script>