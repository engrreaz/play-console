<?php include 'inc.php'; ?>


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
  
  <div class="container">
<div style="max-width:900px;margin:auto;padding:20px;font-family:Arial;line-height:1.7;color:#222;">

<h1>Account Deletion Policy</h1>

<hr>

<h2>1. Introduction</h2>
<p>
This Account Deletion Policy explains how users of the EIMBox School Management System can request deletion of their account and associated data.
</p>

<p>
This service is operated by <strong>Reaz IT (“EIMBox”)</strong> for registered educational institutions including schools, colleges, and madrasas.
</p>

<hr>

<h2>2. Who Can Request Deletion</h2>
<p>
Only authorized users (students, teachers, guardians, or institution staff) who are part of a registered institution can request account deletion.
</p>

<p>
In most cases, final deletion approval is managed by the respective educational institution authority.
</p>

<hr>

<h2>3. How to Request Account Deletion</h2>
<p>
Users can submit an account deletion request using the official deletion request link below:
</p>

<p>
<a href="https://playconsole.eimbox.com/delete-my-account.php" target="_blank">
https://playconsole.eimbox.com/delete-my-account.php
</a>
</p>

<p>
Alternatively, users may contact their institution administrator to initiate deletion.
</p>

<hr>

<h2>4. Data That Will Be Deleted</h2>
<p>The following data may be permanently deleted upon approval:</p>

<ul>
<li>User profile information (name, email, image)</li>
<li>Attendance records</li>
<li>Academic results and grade records</li>
<li>Payment and fee records</li>
<li>QR verification logs</li>
<li>Institutional user activity data</li>
</ul>

<hr>

<h2>5. Data Retention Policy</h2>
<p>
After a deletion request is approved, data may be retained temporarily for backup purposes for up to <strong>180 days</strong>.
After this retention period, data is permanently removed from all systems and backups.
</p>

<hr>

<h2>6. Institutional Control</h2>
<p>
All user accounts are created and managed by the respective educational institution.
Therefore, the institution has the authority to approve, suspend, or permanently delete accounts.
</p>

<p>
Educational institutions remain the controller of their institutional data.
</p>

<hr>

<h2>7. Important Notes</h2>
<ul>
<li>Deleted data cannot be recovered after permanent removal.</li>
<li>Some records may be retained if required for legal or institutional compliance.</li>
<li>Deletion does not affect data already archived by the institution externally.</li>
</ul>

<hr>

<h2>8. Security</h2>
<p>
All deletion requests are processed securely through HTTPS encrypted communication to ensure data protection.
</p>

<hr>

<h2>9. Contact Information</h2>

<ul>
<li><strong>Developer:</strong> Engr. Reazul Hoque</li>
<li><strong>Organization:</strong> Reaz IT (“EIMBox”)</li>
<li><strong>Website:</strong> https://eimbox.com</li>
<li><strong>Email:</strong> engrreaz@gmail.com</li>
<li><strong>Phone:</strong> 01919629672</li>
<li><strong>Address:</strong> Reaz IT, Batakandi, Titas, Cumilla, Bangladesh</li>
</ul>

</div>

<?php include 'footer.php'; ?>
</body>

</html>