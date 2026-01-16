<?php

if($sub_1>0){
$sql22vf="SELECT * FROM subsetup where classname ='$cn' and sectionname = '$secname'  and sccode = '$sccode' and subject= '$sub_1'" ;
$result22vf = $conn->query($sql22vf);
if ($result22vf->num_rows > 0) 
{while($row22vf = $result22vf->fetch_assoc()) {
$fm = $row22vf["fullmarks"] ; $ss = $row22vf["subj"] ; $oo = $row22vf["obj"] ;  $pp = $row22vf["pra"] ; $cc = $row22vf["ca"] ;
}}

//******************************************************************************
if(($sub_1 == 101)||($sub_1 == 102)){
    $ben_sub = $ben_sub + $row22vr["sub_1_sub"] ;
    $ben_obj = $ben_obj + $row22vr["sub_1_obj"] ;
    $ben_pra = $ben_pra + $row22vr["sub_1_pra"] ;
    $ben_ca = $ben_ca + $row22vr["sub_1_ca"] ;
    $ben_total = $ben_sub + $ben_obj + $ben_pra + $ben_ca;
    $ben_fullmarks = $ben_fullmarks + $fm;
    $sss = $sss + $ss;
    $ooo = $ooo + $oo;
    $ppp = $ppp + $pp;
    $ccc = $ccc + $cc;
    //$totalgp = $totalgp - $sub_1_gp;
    $totalsubject = $totalsubject - 1;
    
}
if(($sub_1 == 107)||($sub_1 == 108)){
    $eng_sub = $eng_sub + $row22vr["sub_1_sub"] ;
    $eng_obj = $eng_obj + $row22vr["sub_1_obj"] ;
    $eng_ca = $eng_ca + $row22vr["sub_1_ca"] ;
    $eng_total = $eng_sub + $eng_obj + $eng_pra + $eng_ca;
    $eng_fullmarks = $eng_fullmarks + $fm;
    $ssss = $ssss + $ss;
    $oooo = $oooo + $oo;
    $cccc = $cccc + $cc;
    $totalgp = $totalgp - $sub_1_gp;
    $totalsubject = $totalsubject - 1;
}
//******************************************************************************
if($sub_1 == $fourth){
    $totalsubject = $totalsubject - 1;
            if ($sub_1_gp == 0) {$tfail = $tfail -1; $sub_1_gp = 0;}
    else    if ($sub_1_gp <=2)  {$tfail = $tfail + 0; $sub_1_gp = 0;}
    else    {$tfail = $tfail + 0; $sub_1_gp = $sub_1_gp - 2;}
}

$totalfullmarks = $totalfullmarks + $fm;
$totalmarks = $totalmarks + $sub_1_total;
$totalgp = $totalgp + $sub_1_gp;
$totalsubject = $totalsubject +1;
if($row22vr["sub_1_gp"]==0){$tfail = $tfail +1-1;} else {$tfail = $tfail + 0;}





}
else{
$totalfullmarks = $totalfullmarks + 0;
$totalmarks = $totalmarks + 0;
$totalgp = $totalgp + 0;
$totalsubject = $totalsubject +0;
$tfail = $tfail + 0;
}


//echo $ben_total. '***' . $eng_total . '<br>';

if($sub_2>0){
$sql22vf="SELECT * FROM subsetup where classname ='$cn' and sectionname = '$secname'  and sccode = '$sccode' and subject= '$sub_2'" ;
$result22vf = $conn->query($sql22vf);
if ($result22vf->num_rows > 0) 
{while($row22vf = $result22vf->fetch_assoc()) {
$fm = $row22vf["fullmarks"] ;$ss = $row22vf["subj"] ; $oo = $row22vf["obj"] ; $pp = $row22vf["pra"] ; $cc = $row22vf["ca"] ;}}
//******************************************************************************
if(($sub_2 == 101)||($sub_2 == 102)){
    $ben_sub = $ben_sub + $row22vr["sub_2_sub"] ;
    $ben_obj = $ben_obj + $row22vr["sub_2_obj"] ;
    $ben_pra = $ben_pra + $row22vr["sub_2_pra"] ;
    $ben_ca = $ben_ca + $row22vr["sub_2_ca"] ;
    $ben_total = $ben_sub + $ben_obj + $ben_pra + $ben_ca;
    $ben_fullmarks = $ben_fullmarks + $fm;
    $sss = $sss + $ss;
    $ooo = $ooo + $oo;
    $ppp = $ppp + $pp;
    $ccc = $ccc + $cc;
    $totalgp = $totalgp - $sub_2_gp;
    $totalsubject = $totalsubject - 1;
}
if(($sub_2 == 107)||($sub_2 == 108)){
    $eng_sub = $eng_sub + $row22vr["sub_2_sub"] ;
    $eng_obj = $eng_obj + $row22vr["sub_2_obj"] ;
    $eng_pra = $eng_pra + $row22vr["sub_2_pra"] ;
    $eng_ca = $eng_ca + $row22vr["sub_2_ca"] ;
    $eng_total = $eng_sub + $eng_obj + $eng_pra + $eng_ca;
    $eng_fullmarks = $eng_fullmarks + $fm;
    $ssss = $ssss + $ss;
    $oooo = $oooo + $oo;
    $pppp = $pppp + $pp;
    $cccc = $cccc + $cc;
    //$totalgp = $totalgp - $sub_2_gp;
    $totalsubject = $totalsubject - 1;

}
//******************************************************************************
if($sub_2 == $fourth){
    $totalsubject = $totalsubject - 1;
            if ($sub_2_gp == 0) {$tfail = $tfail -1; $sub_2_gp = 0;}
    else    if ($sub_2_gp <=2)  {$tfail = $tfail + 0; $sub_2_gp = 0;}
    else    {$tfail = $tfail + 0; $sub_2_gp = $sub_2_gp - 2;}
}

$totalfullmarks = $totalfullmarks + $fm;
$totalmarks = $totalmarks + $sub_2_total;
$totalgp = $totalgp + $sub_2_gp;
$totalsubject = $totalsubject +1;
if($row22vr["sub_2_gp"]==0){$tfail = $tfail +1-1;} else {$tfail = $tfail + 0;}
}
else{
$totalfullmarks = $totalfullmarks + 0;
$totalmarks = $totalmarks + 0;
$totalgp = $totalgp + 0;
$totalsubject = $totalsubject +0;
$tfail = $tfail + 0;
}

