<?php
include 'inc.php';

if (isset($_GET['token'])) {
  $devicetoken = $_GET['token'];
  if ($token != $devicetoken) {
    $query33px = "update usersapp set token='$devicetoken' where  email='$usr' LIMIT 1";
    $conn->query($query33px);
  }
} else {
  $devicetoken = $token;
}



?>

<div class="containerx" style="width: 100%">
  <div class="card-header page-top-box">
    <div class="menu-icon"><i class="bi bi-person-circle"></i></div>
    <div class="menu-text">My Profile</i></div>
  </div>


  <style>
    .box {
      color: gray;
      font-weight: bold;
      text-align: left;
      padding: 7px 30px;
      border: 1px solid #ccc;
    }

    .box small {
      font-weight: 400;
      font-size: 10px;
      color: var(--normal);
      padding-left: 30px;
      font-style: italic;
    }

    .icon {
      padding-right: 5px;
    }

    td {
      text-align: center;
    }

    .das {
      font-weight: 400;
      font-size: 12px;
      color: var(--dark);
      padding-top: 8px;
    }

    .bbb {
      border: 1px solid var(--darker);
      border-radius: 5px;
      padding: 7px 2px;
      background: var(--light);
      font-weight: 600;
    }

    .right {
      float: right;
      margin-top: 2px;
    }

    .hidden {
      display: none;
    }
  </style>

  <div class="box">
    <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
      class="bi bi-person-fill" viewBox="0 0 16 16">
      <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
    </svg>
    <?php echo $fullname; ?>
    <div class="st-id ms-4 ps-1">Display Name</div>
  </div>

  <div class="box">
    <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
      class="bi bi-envelope-fill" viewBox="0 0 16 16">
      <path
        d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z" />
    </svg>
    <?php echo $usr; ?>
    <div  class="st-id ms-4 ps-1">Email Address</div>
  </div>

  <div class="box">
    <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
      class="bi bi-telephone-fill" viewBox="0 0 16 16">
      <path fill-rule="evenodd"
        d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z" />
    </svg>
    <?php echo $usrmobile; ?>
     <div  class="st-id ms-4 ps-1">Mobile Number</div>
  </div>



  <div style="margin-top:15px;text-align:center;">
    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-mortarboard-fill"
      viewBox="0 0 16 16">
      <path
        d="M8.211 2.047a.5.5 0 0 0-.422 0l-7.5 3.5a.5.5 0 0 0 .025.917l7.5 3a.5.5 0 0 0 .372 0L14 7.14V13a1 1 0 0 0-1 1v2h3v-2a1 1 0 0 0-1-1V6.739l.686-.275a.5.5 0 0 0 .025-.917l-7.5-3.5Z" />
      <path
        d="M4.176 9.032a.5.5 0 0 0-.656.327l-.5 1.7a.5.5 0 0 0 .294.605l4.5 1.8a.5.5 0 0 0 .372 0l4.5-1.8a.5.5 0 0 0 .294-.605l-.5-1.7a.5.5 0 0 0-.656-.327L8 10.466 4.176 9.032Z" />
    </svg>
  </div>


  <div style="text-align:center; margin-bottom:12px; font-weight:bold;"><?php echo $userlevel; ?> Profile</div>




  <?php

  if ($userlevel == 'Administrator' || $userlevel == 'Super Administrator' || $userlevel == 'Teacher') {
    $hidden = '';
    include 'globalblock1.php';
    include 'globalblock2.php';
  } else if ($userlevel == 'Guardian') {
    $hidden = 'hidden';
    include 'globalblock1.php';
    include 'globalblock2.php';
  } else if ($userlevel == 'Student') {
    $hidden = 'hidden';
    include 'globalblock3.php';
  } else {
    $hidden = '';
    include 'globalblock1.php';
    include 'globalblock2.php';
    include 'globalblock3.php';
    include 'globalblock4.php';
  }

  ?>








  <div style="margin-top:15px;text-align:center;">
    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-lock-fill"
      viewBox="0 0 16 16">
      <path
        d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z" />
    </svg>
  </div>


  <div style="text-align:center; margin-bottom:12px; font-weight:bold;">My Secutiry Setting</div>



  <div class="box">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-google"
      viewBox="0 0 16 16">
      <path
        d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z" />
    </svg>

    Security Key proved by <b>Google</b>

    <div style="overflow:scroll; font-size:10px; font-weight:400; margin-left:30px; color:Gainsboro;" disabled>
      <?php echo $devicetoken; ?>
    </div>
  </div>





  <div style="height:52px;"></div>







</div>





</div>