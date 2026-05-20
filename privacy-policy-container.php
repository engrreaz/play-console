
  <style>
    :root {
      /* Material 3 Light Theme Colors */
      --md-sys-color-background: #FEF7FF;
      --md-sys-color-surface: #F7F2FA;
      --md-sys-color-surface-container: #F3EDF7;
      --md-sys-color-primary: #6750A4;
      --md-sys-color-on-primary: #FFFFFF;
      --md-sys-color-on-surface: #1D1B20;
      --md-sys-color-on-surface-variant: #49454F;
      --md-sys-color-outline-variant: #CAC4D0;
      --md-sys-color-primary-container: #EADDFF;
      --md-sys-color-on-primary-container: #21005D;
      
      /* Shape & Elevation Tokens */
      --md-shape-corner-medium: 12px;
      --md-shape-corner-large: 28px;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Roboto', sans-serif;
      background-color: var(--md-sys-color-background);
      color: var(--md-sys-color-on-surface);
      line-height: 1.6;
      padding: 40px 16px;
      justify-content: center;
    }

    /* Main Container (M3 Card Structure) */
    .container {
      max-width: 840px;
      width: 100%;
      background-color: var(--md-sys-color-surface);
      padding: 40px;
      border-radius: var(--md-shape-corner-large);
      box-shadow: 0px 1px 3px 1px rgba(0, 0, 0, 0.15), 0px 1px 2px 0px rgba(0, 0, 0, 0.30);
    }

    /* Typography */
    h1 {
      font-size: 2.5rem;
      font-weight: 500;
      color: var(--md-sys-color-primary);
      margin-bottom: 8px;
      letter-spacing: -0.5px;
    }

    .effective-date {
      font-size: 0.9rem;
      color: var(--md-sys-color-on-surface-variant);
      margin-bottom: 24px;
      font-weight: 500;
    }

    h2 {
      font-size: 1.4rem;
      font-weight: 500;
      color: var(--md-sys-color-primary);
      margin-top: 32px;
      margin-bottom: 12px;
    }

    h3 {
      font-size: 1.1rem;
      font-weight: 500;
      color: var(--md-sys-color-on-surface);
      margin-top: 20px;
      margin-bottom: 8px;
    }

    p {
      font-size: 1rem;
      color: var(--md-sys-color-on-surface-variant);
      margin-bottom: 16px;
    }

    /* Lists styling */
    ul {
      list-style-type: none; /* Custom M3 bullet feel */
      margin-bottom: 20px;
      padding-left: 8px;
    }

    ul li {
      position: relative;
      font-size: 0.95rem;
      color: var(--md-sys-color-on-surface-variant);
      padding-left: 24px;
      margin-bottom: 8px;
    }

    ul li::before {
      content: "•";
      position: absolute;
      left: 6px;
      color: var(--md-sys-color-primary);
      font-size: 1.2rem;
      top: -2px;
    }

    /* M3 Divider */
    hr {
      border: none;
      height: 1px;
      background-color: var(--md-sys-color-outline-variant);
      margin: 28px 0;
    }

    /* Links & Buttons */
    a {
      color: var(--md-sys-color-primary);
      text-decoration: none;
      font-weight: 500;
      border-bottom: 1px dashed var(--md-sys-color-primary);
      transition: all 0.2s ease;
    }

    a:hover {
      color: var(--md-sys-color-on-primary-container);
      border-bottom-style: solid;
    }

    /* Action Box for Links */
    .action-card {
      background-color: var(--md-sys-color-primary-container);
      color: var(--md-sys-color-on-primary-container);
      padding: 16px;
      border-radius: var(--md-shape-corner-medium);
      margin: 20px 0;
      display: inline-block;
    }

    .action-card a {
      color: var(--md-sys-color-on-primary-container);
      border-bottom-color: var(--md-sys-color-on-primary-container);
      font-weight: 700;
    }

    /* Contact List Custom Grid */
    .contact-list li {
      padding-left: 0;
      margin-bottom: 12px;
    }
    
    .contact-list li::before {
      display: none;
    }

    .contact-list strong {
      color: var(--md-sys-color-primary);
      display: inline-block;
      width: 110px;
    }

    /* Responsive adjustments */
    @media (max-width: 600px) {
      body {
        padding: 16px 8px;
      }
      .container {
        padding: 24px 16px;
        border-radius: var(--md-shape-corner-medium);
      }
      h1 { font-size: 2rem; }
      h2 { font-size: 1.25rem; }
      .contact-list strong {
        display: block;
        margin-bottom: 2px;
      }
    }
  </style>