//echo $ben_total. '***' . $eng_total . '<br>';


if($sub_3>0){
$sql22vf="SELECT * FROM subsetup where classname ='$cn' and sectionname = '$secname'  and sccode = '$sccode' and subject= '$sub_3'" ;
$result22vf = $conn->query($sql22vf);
if ($result22vf->num_rows > 0) 
{while($row22vf = $result22vf->fetch_assoc()) {
$fm = $row22vf["fullmarks"] ;$ss = $row22vf["subj"] ; $oo = $row22vf["obj"] ; $cc = $row22vf["ca"] ;}}
//******************************************************************************


//******************************************************************************
if($sub_3 == $fourth){
    $totalsubject = $totalsubject - 1;
            if ($sub_3_gp == 0) {$tfail = $tfail -1; $sub_3_gp = 0;}
    else    if ($sub_3_gp <=2)  {$tfail = $tfail + 0; $sub_3_gp = 0;}
    else    {$tfail = $tfail + 0; $sub_3_gp = $sub_3_gp - 2;}
}

$totalfullmarks = $totalfullmarks + $fm;
$totalmarks = $totalmarks + $sub_3_total;
$totalgp = $totalgp + $sub_3_gp;
$totalsubject = $totalsubject +1;
if($row22vr["sub_3_gp"]==0){$tfail = $tfail +1;} else {$tfail = $tfail + 0;}
}
else{
$totalfullmarks = $totalfullmarks + 0;
$totalmarks = $totalmarks + 0;
$totalgp = $totalgp + 0;
$totalsubject = $totalsubject +0;
$tfail = $tfail + 0;
}

//echo $ben_total. '***' . $eng_total . '<br>';


if($sub_4>0){
$sql22vf="SELECT * FROM subsetup where classname ='$cn' and sectionname = '$secname'  and sccode = '$sccode' and subject= '$sub_4'" ;
$result22vf = $conn->query($sql22vf);
if ($result22vf->num_rows > 0) 
{while($row22vf = $result22vf->fetch_assoc()) {
$fm = $row22vf["fullmarks"] ;$ss = $row22vf["subj"] ; $oo = $row22vf["obj"] ; $cc = $row22vf["ca"] ;}}
//******************************************************************************

//******************************************************************************
if($sub_4 == $fourth){
    $totalsubject = $totalsubject - 1;
            if ($sub_4_gp == 0) {$tfail = $tfail -1; $sub_4_gp = 0;}
    else    if ($sub_4_gp <=2)  {$tfail = $tfail + 0; $sub_4_gp = 0;}
    else    {$tfail = $tfail + 0; $sub_4_gp = $sub_4_gp - 2;}
}

$totalfullmarks = $totalfullmarks + $fm;
$totalmarks = $totalmarks + $sub_4_total;
$totalgp = $totalgp + $sub_4_gp;
$totalsubject = $totalsubject +1;
if($row22vr["sub_4_gp"]==0){$tfail = $tfail +1;} else {$tfail = $tfail + 0;}
}
else{
$totalfullmarks = $totalfullmarks + 0;
$totalmarks = $totalmarks + 0;
$totalgp = $totalgp + 0;
$totalsubject = $totalsubject +0;
$tfail = $tfail + 0;
}


//echo $ben_total. '***' . $eng_total . '<br>';


