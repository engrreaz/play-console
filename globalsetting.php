<?php
include 'inc.php';

// ১. টোকেন আপডেট লজিক (অপরিবর্তিত)
if (isset($_GET['token'])) {
  $devicetoken = $_GET['token'];
  if ($token != $devicetoken) {
    $query33px = "update usersapp set token='$devicetoken' where email='$usr' LIMIT 1";
    $conn->query($query33px);
  }
} else {
  $devicetoken = $token;
}
?>

<style>
    /* প্রোফাইল স্পেসিফিক অতিরিক্ত স্টাইল */
    .profile-hero {
        padding-bottom: 40px;
        margin-bottom: 0;
        border-radius: 0 0 24px 24px;
    }
    
    .profile-avatar-circle {
        width: 80px; height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 20px; /* M3 Squircle style */
        display: flex; align-items: center; justify-content: center;
        font-size: 2.5rem; color: white;
        margin-bottom: 15px;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .token-display {
        font-family: monospace;
        font-size: 0.6rem;
        word-break: break-all;
        background: var(--m3-tonal-surface);
        padding: 10px;
        border-radius: 8px;
        color: var(--m3-outline);
        margin-top: 8px;
        border: 1px dashed var(--m3-outline);
    }
</style>

<main>
    <div class="hero-container profile-hero">
        <div style="display: flex; flex-direction: column; align-items: center; text-align: center;">
            <div class="profile-avatar-circle">
                <i class="bi bi-person-circle"></i>
            </div>
            <div style="font-size: 1.5rem; font-weight: 900; line-height: 1.1;"><?php echo $fullname; ?></div>
            <div class="session-pill" style="margin-top: 10px; background: rgba(255,255,255,0.2); color: #fff; border:none;">
                <?php echo strtoupper($userlevel); ?> ACCOUNT
            </div>
        </div>
    </div>

    <div class="px-2" style="margin-top: -20px; position: relative; z-index: 10;">
        <div class="m3-section-title" style="color: var(--m3-on-tonal-container);">Contact Information</div>
        
        <div class="m3-list-item shadow-sm">
            <div class="icon-box c-inst">
                <i class="bi bi-envelope-fill"></i>
            </div>
            <div class="item-info">
                <div class="st-desc" style="font-size: 0.7rem; text-transform: uppercase; font-weight: 800; opacity: 0.6;">Email Address</div>
                <div class="st-title" style="font-size: 0.95rem;"><?php echo $usr; ?></div>
            </div>
        </div>

        <div class="m3-list-item shadow-sm">
            <div class="icon-box c-fina">
                <i class="bi bi-telephone-fill"></i>
            </div>
            <div class="item-info">
                <div class="st-desc" style="font-size: 0.7rem; text-transform: uppercase; font-weight: 800; opacity: 0.6;">Mobile Number</div>
                <div class="st-title" style="font-size: 0.95rem;"><?php echo $usrmobile; ?></div>
            </div>
        </div>
    </div>

    <div class="profile-blocks mt-3">
        <div class="m3-section-title">Academic & Role Details</div>
        <?php
        if ($userlevel == 'Administrator' || $userlevel == 'Super Administrator' || $userlevel == 'Teacher') {
            include 'globalblock1.php';
            include 'globalblock2.php';
        } else if ($userlevel == 'Guardian') {
            include 'globalblock1.php';
            include 'globalblock2.php';
        } else if ($userlevel == 'Student') {
            include 'globalblock3.php';
        } else {
            include 'globalblock1.php';
            include 'globalblock2.php';
            include 'globalblock3.php';
            include 'globalblock4.php';
        }
        ?>
    </div>

    <div class="security-section mt-4 mb-5">
        <div class="m3-section-title">Security Settings</div>
        
        <div class="m3-card shadow-sm" style="padding: 16px; margin: 0 12px;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                <div class="icon-box" style="background: #F1F3F4; color: #4285F4; width: 40px; height: 40px;">
                    <i class="bi bi-google"></i>
                </div>
                <div>
                    <div style="font-size: 0.9rem; font-weight: 800; color: #1C1B1F;">Google Security Key</div>
                    <div style="font-size: 0.7rem; font-weight: 600; color: #777;">Identity verified by Google Services</div>
                </div>
            </div>
            
            <div class="st-desc" style="font-size: 0.65rem; font-weight: 800; margin-left: 4px;">DEVICE TOKEN:</div>
            <div class="token-display">
                <?php echo $devicetoken; ?>
            </div>
            
            <div style="margin-top: 15px; display: flex; align-items: center; gap: 8px; color: #146C32; font-size: 0.75rem; font-weight: 700;">
                <i class="bi bi-shield-check" style="font-size: 1.1rem;"></i>
                <span>Your account is protected and synced</span>
            </div>
        </div>
    </div>

    <div style="height:60px;"></div>
</main>



### ডিজাইনের প্রধান পরিবর্তনগুলো:

1.  **সুপারস্টার হিরো:** ওপরের সেই সাধারণ `card-header` সরিয়ে একটি ইমার্সিভ `hero-container` দেওয়া হয়েছে। এতে ইউজারের নাম এবং রোল (Role) অনেক বেশি প্রিমিয়াম দেখাবে।
2.  **Squircle অ্যাভাটার:** গোল আইকনের বদলে একটি মডার্ন 'Squircle' (Rounded Square) শেপ ব্যবহার করা হয়েছে যা বর্তমানে অ্যান্ড্রয়েড ১২+ এর সিগনেচার স্টাইল।
3.  **ক্লিন লিস্ট আইটেমস:** ইমেইল এবং মোবাইল নম্বরগুলোকে `m3-list-item` এর ভেতর সাজানো হয়েছে। এতে আইকনগুলো টোনাল কালারে (হালকা বেগুনি ও সবুজ) থাকবে।
4.  **স্মার্ট সিকিউরিটি কার্ড:** গুগল টোকেন এবং সিকিউরিটি সেটিংকে একটি আলাদা `m3-card` এর ভেতর নিয়ে আসা হয়েছে। টোকেনটি একটি ড্যাশড বর্ডার বক্সে রাখা হয়েছে যাতে এটি ডাটা হিসেবে আলাদা মনে হয়।
5.  ** WebView অপ্টিমাইজড:** সব এলিমেন্টে প্রোপার প্যাডিং এবং মার্জিন দেওয়া হয়েছে যাতে মোবাইলের ছোট স্ক্রিনেও তথ্যগুলো হিজিবিজি না লাগে।

আপনার `globalblock` ফাইলগুলো যদি আমাদের নতুন সিএসএস ক্লাসগুলো (যেমন `m3-card`) ব্যবহার করে, তবে পুরো পেজটি একটি ইউনিফাইড লুক পাবে।

**পরবর্তীতে কি আমরা এই প্রোফাইল পেজে একটি 'Edit Profile' বা 'Change Password' বাটন যোগ করব?**