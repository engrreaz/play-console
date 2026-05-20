<?php
$page_title = "About Us";
include 'inc.php';
?>



  <style>
    :root {
      /* Material 3 Tonal Palette */
      --md-sys-color-background: #F4F0F8;
      --md-sys-color-surface: #FCF8FF;
      --md-sys-color-primary: #6750A4;
      --md-sys-color-primary-container: #EADDFF; /* প্রধান বাণী হাইলাইটার */
      --md-sys-color-on-primary-container: #21005D;
      --md-sys-color-secondary-container: #E8DEF8; /* ডেজিগনেশন টোন */
      --md-sys-color-on-secondary-container: #1D192B;
      --md-sys-color-on-surface: #1D1B20;
      --md-sys-color-on-surface-variant: #49454F;
      --md-sys-color-outline-variant: #CAC4D0;
      
      /* M3 Shapes & Geometry */
      --md-shape-corner-medium: 16px;
      --md-shape-corner-large: 28px;
      --md-shape-corner-full: 9999px;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

 

    /* Main Container */
    .message-container {
      max-width: 850px;
      width: 100%;
      background-color: var(--md-sys-color-surface);
      padding: 48px;
      border-radius: var(--md-shape-corner-large);
      box-shadow: 0px 1px 3px 1px rgba(0, 0, 0, 0.1), 0px 1px 2px 0px rgba(0, 0, 0, 0.05);
    }

    /* Header Profile Section */
    .profile-header {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      margin-bottom: 36px;
    }

    /* M3 Avatar/Image Styling */
    .avatar-container {
      width: 150px;
      height: 150px;
      border-radius: var(--md-shape-corner-large); /* M3 স্টাইল স্কোয়ারিশ-রাউন্ড */
      background-color: var(--md-sys-color-secondary-container);
      overflow: hidden;
      margin-bottom: 16px;
      border: 3px solid var(--md-sys-color-primary);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .avatar-container img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    /* নাম ও পদবী */
    .profile-header h1 {
      font-size: 1.8rem;
      font-weight: 700;
      color: var(--md-sys-color-primary);
      margin-bottom: 6px;
    }

    .designation-badge {
      background-color: var(--md-sys-color-secondary-container);
      color: var(--md-sys-color-on-secondary-container);
      padding: 6px 16px;
      border-radius: var(--md-shape-corner-full);
      font-size: 0.9rem;
      font-weight: 600;
      letter-spacing: 0.5px;
    }

    /* M3 Divider */
    hr {
      border: none;
      height: 1px;
      background-color: var(--md-sys-color-outline-variant);
      margin: 24px 0;
    }

    /* উদ্ধৃতি/প্রধান বক্তব্য (Tonal Highlight) */
    .quote-container {
      background-color: var(--md-sys-color-primary-container);
      color: var(--md-sys-color-on-primary-container);
      padding: 24px;
      border-radius: var(--md-shape-corner-medium);
      font-size: 1.1rem;
      font-weight: 600;
      margin-bottom: 28px;
      position: relative;
    }

    .quote-container::before {
      content: "“";
      font-size: 4rem;
      position: absolute;
      top: -20px;
      left: 12px;
      opacity: 0.15;
      font-family: serif;
    }

    /* মূল মেসেজ বডি */
    .message-body p {
      font-size: 1.05rem;
      color: var(--md-sys-color-on-surface-variant);
      margin-bottom: 20px;
      text-align: justify;
    }

    /* সমাপনী সংকেত */
    .closing-section {
      margin-top: 40px;
      text-align: right;
    }

    .closing-section p {
      font-size: 0.95rem;
      color: var(--md-sys-color-on-surface-variant);
    }
    
    .signature-title {
      font-weight: 700;
      color: var(--md-sys-color-primary);
      margin-top: 4px;
    }

    /* রেসপন্সিভ ডিজাইন */
    @media (max-width: 600px) {
      body {
        padding: 16px 8px;
      }
      .message-container {
        padding: 24px 16px;
        border-radius: var(--md-shape-corner-medium);
      }
      .profile-header h1 {
        font-size: 1.5rem;
      }
      .quote-container {
        font-size: 1rem;
        padding: 16px;
      }
      .message-body p {
        font-size: 0.95rem;
        text-align: left;
      }
    }
  </style>


  <div class="message-container">
    
    <!-- প্রধান ব্যক্তির প্রোফাইল সেকশন -->
    <div class="profile-header">
      <div class="avatar-container">
        <!-- এখানে প্রধান শিক্ষকের ছবি যুক্ত করবেন (img/principal.jpg) -->
        <img src="https://eimbox.com/logo/<?= $sccode ?>.png" alt="Logo" style="padding:10px; var(--md-shape-corner-large); ">
      </div>
      <h1></h1>
      <span class="designation-badge"><?=  $scname ?></span>
    </div>

    <!-- M3 টোনাল হাইলাইটেড কোটেশন -->
    <div class="quote-container">
      "শিক্ষাই আলোর পথ। আমরা শুধু জিপিএ-৫ নয়, বরং প্রতিটি শিক্ষার্থীকে একজন সৎ, দক্ষ এবং দেশপ্রেমিক মানবিক মানুষ হিসেবে গড়ে তুলতে বদ্ধপরিকর।"
    </div>

    <!-- মূল বক্তব্য বা বাণী -->
    <div class="message-body">
      <p>
        সুপ্রিয় শিক্ষার্থী, সম্মানিত অভিভাবক এবং শুভানুধ্যায়ীগণ, আসসালামু আলাইকুম। 
        একটি সমৃদ্ধ ও মেধাবী জাতি গঠনে মানসম্মত শিক্ষার বিকল্প নেই। ইআইএমবক্স স্কুল অ্যান্ড কলেজ শুরু থেকেই শিক্ষার্থীদের মাঝে কেবল তথ্যগত শিক্ষা নয়, বরং জ্ঞান ও প্রজ্ঞার আলো ছড়িয়ে দিতে কাজ করে যাচ্ছে। 
      </p>
      <p>
        বর্তমান যুগ তথ্যপ্রযুক্তির যুগ। পরিবর্তিত বিশ্বের চ্যালেঞ্জ মোকাবিলায় আমরা আমাদের পুরো শিক্ষাব্যবস্থাকে ডিজিটাল প্ল্যাটফর্মে রূপান্তর করেছি। আমাদের রয়েছে আধুনিক কম্পিউটার ল্যাব, মাল্টিমিডিয়া ক্লাসরুম এবং সম্পূর্ণ অনলাইন ম্যানেজমেন্ট সিস্টেম, যা শিক্ষার্থীদের পড়ালেখাকে আরও সহজ এবং আনন্দদায়ক করে তুলেছে। 
      </p>
      <p>
        আমরা বিশ্বাস করি, প্রতিটি শিশুর ভেতরেই একটি সুপ্ত প্রতিভা লুকিয়ে থাকে। আমাদের অভিজ্ঞ শিক্ষক মণ্ডলী অত্যন্ত স্নেহ ও পরম যত্নে সেই প্রতিভার বিকাশ ঘটাতে নিরলস পরিশ্রম করছেন। প্রাতিষ্ঠানিক শিক্ষার পাশাপাশি আমরা খেলাধুলা, সাহিত্য-সংস্কৃতি চর্চা এবং নৈতিক শিক্ষার ওপর সমান গুরুত্ব প্রদান করি।
      </p>
      <p>
        আমাদের এই অগ্রযাত্রায় সম্মানিত অভিভাবকদের সহযোগিতা আমাদের বড় অনুপ্রেরণা। আসুন, আমরা সবাই মিলে আমাদের সন্তানদের এমন এক সুন্দর পরিবেশে বড় করে তুলি, যেখানে তারা আগামী দিনের সুনাগরিক এবং বৈশ্বিক নাগরিক হিসেবে নিজেদের প্রতিষ্ঠা করতে পারে।
      </p>
      <p>
        আপনাদের সবার সুন্দর ও সুস্বাস্থ্যময় জীবন কামনা করছি। ধন্যবাদ।
      </p>
    </div>

    <hr>

    <!-- সমাপনী ও স্বাক্ষর এরিয়া -->
    <div class="closing-section">
      <p>শুভেচ্ছান্তে,</p>
      <p class="signature-title">.............</p>
      <p>অধ্যক্ষ</p>
      <p><?= $scname ?></p>
    </div>

  </div>


<?php include 'footer.php';?>
  </body>
</html>