if($sub_5>0){
$sql22vf="SELECT * FROM subsetup where classname ='$cn' and sectionname = '$secname'  and sccode = '$sccode' and subject= '$sub_5'" ;
$result22vf = $conn->query($sql22vf);
if ($result22vf->num_rows > 0) 
{while($row22vf = $result22vf->fetch_assoc()) {
$fm = $row22vf["fullmarks"] ;$ss = $row22vf["subj"] ; $oo = $row22vf["obj"] ; $cc = $row22vf["ca"] ;}}
//******************************************************************************
if(($sub_5 == 101)||($sub_5 == 102)){
    $ben_sub = $ben_sub + $row22vr["sub_5_sub"] ;
    $ben_obj = $ben_obj + $row22vr["sub_5_obj"] ;
    $ben_ca = $ben_ca + $row22vr["sub_5_ca"] ;
    $ben_total = $ben_sub + $ben_obj + $ben_pra + $ben_ca;
    $ben_fullmarks = $ben_fullmarks + $fm;
    $sss = $sss + $ss;
    $ooo = $ooo + $oo;
    $ccc = $ccc + $cc;
    $totalgp = $totalgp - $sub_5_gp;
    $totalsubject = $totalsubject - 1;
}
if(($sub_5 == 107)||($sub_5 == 108)){
    $eng_sub = $eng_sub + $row22vr["sub_5_sub"] ;
    $eng_obj = $eng_obj + $row22vr["sub_5_obj"] ;
    $eng_ca = $eng_ca + $row22vr["sub_5_ca"] ;
    $eng_total = $eng_sub + $eng_obj + $eng_pra + $eng_ca;
    $eng_fullmarks = $eng_fullmarks + $fm;
    $ssss = $ssss + $ss;
    $oooo = $oooo + $oo;
    $cccc = $cccc + $cc;
    $totalgp = $totalgp - $sub_5_gp;
    $totalsubject = $totalsubject - 1;
}
//******************************************************************************
if($sub_5 == $fourth){
    $totalsubject = $totalsubject - 1;
            if ($sub_5_gp == 0) {$tfail = $tfail -1; $sub_5_gp = 0;}
    else    if ($sub_5_gp <=2)  {$tfail = $tfail + 0; $sub_5_gp = 0;}
    else    {$tfail = $tfail + 0; $sub_5_gp = $sub_5_gp - 2;}
}

$totalfullmarks = $totalfullmarks + $fm;
$totalmarks = $totalmarks + $sub_5_total;
$totalgp = $totalgp + $sub_5_gp;
$totalsubject = $totalsubject +1;
if($row22vr["sub_5_gp"]==0){$tfail = $tfail +1;} else {$tfail = $tfail + 0;}
}
else{
$totalfullmarks = $totalfullmarks + 0;
$totalmarks = $totalmarks + 0;
$totalgp = $totalgp + 0;
$totalsubject = $totalsubject +0;
$tfail = $tfail + 0;
}
if($sub_6>0){
$sql22vf="SELECT * FROM subsetup where classname ='$cn' and sectionname = '$secname'  and sccode = '$sccode' and subject= '$sub_6'" ;
$result22vf = $conn->query($sql22vf);
if ($result22vf->num_rows > 0) 
{while($row22vf = $result22vf->fetch_assoc()) {
$fm = $row22vf["fullmarks"] ;$ss = $row22vf["subj"] ; $oo = $row22vf["obj"] ; $cc = $row22vf["ca"] ;}}
//******************************************************************************
if(($sub_6 == 101)||($sub_6 == 102)){
    $ben_sub = $ben_sub + $row22vr["sub_6_sub"] ;
    $ben_obj = $ben_obj + $row22vr["sub_6_obj"] ;
    $ben_ca = $ben_ca + $row22vr["sub_6_ca"] ;
    $ben_total = $ben_sub + $ben_obj + $ben_pra + $ben_ca;
    $ben_fullmarks = $ben_fullmarks + $fm;
    $sss = $sss + $ss;
    $ooo = $ooo + $oo;
    $ccc = $ccc + $cc;
    $totalgp = $totalgp - $sub_6_gp;
    $totalsubject = $totalsubject - 1;
}
if(($sub_6 == 107)||($sub_6 == 108)){
    $eng_sub = $eng_sub + $row22vr["sub_6_sub"] ;
    $eng_obj = $eng_obj + $row22vr["sub_6_obj"] ;
    $eng_ca = $eng_ca + $row22vr["sub_6_ca"] ;
    $eng_total = $eng_sub + $eng_obj + $eng_pra + $eng_ca;
    $eng_fullmarks = $eng_fullmarks + $fm;
    $ssss = $ssss + $ss;
    $oooo = $oooo + $oo;
    $cccc = $cccc + $cc;
    $totalgp = $totalgp - $sub_6_gp;
    $totalsubject = $totalsubject - 1;
}
//******************************************************************************
if($sub_6 == $fourth){
    $totalsubject = $totalsubject - 1;
            if ($sub_6_gp == 0) {$tfail = $tfail -1; $sub_6_gp = 0;}
    else    if ($sub_6_gp <=2)  {$tfail = $tfail + 0; $sub_6_gp = 0;}
    else    {$tfail = $tfail + 0; $sub_6_gp = $sub_6_gp - 2;}
}

$totalfullmarks = $totalfullmarks + $fm;
$totalmarks = $totalmarks + $sub_6_total;
$totalgp = $totalgp + $sub_6_gp;
$totalsubject = $totalsubject +1;
if($row22vr["sub_6_gp"]==0){$tfail = $tfail +1;} else {$tfail = $tfail + 0;}
}
else{
$totalfullmarks = $totalfullmarks + 0;
$totalmarks = $totalmarks + 0;
$totalgp = $totalgp + 0;
$totalsubject = $totalsubject +0;
$tfail = $tfail + 0;
}

//echo $rollno . '***' . $tfail . '/';

