<?php
    include 'incc.php';
    
    $sccode0 = $sccode * 10;
	$inex = $_POST['inex']; $partid1 = $_POST['partid1']; $partid2 = $_POST['partid2']; $date = $_POST['date']; 
	$descrip = $_POST['descrip']; $amount = $_POST['amount']; $memo = $_POST['memo']; 
	
	$search_array= array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০");
    $replace_array= array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
    $amount = str_replace($search_array, $replace_array, $amount);
	
	
	if($inex=='true'){
	    $category = 'Expenditure'; $partid=$partid2; $income = 0; $expenditure = $amount;
	} else {
	    $category = 'Income'; $partid=$partid1; $income = $amount; $expenditure = 0;
	}
	
	$query33 ="insert into cashbook(id, sessionyear, sccode, date, type, partid, memono, particulars, income, expenditure, amount, entryby, entrytime, status)
		VALUES (NULL, '$sy', '$sccode0', '$date', '$category', '$partid', '$memo', '$descrip', '$income', '$expenditure', '$amount', '$usr', '$cur', '1' );";
		$conn->query($query33); 

		echo '<b>Entry Saved Successfully.</b>';
		
?>