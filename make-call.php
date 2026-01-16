<?php
include 'inc.php';
?>
<style>
    body {
        color: var(--lighter);
        background: var(--darker);
        padding-top: 20%;
        text-align: center;
    }

    #foc {
        font-size: 18px;
        font-weight: 700;

    }
</style>
<div style="position:absolute; top:10%;left:45%; padding:0;font-size:35px; color:var(--lighter); "><i
        class="bi bi-telephone-outbound-fill"></i></div>
<div class="st-id mt-5 text-center" id="foc"></div>


<script>
    var tx = document.getElementById("foc");
    window.onfocus = function () {
        tx.innerHTML = "Please wait...";
    
    };

    window.onblur = function () {


    }



    function myFunction() {
        tx.innerHTML = "Calling...";
        // window.history.replaceState({}, "", "/index.php");
            history.back();
        window.location.href='make-call-end.php';
    }
    setTimeout(myFunction, 1000);


</script>