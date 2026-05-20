<?php
$page_title = "About Us";
include 'inc.php';
?>




  <style>
    :root {
      /* Material 3 Tonal Palette (Secondary/Tertiary Soft Tones) */
      --md-sys-color-background: #F4F0F8; /* হালকা টোনাল ব্যাকগ্রাউন্ড */
      --md-sys-color-surface: #FCF8FF;
      --md-sys-color-primary: #6750A4; /* সিগনেচার পার্পল */
      --md-sys-color-primary-container: #EADDFF; /* লাইট টোনাল কন্টেইনার */
      --md-sys-color-on-primary-container: #21005D;
      --md-sys-color-secondary-container: #E8DEF8; /* সেকেন্ডারি টোনাল কন্টেইনার */
      --md-sys-color-on-secondary-container: #1D192B;
      --md-sys-color-tertiary-container: #FFD8E4; /* ওয়ার্ম টোনাল কন্টেইনার */
      --md-sys-color-on-tertiary-container: #31111D;
      --md-sys-color-on-surface: #1D1B20;
      --md-sys-color-on-surface-variant: #49454F;
      --md-sys-color-outline-variant: #CAC4D0;
      
      /* M3 Shapes */
      --md-shape-corner-medium: 16px;
      --md-shape-corner-large: 28px;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }


    /* Main Container */
    .about-container {
      max-width: 900px;
      width: 100%;
      background-color: var(--md-sys-color-surface);
      padding: 40px;
      border-radius: var(--md-shape-corner-large);
      box-shadow: 0px 1px 3px 1px rgba(0, 0, 0, 0.1), 0px 1px 2px 0px rgba(0, 0, 0, 0.05);
    }

    /* Hero Header */
    .hero-section {
      text-align: center;
      margin-bottom: 40px;
    }

    .hero-section h1 {
      font-size: 2.4rem;
      font-weight: 700;
      color: var(--md-sys-color-primary);
      margin-bottom: 12px;
    }

    .hero-section p {
      font-size: 1.1rem;
      color: var(--md-sys-color-on-surface-variant);
      max-width: 600px;
      margin: 0 auto;
    }

    /* Tonal Cards Grid */
    .tonal-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin-bottom: 32px;
    }

    /* Material 3 Tonal Containers */
    .tonal-card {
      padding: 24px;
      border-radius: var(--md-shape-corner-medium);
      border: 1px solid transparent;
    }

    .tonal-card.primary {
      background-color: var(--md-sys-color-primary-container);
      color: var(--md-sys-color-on-primary-container);
    }

    .tonal-card.secondary {
      background-color: var(--md-sys-color-secondary-container);
      color: var(--md-sys-color-on-secondary-container);
    }

    .tonal-card.tertiary {
      background-color: var(--md-sys-color-tertiary-container);
      color: var(--md-sys-color-on-tertiary-container);
      grid-column: span 2; /* পুরো উইডথ জুড়ে থাকবে */
    }

    .tonal-card h2 {
      font-size: 1.3rem;
      font-weight: 600;
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .tonal-card p {
      font-size: 0.95rem;
      opacity: 0.9;
    }

    /* Info Divider */
    hr {
      border: none;
      height: 1px;
      background-color: var(--md-sys-color-outline-variant);
      margin: 32px 0;
    }

    /* Statistics Section */
    .stats-section {
      display: flex;
      justify-content: space-around;
      text-align: center;
      background-color: var(--md-sys-color-background);
      padding: 20px;
      border-radius: var(--md-shape-corner-medium);
      margin-top: 24px;
    }

    .stat-item h3 {
      font-size: 1.8rem;
      color: var(--md-sys-color-primary);
      font-weight: 700;
    }

    .stat-item p {
      font-size: 0.85rem;
      color: var(--md-sys-color-on-surface-variant);
      font-weight: 500;
    }

    /* Footer Contact Tone */
    .footer-note {
      text-align: center;
      margin-top: 32px;
      font-size: 0.9rem;
      color: var(--md-sys-color-on-surface-variant);
    }

    /* Responsive Design */
    @media (max-width: 680px) {
      body {
        padding: 16px 8px;
      }
      .about-container {
        padding: 24px 16px;
        border-radius: var(--md-shape-corner-medium);
      }
      .tonal-grid {
        grid-template-columns: 1fr;
      }
      .tonal-card.tertiary {
        grid-column: span 1;
      }
      .stats-section {
        flex-direction: column;
        gap: 16px;
      }
      .hero-section h1 { font-size: 1.8rem; }
    }
  </style>
</head>
<body>

  <div class="about-container">
    
    <!-- Hero Section -->
    <div class="hero-section">
      <h1>আমাদের সম্পর্কে</h1>
      <p> <?= $scname ?> বাংলাদেশের একটি আধুনিক ও আদর্শ শিক্ষাপ্রতিষ্ঠান, যেখানে মেধা ও নৈতিকতার সমন্বয়ে ভবিষ্যৎ গড়া হয়।</p>
    </div>

    <!-- Tonal Cards Grid -->
    <div class="tonal-grid">
      
      <!-- লক্ষ (Mission) - Primary Tonal Card -->
      <div class="tonal-card primary">
        <h2>🎯 আমাদের লক্ষ্য</h2>
        <p>আমাদের মূল লক্ষ্য হলো শিক্ষার্থীদের কেবল প্রাতিষ্ঠানিক শিক্ষায় নয়, বরং আধুনিক প্রযুক্তি, বিজ্ঞান এবং নৈতিক মূল্যবোধে বলীয়ান করে একজন সুনাগরিক হিসেবে গড়ে তোলা।</p>
      </div>

      <!-- উদ্দেশ্য (Vision) - Secondary Tonal Card -->
      <div class="tonal-card secondary">
        <h2>👁️ আমাদের উদ্দেশ্য</h2>
        <p>ডিজিটাল বাংলাদেশের সাথে তাল মিলিয়ে একটি বৈষম্যহীন এবং সৃজনশীল শিক্ষার্থীবান্ধব পরিবেশ নিশ্চিত করা, যেখানে প্রতিটি শিশু তার সুপ্ত প্রতিভা বিকাশের সুযোগ পাবে।</p>
      </div>

      <!-- ইতিহাস ও ঐতিহ্য - Tertiary Tonal Card -->
      <div class="tonal-card tertiary">
        <h2>🏛️ ইতিহাস ও ঐতিহ্য</h2>
        <p>প্রতিষ্ঠালগ্ন থেকেই আমাদের প্রতিষ্ঠানটি কুমিল্লা জেলার তিতাস উপজেলায় শিক্ষা বিস্তারে অগ্রণী ভূমিকা পালন করে আসছে। অভিজ্ঞ শিক্ষক মণ্ডলী, সমৃদ্ধ বিজ্ঞানাগার, কম্পিউটার ল্যাব এবং সম্পূর্ণ ডিজিটাল ক্লাসরুমের মাধ্যমে আমরা শিক্ষার্থীদের বিশ্বমানের শিক্ষা প্রদান করছি। পড়াশোনার পাশাপাশি খেলাধুলা ও সাংস্কৃতিক কর্মকাণ্ডে আমাদের শিক্ষার্থীরা প্রতিবছর কৃতিত্বের স্বাক্ষর রাখছে।</p>
      </div>

    </div>

    <!-- M3 Divider -->
    <hr>

    <!-- আমাদের অর্জন (Statistics) -->
    <h2 style="font-size: 1.3rem; font-weight: 600; margin-bottom: 16px; text-align: center; color: var(--md-sys-color-primary);">এক নজরে আমাদের প্রতিষ্ঠান</h2>
    
    <div class="stats-section">
      <div class="stat-item">
        <h3>১,২০০+</h3>
        <p>বর্তমান শিক্ষার্থী</p>
      </div>
      <div class="stat-item">
        <h3>৪৫+</h3>
        <p>দক্ষ শিক্ষক-শিক্ষিকা</p>
      </div>
      <div class="stat-item">
        <h3>১০০%</h3>
        <p>পাসের হার (পাবলিক পরীক্ষা)</p>
      </div>
      <div class="stat-item">
        <h3>২০+</h3>
        <p>সহ-শিক্ষা কার্যক্রম</p>
      </div>
    </div>

    <!-- Footer Note -->
    <div class="footer-note">
      <p>প্রতিষ্ঠানের যেকোনো তথ্যের জন্য যোগাযোগ করুন: <span style="color: var(--md-sys-color-primary); font-weight: 500;"><?= $mobile ?></span></p>
    </div>

  </div>

  

  

<?php include 'footer.php';?>
  </body>
</html>
