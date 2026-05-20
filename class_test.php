<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>কাজ চলছে - Under Construction</title>
  <!-- Google Material Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@500;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  
  <style>
    :root {
      /* Material 3 Purple Tonal Palette Tokens */
      --md-sys-color-background: #F4F0F8; /* হালকা পার্পল টোনাল ব্যাকগ্রাউন্ড */
      --md-sys-color-surface: #FCF8FF; /* প্রধান কন্টেইনার সারফেস */
      --md-sys-color-primary: #6750A4; /* মেইন পার্পল ব্র্যান্ড কালার */
      --md-sys-color-primary-container: #EADDFF; /* উজ্জ্বল পার্পল টোনাল কন্টেইনার */
      --md-sys-color-on-primary-container: #21005D;
      --md-sys-color-secondary-container: #E8DEF8; /* মাঝারি পার্পল টোনাল কন্টেইনার */
      --md-sys-color-on-secondary-container: #1D192B;
      --md-sys-color-on-surface: #1D1B20;
      --md-sys-color-on-surface-variant: #49454F;
      --md-sys-color-outline-variant: #CAC4D0;
      
      /* M3 Geometric Shapes */
      --md-shape-corner-medium: 16px;
      --md-shape-corner-large: 28px;
      --md-shape-corner-full: 9999px;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Roboto', 'Noto Serif Bengali', sans-serif;
      background-color: var(--md-sys-color-background);
      color: var(--md-sys-color-on-surface);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 16px;
    }

    /* M3 Elevated Construction Card */
    .construction-card {
      max-width: 540px;
      width: 100%;
      height: 100vh;
      background-color: var(--md-sys-color-surface);
      padding: 48px 32px;
      border-radius: var(--md-shape-corner-large);
      text-align: center;
      box-shadow: 0px 4px 12px rgba(103, 80, 164, 0.08), 0px 1px 3px rgba(0, 0, 0, 0.05);
      animation: fadeIn 0.6s ease-out;
    }

    /* Animated Visual Element (M3 Tonal Cog/Gear) */
    .icon-container {
      width: 96px;
      height: 96px;
      background-color: var(--md-sys-color-primary-container);
      color: var(--md-sys-color-on-primary-container);
      border-radius: var(--md-shape-corner-large); /* M3 Squarish-Round */
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 28px;
      position: relative;
    }

    .gear-icon {
      font-size: 3.5rem;
      display: inline-block;
      animation: spin 4s linear infinite;
    }

    /* Typography */
    h1 {
      font-size: 2rem;
      font-weight: 700;
      color: var(--md-sys-color-primary);
      margin-bottom: 12px;
      letter-spacing: -0.5px;
    }

    /* Subtitle Badge */
    .status-badge {
      display: inline-block;
      background-color: var(--md-sys-color-secondary-container);
      color: var(--md-sys-color-on-secondary-container);
      padding: 6px 16px;
      border-radius: var(--md-shape-corner-full);
      font-size: 0.85rem;
      font-weight: 600;
      margin-bottom: 24px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    p.description {
      font-size: 1.05rem;
      color: var(--md-sys-color-on-surface-variant);
      margin-bottom: 32px;
      line-height: 1.6;
    }

    /* Progress Indicator Bar (M3 Linear Progress Feel) */
    .progress-track {
      background-color: var(--md-sys-color-secondary-container);
      height: 6px;
      border-radius: var(--md-shape-corner-full);
      width: 80%;
      margin: 0 auto 32px;
      overflow: hidden;
      position: relative;
    }

    .progress-bar {
      background-color: var(--md-sys-color-primary);
      height: 100%;
      width: 65%; /* বর্তমান কাজের অগ্রগতি */
      border-radius: var(--md-shape-corner-full);
      position: absolute;
      left: 0;
      top: 0;
      animation: progressShimmy 2s ease-in-out infinite alternate;
    }

    /* M3 Tonal Button (Back to Home Alternative) */
    .contact-btn {
      display: inline-block;
      background-color: var(--md-sys-color-primary);
      color: #ffffff;
      padding: 12px 28px;
      border-radius: var(--md-shape-corner-full);
      font-size: 0.95rem;
      font-weight: 500;
      text-decoration: none;
      box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.1);
      transition: all 0.2s ease;
    }

    .contact-btn:hover {
      background-color: var(--md-sys-color-on-primary-container);
      box-shadow: 0px 4px 8px rgba(103, 80, 164, 0.3);
      transform: translateY(-1px);
    }

    /* Animations */
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(15px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes progressShimmy {
      0% { width: 40%; left: 0%; }
      100% { width: 55%; left: 45%; }
    }

    /* Responsive */
    @media (max-width: 480px) {
      .construction-card {
        padding: 32px 20px;
        border-radius: var(--md-shape-corner-medium);
      }
      h1 { font-size: 1.6rem; }
      p.description { font-size: 0.95rem; }
      .progress-track { width: 100%; }
    }
  </style>
</head>
<body>

  <div class="construction-card">
    
    <!-- M3 Tonal Animated Gear Box -->
    <div class="icon-container">
      <span class="gear-icon">⚙️</span>
    </div>

    <!-- Status -->
    <span class="status-badge">Under Construction</span>
    
    <!-- Title -->
    <h1>উন্নয়ন কাজ চলছে</h1>
    
    <!-- Description -->
    <p class="description">
      ইউজার এক্সপেরিয়েন্স আরও সহজ ও আকর্ষণীয় করতে আমরা পেজটির ব্যাকএন্ড ও ডিজাইনে কিছু নতুন পরিবর্তন আনছি। খুব শীঘ্রই আমরা নতুন রূপে ফিরে আসছি। সাময়িক অসুবিধার জন্য আমরা আন্তরিকভাবে দুঃখিত।
    </p>

    <!-- M3 Linear Progress Bar (Indeterminate Touch) -->
    <div class="progress-track">
      <div class="progress-bar"></div>
    </div>

    <!-- Contact Button -->
    <a href="mailto:engrreaz@gmail.com" class="contact-btn">জরুরি প্রয়োজনে যোগাযোগ করুন</a>

  </div>

</body>
</html>