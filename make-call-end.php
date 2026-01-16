<?php
include 'inc.php';
?>
<style>
    body {
        color: var(--lighter);
        background: var(--lighter);
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
        history.back(-2);
    };






</script>