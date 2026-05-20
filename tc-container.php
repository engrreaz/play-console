
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

    <h1>Terms & Conditions</h1>

    <p><strong>Effective Date:</strong> 2026-01-01</p>

    <hr>

    <h2>1. Introduction</h2>
    <p>
      These Terms and Conditions govern the use of the EIMBox School Management System application provided by
      <strong>Reaz IT (“EIMBox”)</strong>.
      By accessing or using this application, you agree to comply with these terms.
    </p>

    <p>
      EIMBox is designed for use by registered educational institutions such as schools, colleges, and madrasas under
      institutional supervision.
    </p>

    <hr>

    <h2>2. Institutional Control</h2>
    <p>
      All user accounts including students, teachers, and guardians are created and managed by the respective
      educational institution.
      The institution remains the sole owner and controller of all institutional data.
    </p>

    <hr>

    <h2>3. Use of Service</h2>
    <p>
      Users agree to use the application only for legitimate educational purposes including:
    </p>

    <ul>
      <li>Attendance management</li>
      <li>Academic record management</li>
      <li>Result and grading system</li>
      <li>Fee and payment tracking</li>
      <li>Institutional communication</li>
    </ul>

    <p>
      Any misuse, unauthorized access, or system manipulation is strictly prohibited.
    </p>

    <hr>

    <h2>4. Permissions Usage</h2>

    <h3>4.1 Location</h3>
    <p>
      Location is used only for verifying attendance within the institution campus during attendance submission.
      No background tracking or location storage is performed.
    </p>

    <h3>4.2 Camera</h3>
    <p>
      Camera is used only for scanning QR codes for verification purposes.
      No image or video is stored or uploaded.
    </p>

    <h3>4.3 Bluetooth</h3>
    <p>
      Bluetooth is used only for connecting to thermal or POS printers for receipt printing.
    </p>

    <hr>

    <h2>5. Data Ownership</h2>
    <p>
      All data including student records, teacher records, attendance, results, and payments belongs to the respective
      educational institution.
      EIMBox acts only as a software service provider.
    </p>

    <hr>

    <h2>6. Privacy</h2>
    <p>
      Use of the application is also governed by our Privacy Policy:
    </p>

    <p>
      <a href="https://eimbox.com/privacy-policy">
        https://eimbox.com/privacy-policy
      </a>
    </p>

    <hr>

    <h2>7. Account Management</h2>
    <p>
      Institutions have full control over user accounts including creation, editing, suspension, and deletion of student
      and teacher accounts.
    </p>

    <p>
      Users may request account deletion via:
    </p>

    <p>
      <a href="https://playconsole.eimbox.com/delete-my-account.php">
        Account Deletion Request
      </a>
    </p>

    <hr>

    <h2>8. Service Availability</h2>
    <p>
      We aim to provide uninterrupted service, however we do not guarantee that the application will always be available
      without interruption or error.
    </p>

    <hr>

    <h2>9. Limitation of Liability</h2>
    <p>
      EIMBox shall not be held responsible for any indirect, incidental, or consequential damages arising from the use
      or inability to use the application.
    </p>

    <hr>

    <h2>10. Security</h2>
    <p>
      All data is transmitted securely using HTTPS encryption.
      We implement reasonable security measures to protect user data but cannot guarantee absolute security.
    </p>

    <hr>

    <h2>11. Termination</h2>
    <p>
      We reserve the right to suspend or terminate access to the service in case of misuse, violation of terms, or
      institutional request.
    </p>

    <hr>

    <h2>12. Changes to Terms</h2>
    <p>
      We may update these Terms and Conditions at any time. Users are advised to review this page periodically.
    </p>

    <hr>

    <h2>13. Contact Information</h2>

    <ul>
      <li><strong>Developer:</strong> Engr. Reazul Hoque</li>
      <li><strong>Organization:</strong> Reaz IT (“EIMBox”)</li>
      <li><strong>Website:</strong> https://eimbox.com</li>
      <li><strong>Email:</strong> engrreaz@gmail.com</li>
      <li><strong>Phone:</strong> 01919629672</li>
      <li><strong>Address:</strong> Reaz IT, Batakandi, Titas, Cumilla, Bangladesh</li>
    </ul>

  </div>