if($sub_7>0){
$sql22vf="SELECT * FROM subsetup where classname ='$cn' and sectionname = '$secname'  and sccode = '$sccode' and subject= '$sub_7'" ;
$result22vf = $conn->query($sql22vf);
if ($result22vf->num_rows > 0) 
{while($row22vf = $result22vf->fetch_assoc()) {
$fm = $row22vf["fullmarks"] ;$ss = $row22vf["subj"] ; $oo = $row22vf["obj"] ; $cc = $row22vf["ca"] ;}}
//******************************************************************************
if(($sub_7 == 101)||($sub_7 == 102)){
    $ben_sub = $ben_sub + $row22vr["sub_7_sub"] ;
    $ben_obj = $ben_obj + $row22vr["sub_7_obj"] ;
    $ben_ca = $ben_ca + $row22vr["sub_7_ca"] ;
    $ben_total = $ben_sub + $ben_obj + $ben_pra + $ben_ca;
    $ben_fullmarks = $ben_fullmarks + $fm;
    $sss = $sss + $ss;
    $ooo = $ooo + $oo;
    $ccc = $ccc + $cc;
    $totalgp = $totalgp - $sub_7_gp;
    $totalsubject = $totalsubject - 1;
}
if(($sub_7 == 107)||($sub_7 == 108)){
    $eng_sub = $eng_sub + $row22vr["sub_7_sub"] ;
    $eng_obj = $eng_obj + $row22vr["sub_7_obj"] ;
    $eng_ca = $eng_ca + $row22vr["sub_7_ca"] ;
    $eng_total = $eng_sub + $eng_obj + $eng_pra + $eng_ca;
    $eng_fullmarks = $eng_fullmarks + $fm;
    $ssss = $ssss + $ss;
    $oooo = $oooo + $oo;
    $cccc = $cccc + $cc;
    $totalgp = $totalgp - $sub_7_gp;
    $totalsubject = $totalsubject - 1;
}
//******************************************************************************
if($sub_7 == $fourth || $sub_7 == 126){
    $totalsubject = $totalsubject - 1;
            if ($sub_7_gp == 0) {$tfail = $tfail - 1; $sub_7_gp = 0;}
    else    if ($sub_7_gp <=2)  {$tfail = $tfail + 0; $sub_7_gp = 0;}
    else    {$tfail = $tfail + 0; $sub_7_gp = $sub_7_gp - 2;}
}
//echo '(' . $tfail . ')';

$totalfullmarks = $totalfullmarks + $fm;
$totalmarks = $totalmarks + $sub_7_total;
$totalgp = $totalgp + $sub_7_gp;
$totalsubject = $totalsubject +1;
if($row22vr["sub_7_gp"]==0){$tfail = $tfail +1;} else {$tfail = $tfail + 0;}//********************************************************************************************************************************************************************
}
else{
$totalfullmarks = $totalfullmarks + 0;
$totalmarks = $totalmarks + 0;
$totalgp = $totalgp + 0;
$totalsubject = $totalsubject +0;
$tfail = $tfail + 0;
}

//echo $tfail ."<br>";