</head>
<body>

  <div class="container">
    <h1>Privacy Policy</h1>
    <p class="effective-date">Effective Date: 2026-01-01</p>

    <hr>

    <h2>1. Introduction</h2>
    <p>
      This Privacy Policy explains how <strong>Reaz IT (“EIMBox”)</strong> collects, uses, and protects information when
      you use the EIMBox School Management System application.
      This service is intended for use by registered educational institutions including schools, colleges, and madrasas
      in Bangladesh.
    </p>
    <p>
      The application is intended for use under the supervision of registered educational institutions.
      Educational institutions remain the owner and controller of their institutional data.
    </p>

    <hr>

    <h2>2. Information We Collect</h2>

    <h3>2.1 Google Sign-In Information</h3>
    <p>We collect only the following information from Google Sign-In:</p>
    <ul>
      <li>Email address</li>
      <li>Profile name</li>
      <li>Profile image URL</li>
    </ul>
    <p>We do not collect Google user ID, social ID, phone number, or any other Google account data.</p>

    <h3>2.2 Institution Data</h3>
    <p>We collect and store institutional data including:</p>
    <ul>
      <li>Student information</li>
      <li>Teacher information</li>
      <li>Attendance records</li>
      <li>Exam results and gradebooks</li>
      <li>Payment and fee records</li>
      <li>Institution-generated documents (images/PDFs)</li>
    </ul>

    <hr>

    <h2>3. How We Use Information</h2>
    <p>Collected data is used strictly for:</p>
    <ul>
      <li>Managing educational institution activities</li>
      <li>Attendance tracking</li>
      <li>Academic record management</li>
      <li>Fee and payment tracking</li>
      <li>Communication via notifications</li>
    </ul>
    <p>We do not sell, rent, or share personal data with any third parties.</p>

    <hr>

    <h2>4. Permissions Usage</h2>

    <h3>4.1 Location</h3>
    <p>Location is used only during attendance submission to verify physical presence within the educational institution campus.</p>
    <ul>
      <li>No background location is collected</li>
      <li>No location data is stored or tracked</li>
      <li>No live tracking is performed</li>
    </ul>

    <h3>4.2 Camera</h3>
    <p>Camera is used only for scanning QR codes for verification purposes.</p>
    <ul>
      <li>No photos or videos are captured or stored</li>
      <li>No camera data is uploaded to any server</li>
    </ul>

    <h3>4.3 Bluetooth</h3>
    <p>Bluetooth is used only for connecting and printing via POS/thermal printers.</p>
    <ul>
      <li>No Bluetooth data is collected or stored</li>
      <li>Nearby devices may be scanned only for printer detection</li>
    </ul>

    <h3>4.4 Notifications</h3>
    <p>Notifications are used for attendance updates, exam notices, payments, and institutional announcements. Users can disable notifications anytime from device settings.</p>

    <hr>

    <h2>5. Data Storage & Security</h2>
    <p>All institutional data is stored securely in a MySQL database hosted on <strong><a href="https://eimbox.com" target="_blank">https://eimbox.com</a></strong> over HTTPS encrypted connection.</p>
    <p>We use commercially acceptable security measures to protect data. However, no method of transmission over the internet is 100% secure.</p>

    <hr>

    <h2>6. Cookies & WebView</h2>
    <p>The application uses WebView-based sessions and cookies to improve user experience, including session management, class, section, and exam-related data.</p>

    <hr>

    <h2>7. Data Retention</h2>
    <p>Data is retained as long as the institution account remains active. After account deactivation, data is stored for up to 180 days and then permanently deleted.</p>

    <hr>

    <h2>8. Data Sharing</h2>
    <p>We do not share, sell, or transfer any user or institutional data to third parties. Data is strictly accessible only to authorized users of the respective institution.</p>

    <hr>

    <h2>9. Account Management & Deletion</h2>
    <p>Institution administrators can create, edit, and delete teacher and student accounts.</p>
    
    <div class="action-card">
      <strong>Action Required?</strong> 
      <a href="https://playconsole.eimbox.com/delete-my-account.php" target="_blank">
        Account Deletion Request Link
      </a>
    </div>
    
    <p>Account logout will clear session data immediately.</p>

    <hr>

    <h2>10. Third-Party Services</h2>
    <p>We use only the following services:</p>
    <ul>
      <li>Google Sign-In</li>
      <li>Firebase Cloud Messaging (FCM)</li>
    </ul>
    <p>No advertising or analytics SDKs are used.</p>

    <hr>

    <h2>11. Children’s Privacy</h2>
    <p>This application is intended for supervised use under registered educational institutions. Student accounts are managed and created by institution authorities.</p>

    <hr>

    <h2>12. Security</h2>
    <p>We take reasonable measures to protect data using secure HTTPS communication and server-side protections.</p>

    <hr>

    <h2>13. Changes to This Policy</h2>
    <p>We may update this Privacy Policy from time to time. Users are advised to review this page periodically.</p>

    <hr>

    <h2>14. Contact Us</h2>
    <p>If you have any questions, contact us:</p>

    <ul class="contact-list">
      <li><strong>Developer:</strong> Engr. Reazul Hoque</li>
      <li><strong>Organization:</strong> Reaz IT (“EIMBox”)</li>
      <li><strong>Website:</strong> <a href="https://eimbox.com" target="_blank">https://eimbox.com</a></li>
      <li><strong>Email:</strong> engrreaz@gmail.com</li>
      <li><strong>Phone:</strong> 01919629672</li>
      <li><strong>Address:</strong> Reaz IT, Batakandi, Titas, Cumilla, Bangladesh</li>
    </ul>

  </div>

</body>
</html>