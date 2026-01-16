<?php
include 'incc.php';

    $tail = $_POST['tail']; 
    
    if($tail == 0){
        $id = $_POST['partid2']; $date = $_POST['date']; 
    	$amount = $_POST['amount']; 
    	
    	$sql0 = "SELECT * FROM banktrans where accid='$id' and sccode='$sccode' and transtype = 'Deposit' order by entrytime desc limit 1 ";
        $result01xgg = $conn->query($sql0); if ($result01xgg->num_rows > 0) {while($row0 = $result01xgg->fetch_assoc()) 
        { $chq=$row0["chqno"];  }} else { $chq = ($sccode % 10000) * 10000  ; } 
        
        if($chq==NULL || $chq == '' || $chq == 0){$chq = ($sccode % 10000) * 10000 ;}
        $chq = $chq * 1;
        $chq = $chq + 1;
    	
    	
    	$sql0 = "SELECT * FROM banktrans where accid='$id' and sccode='$sccode' order by entrytime desc limit 1 ";
        $result01xg = $conn->query($sql0); if ($result01xg->num_rows > 0) {while($row0 = $result01xg->fetch_assoc()) 
        { $accno=$row0["accno"];  $balance=$row0["balance"]; }} else { $accno=0;  $balance=0; } 
        $bala = $balance + $amount;
    	
    	$query33 ="insert into banktrans(id, sccode, accid, accno, date, transopening, transtype, amount, balance, entryby, entrytime, verified, chqno)
    		VALUES (NULL,  '$sccode', '$id', '$accno', '$date', '$balance', 'Deposit', '$amount', '$bala', '$usr', '$cur', '0', '$chq' );";
    		$conn->query($query33); 
    
    	
    	
    	    $sql0 = "SELECT classname, sectionname, pr1date, partid, sum(pr1) as mottaka FROM stfinance where sccode='$sccode' and sessionyear='$sy' and pr1> 0 and cashbook1=0 group by classname, sectionname, pr1date, partid";
            //echo $sql0;
            $result0 = $conn->query($sql0);
            if ($result0->num_rows > 0) 
            {while($row0 = $result0->fetch_assoc()) { 
            $ccc = $row0["classname"];  $sss = $row0["sectionname"];  $aaa = $row0["mottaka"];  $ddd = $row0["pr1date"];   $ppp = $row0["partid"];  
            $de = 'Collection : ' . $ccc . ' (' . $sss . ')';
                $jax = "insert into cashbook (id, sessionyear, sccode, date, type, partid, particulars,income, amount, entryby, entrytime, status) 
                                        VALUES (NULL, '$sy', '$sccode', '$ddd', 'Income', '$ppp', '$de', '$aaa', '$aaa', 'System-Auto', '$cur', 1 );";
                $conn->query($jax);
                
                $cax = "UPDATE stfinance set cashbook1=1 where sccode='$sccode' and sessionyear='$sy' and pr1> 0 and cashbook1=0 and classname='$ccc' and sectionname='$sss' and pr1date='$ddd';";
                $conn->query($cax);
            }}
    } 
    
    
    
    
    
    else if($tail == 1){
        $bank = $_POST['bank']; $date = $_POST['date']; 
    	$amount = $_POST['amount']; $type = $_POST['type']; $chqno = $_POST['chqno'];
    	
    	$sql0 = "SELECT * FROM financesetup where sccode='$sccode' and id='$type' ";
    //	echo $sql0;
        $result01 = $conn->query($sql0);
        if ($result01->num_rows > 0) 
        {while($row0 = $result01->fetch_assoc()) { 
            $partid = $row0["id"]; $txt2 = $row0["particulareng"]; $inex = $row0["inexex"];
        }}
    	
    	$sql0 = "SELECT * FROM banktrans where accid='$bank' and sccode='$sccode' order by entrytime desc limit 1 ";
    //    echo $sql0;
        $result01xg = $conn->query($sql0); if ($result01xg->num_rows > 0) {while($row0 = $result01xg->fetch_assoc()) 
        { $accid=$row0["accid"]; $accno=$row0["accno"];  $balance=$row0["balance"]; }} else { $accno=0;  $balance=0; } 
        if($inex == 3){
            $bala = $balance + $amount; $ttt = 'Income'; $iii = $amount; $eee = 0;
        } else {
            $bala = $balance - $amount; $ttt = 'Expenditure'; $iii = 0; $eee = $amount;
        }
        
    	
    	$query33 ="insert into banktrans(id, sccode, accid, accno, date, transopening, transtype, amount, balance, entryby, entrytime, verified, chqno)
    		VALUES (NULL,  '$sccode', '$accid', '$accno', '$date', '$balance', '$txt2', '$amount', '$bala', '$usr', '$cur', '0', '$chqno' );";
    	//	echo $query33;
    		$conn->query($query33); 
    	
    	
    	
    	$jax = "insert into cashbook (id, sessionyear, sccode, date, type, partid, particulars, income, expenditure, amount, entryby, entrytime, status) 
                                VALUES (NULL, '$sy', '$sccode', '$date', '$ttt', '$type', '$txt2', '$iii', '$eee', '$amount', 'System-Auto', '$cur', 1 );";
                               // echo $jax;
        $conn->query($jax);
    
    }
    	
	
	

	
		echo '<b>Entry Saved Successfully.</b>';
		
		
		
                            ?>