if($sub_8>0){
$sql22vf="SELECT * FROM subsetup where classname ='$cn' and sectionname = '$secname'  and sccode = '$sccode' and subject= '$sub_8'" ;
$result22vf = $conn->query($sql22vf);
if ($result22vf->num_rows > 0) 
{while($row22vf = $result22vf->fetch_assoc()) {
$fm = $row22vf["fullmarks"] ;$ss = $row22vf["subj"] ; $oo = $row22vf["obj"] ; $cc = $row22vf["ca"] ;}}
//******************************************************************************
if(($sub_8 == 101)||($sub_8 == 102)){
    $ben_sub = $ben_sub + $row22vr["sub_8_sub"] ;
    $ben_obj = $ben_obj + $row22vr["sub_8_obj"] ;
    $ben_ca = $ben_ca + $row22vr["sub_8_ca"] ;
    $ben_total = $ben_sub + $ben_obj + $ben_pra + $ben_ca;
    $ben_fullmarks = $ben_fullmarks + $fm;
    $sss = $sss + $ss;
    $ooo = $ooo + $oo;
    $ccc = $ccc + $cc;
    $totalgp = $totalgp - $sub_8_gp;
    $totalsubject = $totalsubject - 1;
}
if(($sub_8 == 107)||($sub_8 == 108)){
    $eng_sub = $eng_sub + $row22vr["sub_8_sub"] ;
    $eng_obj = $eng_obj + $row22vr["sub_8_obj"] ;
    $eng_ca = $eng_ca + $row22vr["sub_8_ca"] ;
    $eng_total = $eng_sub + $eng_obj + $eng_pra + $eng_ca;
    $eng_fullmarks = $eng_fullmarks + $fm;
    $ssss = $ssss + $ss;
    $oooo = $oooo + $oo;
    $cccc = $cccc + $cc;
    $totalgp = $totalgp - $sub_8_gp;
    $totalsubject = $totalsubject - 1;
}
//******************************************************************************
if($sub_8 == $fourth || $sub_8 == 134){
    $totalsubject = $totalsubject - 1;
            if ($sub_8_gp == 0) {$tfail = $tfail -1; $sub_8_gp = 0;}
    else    if ($sub_8_gp <=2)  {$tfail = $tfail + 0; $sub_8_gp = 0;}
    else    {$tfail = $tfail + 0; $sub_8_gp = $sub_8_gp - 2;}
}

$totalfullmarks = $totalfullmarks + $fm;
$totalmarks = $totalmarks + $sub_8_total;
$totalgp = $totalgp + $sub_8_gp;
$totalsubject = $totalsubject +1;
if($row22vr["sub_8_gp"]==0){$tfail = $tfail +1;} else {$tfail = $tfail + 0;}
}
else{
$totalfullmarks = $totalfullmarks + 0;
$totalmarks = $totalmarks + 0;
$totalgp = $totalgp + 0;
$totalsubject = $totalsubject +0;
$tfail = $tfail + 0;
}
if($sub_9>0){
$sql22vf="SELECT * FROM subsetup where classname ='$cn' and sectionname = '$secname'  and sccode = '$sccode' and subject= '$sub_9'" ;
$result22vf = $conn->query($sql22vf);
if ($result22vf->num_rows > 0) 
{while($row22vf = $result22vf->fetch_assoc()) {
$fm = $row22vf["fullmarks"] ;$ss = $row22vf["subj"] ; $oo = $row22vf["obj"] ; $cc = $row22vf["ca"] ;}}
//******************************************************************************
if(($sub_9 == 101)||($sub_9 == 102)){
    $ben_sub = $ben_sub + $row22vr["sub_9_sub"] ;
    $ben_obj = $ben_obj + $row22vr["sub_9_obj"] ;
    $ben_ca = $ben_ca + $row22vr["sub_9_ca"] ;
    $ben_total = $ben_sub + $ben_obj + $ben_pra + $ben_ca;
    $ben_fullmarks = $ben_fullmarks + $fm;
    $sss = $sss + $ss;
    $ooo = $ooo + $oo;
    $ccc = $ccc + $cc;
    $totalgp = $totalgp - $sub_9_gp;
    $totalsubject = $totalsubject - 1;
}
if(($sub_9 == 107)||($sub_9 == 108)){
    $eng_sub = $eng_sub + $row22vr["sub_9_sub"] ;
    $eng_obj = $eng_obj + $row22vr["sub_9_obj"] ;
    $eng_ca = $eng_ca + $row22vr["sub_9_ca"] ;
    $eng_total = $eng_sub + $eng_obj + $eng_pra + $eng_ca;
    $eng_fullmarks = $eng_fullmarks + $fm;
    $ssss = $ssss + $ss;
    $oooo = $oooo + $oo;
    $cccc = $cccc + $cc;
    $totalgp = $totalgp - $sub_9_gp;
    $totalsubject = $totalsubject - 1;
}
//******************************************************************************
if($sub_9 == $fourth){
    $totalsubject = $totalsubject - 1;
            if ($sub_9_gp == 0) {$tfail = $tfail -1; $sub_9_gp = 0;}
    else    if ($sub_9_gp <=2)  {$tfail = $tfail + 0; $sub_9_gp = 0;}
    else    {$tfail = $tfail + 0; $sub_9_gp = $sub_9_gp - 2;}
}

$totalfullmarks = $totalfullmarks + $fm;
$totalmarks = $totalmarks + $sub_9_total;
$totalgp = $totalgp + $sub_9_gp;
$totalsubject = $totalsubject +1;
if($row22vr["sub_9_gp"]==0){$tfail = $tfail +1;} else {$tfail = $tfail + 0;}
}
else{
$totalfullmarks = $totalfullmarks + 0;
$totalmarks = $totalmarks + 0;
$totalgp = $totalgp + 0;
$totalsubject = $totalsubject +0;
$tfail = $tfail + 0;
}
if($sub_10>0){
$sql22vf="SELECT * FROM subsetup where classname ='$cn' and sectionname = '$secname'  and sccode = '$sccode' and subject= '$sub_10'" ;
$result22vf = $conn->query($sql22vf);
if ($result22vf->num_rows > 0) 
{while($row22vf = $result22vf->fetch_assoc()) {
$fm = $row22vf["fullmarks"] ;$ss = $row22vf["subj"] ; $oo = $row22vf["obj"] ; $cc = $row22vf["ca"] ;}}
//******************************************************************************
if(($sub_10 == 101)||($sub_10 == 102)){
    $ben_sub = $ben_sub + $row22vr["sub_10_sub"] ;
    $ben_obj = $ben_obj + $row22vr["sub_10_obj"] ;
    $ben_ca = $ben_ca + $row22vr["sub_10_ca"] ;
    $ben_total = $ben_sub + $ben_obj + $ben_pra + $ben_ca;
    $ben_fullmarks = $ben_fullmarks + $fm;
    $sss = $sss + $ss;
    $ooo = $ooo + $oo;
    $ccc = $ccc + $cc;
    $totalgp = $totalgp - $sub_10_gp;
    $totalsubject = $totalsubject - 1;
}
if(($sub_10 == 107)||($sub_10 == 108)){
    $eng_sub = $eng_sub + $row22vr["sub_10_sub"] ;
    $eng_obj = $eng_obj + $row22vr["sub_10_obj"] ;
    $eng_ca = $eng_ca + $row22vr["sub_10_ca"] ;
    $eng_total = $eng_sub + $eng_obj + $eng_pra + $eng_ca;
    $eng_fullmarks = $eng_fullmarks + $fm;
    $ssss = $ssss + $ss;
    $oooo = $oooo + $oo;
    $cccc = $cccc + $cc;
    $totalgp = $totalgp - $sub_10_gp;
    $totalsubject = $totalsubject - 1;
}
//******************************************************************************
if($sub_10 == $fourth){
    $totalsubject = $totalsubject - 1;
            if ($sub_10_gp == 0) {$tfail = $tfail -1; $sub_10_gp = 0;}
    else    if ($sub_10_gp <=2)  {$tfail = $tfail + 0; $sub_10_gp = 0;}
    else    {$tfail = $tfail + 0; $sub_10_gp = $sub_10_gp - 2;}
}

$totalfullmarks = $totalfullmarks + $fm;
$totalmarks = $totalmarks + $sub_10_total;
$totalgp = $totalgp + $sub_10_gp;
$totalsubject = $totalsubject +1;
if($row22vr["sub_10_gp"]==0){$tfail = $tfail +1;} else {$tfail = $tfail + 0;}
}
else{
$totalfullmarks = $totalfullmarks + 0;
$totalmarks = $totalmarks + 0;
$totalgp = $totalgp + 0;
$totalsubject = $totalsubject +0;
$tfail = $tfail + 0;
}
if($sub_11>0){
$sql22vf="SELECT * FROM subsetup where classname ='$cn' and sectionname = '$secname'  and sccode = '$sccode' and subject= '$sub_11'" ;
$result22vf = $conn->query($sql22vf);
if ($result22vf->num_rows > 0) 
{while($row22vf = $result22vf->fetch_assoc()) {
$fm = $row22vf["fullmarks"] ;$ss = $row22vf["subj"] ; $oo = $row22vf["obj"] ; $cc = $row22vf["ca"] ;}}
//******************************************************************************
if(($sub_11 == 101)||($sub_11 == 102)){
    $ben_sub = $ben_sub + $row22vr["sub_11_sub"] ;
    $ben_obj = $ben_obj + $row22vr["sub_11_obj"] ;
    $ben_ca = $ben_ca + $row22vr["sub_11_ca"] ;
    $ben_total = $ben_sub + $ben_obj + $ben_pra + $ben_ca;
    $ben_fullmarks = $ben_fullmarks + $fm;
    $sss = $sss + $ss;
    $ooo = $ooo + $oo;
    $ccc = $ccc + $cc;
    $totalgp = $totalgp - $sub_11_gp;
    $totalsubject = $totalsubject - 1;
}
if(($sub_11 == 107)||($sub_11 == 108)){
    $eng_sub = $eng_sub + $row22vr["sub_11_sub"] ;
    $eng_obj = $eng_obj + $row22vr["sub_11_obj"] ;
    $eng_ca = $eng_ca + $row22vr["sub_11_ca"] ;
    $eng_total = $eng_sub + $eng_obj + $eng_pra + $eng_ca;
    $eng_fullmarks = $eng_fullmarks + $fm;
    $ssss = $ssss + $ss;
    $oooo = $oooo + $oo;
    $cccc = $cccc + $cc;
    $totalgp = $totalgp - $sub_11_gp;
    $totalsubject = $totalsubject - 1;
}
//******************************************************************************
if($sub_11 == $fourth){
    $totalsubject = $totalsubject - 1;
            if ($sub_11_gp == 0) {$tfail = $tfail -1; $sub_11_gp = 0;}
    else    if ($sub_11_gp <=2)  {$tfail = $tfail + 0; $sub_11_gp = 0;}
    else    {$tfail = $tfail + 0; $sub_11_gp = $sub_11_gp - 2;}
}

$totalfullmarks = $totalfullmarks + $fm;
$totalmarks = $totalmarks + $sub_11_total;
$totalgp = $totalgp + $sub_11_gp;
$totalsubject = $totalsubject +1;
if($row22vr["sub_11_gp"]==0){$tfail = $tfail +1;} else {$tfail = $tfail + 0;}
}
else{
$totalfullmarks = $totalfullmarks + 0;
$totalmarks = $totalmarks + 0;
$totalgp = $totalgp + 0;
$totalsubject = $totalsubject +0;
$tfail = $tfail + 0;
}
if($sub_12>0){
$sql22vf="SELECT * FROM subsetup where classname ='$cn' and sectionname = '$secname'  and sccode = '$sccode' and subject= '$sub_12'" ;
$result22vf = $conn->query($sql22vf);
if ($result22vf->num_rows > 0) 
{while($row22vf = $result22vf->fetch_assoc()) {
$fm = $row22vf["fullmarks"] ;$ss = $row22vf["subj"] ; $oo = $row22vf["obj"] ; $cc = $row22vf["ca"] ;}}
//******************************************************************************
if(($sub_12 == 101)||($sub_12 == 102)){
    $ben_sub = $ben_sub + $row22vr["sub_12_sub"] ;
    $ben_obj = $ben_obj + $row22vr["sub_12_obj"] ;
    $ben_ca = $ben_ca + $row22vr["sub_12_ca"] ;
    $ben_total = $ben_sub + $ben_obj + $ben_pra + $ben_ca;
    $ben_fullmarks = $ben_fullmarks + $fm;
    $sss = $sss + $ss;
    $ooo = $ooo + $oo;
    $ccc = $ccc + $cc;
    $totalgp = $totalgp - $sub_12_gp;
    $totalsubject = $totalsubject - 1;
}
if(($sub_12 == 107)||($sub_12 == 108)){
    $eng_sub = $eng_sub + $row22vr["sub_12_sub"] ;
    $eng_obj = $eng_obj + $row22vr["sub_12_obj"] ;
    $eng_ca = $eng_ca + $row22vr["sub_12_ca"] ;
    $eng_total = $eng_sub + $eng_obj + $eng_pra + $eng_ca;
    $eng_fullmarks = $eng_fullmarks + $fm;
    $ssss = $ssss + $ss;
    $oooo = $oooo + $oo;
    $cccc = $cccc + $cc;
    $totalgp = $totalgp - $sub_12_gp;
    $totalsubject = $totalsubject - 1;
}
//******************************************************************************
if($sub_12 == $fourth){
    $totalsubject = $totalsubject - 1;
            if ($sub_12_gp == 0) {$tfail = $tfail -1; $sub_12_gp = 0;}
    else    if ($sub_12_gp <=2)  {$tfail = $tfail + 0; $sub_12_gp = 0;}
    else    {$tfail = $tfail + 0; $sub_12_gp = $sub_12_gp - 2;}
}

$totalfullmarks = $totalfullmarks + $fm;
$totalmarks = $totalmarks + $sub_12_total;
$totalgp = $totalgp + $sub_12_gp;
$totalsubject = $totalsubject +1;
if($row22vr["sub_12_gp"]==0){$tfail = $tfail +1;} else {$tfail = $tfail + 0;}
}
else{
$totalfullmarks = $totalfullmarks + 0;
$totalmarks = $totalmarks + 0;
$totalgp = $totalgp + 0;
$totalsubject = $totalsubject +0;
$tfail = $tfail + 0;
}
if($sub_13>0){
$sql22vf="SELECT * FROM subsetup where classname ='$cn' and sectionname = '$secname'  and sccode = '$sccode' and subject= '$sub_13'" ;
$result22vf = $conn->query($sql22vf);
if ($result22vf->num_rows > 0) 
{while($row22vf = $result22vf->fetch_assoc()) {
$fm = $row22vf["fullmarks"] ;$ss = $row22vf["subj"] ; $oo = $row22vf["obj"] ; $cc = $row22vf["ca"] ;}}
//******************************************************************************
if(($sub_13 == 101)||($sub_13 == 102)){
    $ben_sub = $ben_sub + $row22vr["sub_13_sub"] ;
    $ben_obj = $ben_obj + $row22vr["sub_13_obj"] ;
    $ben_ca = $ben_ca + $row22vr["sub_13_ca"] ;
    $ben_total = $ben_sub + $ben_obj + $ben_pra + $ben_ca;
    $ben_fullmarks = $ben_fullmarks + $fm;
    $sss = $sss + $ss;
    $ooo = $ooo + $oo;
    $ccc = $ccc + $cc;
    $totalgp = $totalgp - $sub_13_gp;
    $totalsubject = $totalsubject - 1;
}
if(($sub_13 == 107)||($sub_13 == 108)){
    $eng_sub = $eng_sub + $row22vr["sub_13_sub"] ;
    $eng_obj = $eng_obj + $row22vr["sub_13_obj"] ;
    $eng_ca = $eng_ca + $row22vr["sub_13_ca"] ;
    $eng_total = $eng_sub + $eng_obj + $eng_pra + $eng_ca;
    $eng_fullmarks = $eng_fullmarks + $fm;
    $ssss = $ssss + $ss;
    $oooo = $oooo + $oo;
    $cccc = $cccc + $cc;
    $totalgp = $totalgp - $sub_13_gp;
    $totalsubject = $totalsubject - 1;
}
//******************************************************************************
if($sub_13 == $fourth || $sub_13 == 151){
    $totalsubject = $totalsubject - 1;
     
            if ($sub_13_gp == 0) {$tfail = $tfail -1; $sub_13_gp = 0;}
    else    if ($sub_13_gp <=2)  {$tfail = $tfail + 0; $sub_13_gp = 0;}
    else    {$tfail = $tfail + 0; $sub_13_gp = $sub_13_gp - 2;}
}
$totalfullmarks = $totalfullmarks + $fm;
$totalmarks = $totalmarks + $sub_13_total;
$totalgp = $totalgp + $sub_13_gp;
$totalsubject = $totalsubject +1;
if($row22vr["sub_13_gp"]==0){$tfail = $tfail +1;} else {$tfail = $tfail + 0;}
}
else{
$totalfullmarks = $totalfullmarks + 0;
$totalmarks = $totalmarks + 0;
$totalgp = $totalgp + 0;
$totalsubject = $totalsubject +0;
$tfail = $tfail + 0;
}
if($sub_14>0){
$sql22vf="SELECT * FROM subsetup where classname ='$cn' and sectionname = '$secname'  and sccode = '$sccode' and subject= '$sub_14'" ;
$result22vf = $conn->query($sql22vf);
if ($result22vf->num_rows > 0) 
{while($row22vf = $result22vf->fetch_assoc()) {
$fm = $row22vf["fullmarks"] ;$ss = $row22vf["subj"] ; $oo = $row22vf["obj"] ; $cc = $row22vf["ca"] ;}}
//******************************************************************************
if(($sub_14 == 101)||($sub_14 == 102)){
    $ben_sub = $ben_sub + $row22vr["sub_14_sub"] ;
    $ben_obj = $ben_obj + $row22vr["sub_14_obj"] ;
    $ben_ca = $ben_ca + $row22vr["sub_14_ca"] ;
    $ben_total = $ben_sub + $ben_obj + $ben_pra + $ben_ca;
    $ben_fullmarks = $ben_fullmarks + $fm;
    $sss = $sss + $ss;
    $ooo = $ooo + $oo;
    $ccc = $ccc + $cc;
    $totalgp = $totalgp - $sub_14_gp;
    $totalsubject = $totalsubject - 1;
}
if(($sub_14 == 107)||($sub_14 == 108)){
    $eng_sub = $eng_sub + $row22vr["sub_14_sub"] ;
    $eng_obj = $eng_obj + $row22vr["sub_14_obj"] ;
    $eng_ca = $eng_ca + $row22vr["sub_14_ca"] ;
    $eng_total = $eng_sub + $eng_obj + $eng_pra + $eng_ca;
    $eng_fullmarks = $eng_fullmarks + $fm;
    $ssss = $ssss + $ss;
    $oooo = $oooo + $oo;
    $cccc = $cccc + $cc;
    $totalgp = $totalgp - $sub_14_gp;
    $totalsubject = $totalsubject - 1;
}
//******************************************************************************
if($sub_14 == $fourth){
    $totalsubject = $totalsubject - 1;
            if ($sub_14_gp == 0) {$tfail = $tfail -1; $sub_14_gp = 0;}
    else    if ($sub_14_gp <=2)  {$tfail = $tfail + 0; $sub_14_gp = 0;}
    else    {$tfail = $tfail + 0; $sub_14_gp = $sub_14_gp - 2;}
}

$totalfullmarks = $totalfullmarks + $fm;
$totalmarks = $totalmarks + $sub_14_total;
$totalgp = $totalgp + $sub_14_gp;
$totalsubject = $totalsubject +1;
if($row22vr["sub_14_gp"]==0){$tfail = $tfail +1;} else {$tfail = $tfail + 0;}
}
else{
$totalfullmarks = $totalfullmarks + 0;
$totalmarks = $totalmarks + 0;
$totalgp = $totalgp + 0;
$totalsubject = $totalsubject +0;
$tfail = $tfail + 0;
}
if($sub_15>0){
$sql22vf="SELECT * FROM subsetup where classname ='$cn' and sectionname = '$secname'  and sccode = '$sccode' and subject= '$sub_15'" ;
$result22vf = $conn->query($sql22vf);
if ($result22vf->num_rows > 0) 
{while($row22vf = $result22vf->fetch_assoc()) {
$fm = $row22vf["fullmarks"] ;$ss = $row22vf["subj"] ; $oo = $row22vf["obj"] ; $cc = $row22vf["ca"] ;}}
//******************************************************************************
if(($sub_15 == 101)||($sub_15 == 102)){
    $ben_sub = $ben_sub + $row22vr["sub_15_sub"] ;
    $ben_obj = $ben_obj + $row22vr["sub_15_obj"] ;
    $ben_ca = $ben_ca + $row22vr["sub_15_ca"] ;
    $ben_total = $ben_sub + $ben_obj + $ben_pra + $ben_ca;
    $ben_fullmarks = $ben_fullmarks + $fm;
    $sss = $sss + $ss;
    $ooo = $ooo + $oo;
    $ccc = $ccc + $cc;
    $totalgp = $totalgp - $sub_15_gp;
    $totalsubject = $totalsubject - 1;
}
if(($sub_15 == 107)||($sub_15 == 108)){
    $eng_sub = $eng_sub + $row22vr["sub_15_sub"] ;
    $eng_obj = $eng_obj + $row22vr["sub_15_obj"] ;
    $eng_ca = $eng_ca + $row22vr["sub_15_ca"] ;
    $eng_total = $eng_sub + $eng_obj + $eng_pra + $eng_ca;
    $eng_fullmarks = $eng_fullmarks + $fm;
    $ssss = $ssss + $ss;
    $oooo = $oooo + $oo;
    $cccc = $cccc + $cc;
    $totalgp = $totalgp - $sub_15_gp;
    $totalsubject = $totalsubject - 1;
}

//******************************************************************************
if($sub_15 == $fourth){
    $totalsubject = $totalsubject - 1;
            if ($sub_15_gp == 0) {$tfail = $tfail -1; $sub_15_gp = 0;}
    else    if ($sub_15_gp <=2)  {$tfail = $tfail + 0; $sub_15_gp = 0;}
    else    {$tfail = $tfail + 0; $sub_15_gp = $sub_15_gp - 2;}
}

$totalfullmarks = $totalfullmarks + $fm;
$totalmarks = $totalmarks + $sub_15_total;
$totalgp = $totalgp + $sub_15_gp;
$totalsubject = $totalsubject +1;
if($row22vr["sub_15_gp"]==0){$tfail = $tfail +1;} else {$tfail = $tfail + 0;}
}
else{
$totalfullmarks = $totalfullmarks + 0;
$totalmarks = $totalmarks + 0;
$totalgp = $totalgp + 0;
$totalsubject = $totalsubject +0;
$tfail = $tfail + 0;
}



