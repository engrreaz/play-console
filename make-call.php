<?php
$page_title = "Calling";
include 'inc.php';
// ধরুন $stid বা অন্য কোনো তথ্য আপনি আগের পেজ থেকে পাচ্ছেন
?>

<style>
    :root {
        --m3-surface: #FEF7FF;
        --m3-primary: #6750A4;
        --m3-on-primary: #FFFFFF;
        --m3-primary-container: #EADDFF;
        --m3-on-primary-container: #21005D;
    }

    body {
        margin: 0;
        padding: 0;
        background-color: var(--m3-surface);
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        display: flex;
        flex-direction: column;
        align-items: center;
        /* justify-content: center; */
        height: 100vh;
        overflow: hidden;
    }

    /* কল আইকন কন্টেইনার */
    .call-container {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 120px;
        height: 120px;
        background-color: var(--m3-primary-container);
        color: var(--m3-on-primary-container);
        border-radius: 28px; /* M3 Standard Large Shape */
        font-size: 3rem;
        margin-top:24px;
        margin-bottom: 24px;
        z-index: 2;
    }

    /* অ্যানিমেশন (Pulse Effect) */
    .pulse {
        position: absolute;
        width: 100%;
        height: 100%;
        background-color: var(--m3-primary);
        border-radius: 28px;
        opacity: 0.3;
        animation: pulse-animation 2s infinite;
        z-index: 1;
    }

    @keyframes pulse-animation {
        0% { transform: scale(1); opacity: 0.4; }
        100% { transform: scale(1.6); opacity: 0; }
    }

    /* টেক্সট স্টাইল */
    .status-text {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1C1B1F;
        margin: 0;
        letter-spacing: -0.5px;
    }

    .sub-text {
        font-size: 0.9rem;
        color: #49454F;
        margin-top: 8px;
    }

    /* লোডিং স্পিনার */
    .m3-spinner {
        width: 24px;
        height: 24px;
        border: 3px solid var(--m3-primary-container);
        border-top: 3px solid var(--m3-primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-top: 20px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<main>
    <div style="position: relative; display: flex; align-items: center; justify-content: center;">
        <div class="pulse"></div>
        <div class="call-container shadow-sm">
            <i class="bi bi-telephone-outbound-fill"></i>
        </div>
    </div>

    <div class="status-text" id="status_msg">Initiating Call</div>
    <div class="sub-text" id="sub_msg">Connecting to carrier...</div>
    
    <div class="m3-spinner"></div>
</main>



<script>
    const statusBox = document.getElementById("status_msg");
    const subBox = document.getElementById("sub_msg");

    // উইন্ডো ফোকাস হলে টেক্সট আপডেট
    window.onfocus = function () {
        statusBox.innerHTML = "Welcome Back";
        subBox.innerHTML = "Redirecting you to dashboard...";
    };

    function startCalling() {
        statusBox.innerHTML = "Calling Now";
        subBox.innerHTML = "Please complete the call on your dialer.";
        
        // ১ সেকেন্ড পর অ্যাকশন
        setTimeout(() => {
            // আগের পেজে ফিরে যাওয়া
            history.back();
            // নির্দিষ্ট গন্তব্যে পাঠানো
            window.location.href = 'make-call-end.php';
        }, 1200);
    }

    // অটোমেটিক কল ফাংশন ট্রিগার
    setTimeout(startCalling, 1000);
</script>