// if($sub_2 == 102){
//     if($sub_1_gp == 0){$tfail = $tfail -1;}
//     if($sub_2_gp == 0){$tfail = $tfail -1;}
//     if($row22vr["sub_3_sub"] + $row22vr["sub_4_sub"] >= 66) {$tfail = $tfail -1;}
// }

// if($sub_4 == 108){
//     if($sub_3_gp == 0){$tfail = $tfail -1;}
//     if($sub_4_gp == 0){$tfail = $tfail -1;}
    
    
// }

// if($sub_1 == 103){
//     if($sub_1_gp > 0){$tfail = $tfail -1;}
//     if($sub_2_gp > 0){$tfail = $tfail -1;}
// }

//   if($tfail <0) {$tfail = 0;}
// $tfail++;
// if($stid=='1056761830' || $stid=='1056761882'){$tfail = $tfail+1;}


$sql22vf="SELECT count(*) as fs FROM subsetup where classname ='$cn' and sectionname = '$secname'  and sccode = '$sccode' and (subject= 126 || subject= 134 || subject= 151)" ;
$result22vf = $conn->query($sql22vf);
if ($result22vf->num_rows > 0) 
{while($row22vf = $result22vf->fetch_assoc()) {
$fs = $row22vf["fs"] ;}}

$totalfullmarks = $totalfullmarks - ($fs-1) * 100;

//if($sccode==105676 && $cn=='Eight'){$totalsubject = 10;}
