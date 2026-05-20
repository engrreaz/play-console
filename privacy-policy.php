<?php
session_start();

if(isset($_SESSION["user"])) {
   include 'inc.php';
} else {
    include 'header.php';
}
?>

  <style>
    body {
      font-family: Arial, sans-serif;
      line-height: 1.6;
      padding: 20px;
      color: #222;
    }

    h1,
    h2,
    h3 {
      color: #111;
    }

    .container {
      max-width: 900px;
      margin: auto;
    }

    hr {
      margin: 20px 0;
    }
  </style>
<body>
  <div class="container">

    <h1>Privacy Policy</h1>

    <p><strong>Effective Date:</strong> 2026-01-01</p>

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
    <p>
      We collect only the following information from Google Sign-In:
    </p>
    <ul>
      <li>Email address</li>
      <li>Profile name</li>
      <li>Profile image URL</li>
    </ul>
    <p>
      We do not collect Google user ID, social ID, phone number, or any other Google account data.
    </p>

    <h3>2.2 Institution Data</h3>
    <p>
      We collect and store institutional data including:
    </p>
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
    <p>
      Collected data is used strictly for:
    </p>
    <ul>
      <li>Managing educational institution activities</li>
      <li>Attendance tracking</li>
      <li>Academic record management</li>
      <li>Fee and payment tracking</li>
      <li>Communication via notifications</li>
    </ul>

    <p>
      We do not sell, rent, or share personal data with any third parties.
    </p>

    <hr>

    <h2>4. Permissions Usage</h2>

    <h3>4.1 Location</h3>
    <p>
      Location is used only during attendance submission to verify physical presence within the educational institution
      campus.
    </p>
    <ul>
      <li>No background location is collected</li>
      <li>No location data is stored or tracked</li>
      <li>No live tracking is performed</li>
    </ul>

    <h3>4.2 Camera</h3>
    <p>
      Camera is used only for scanning QR codes for verification purposes.
    </p>
    <ul>
      <li>No photos or videos are captured or stored</li>
      <li>No camera data is uploaded to any server</li>
    </ul>

    <h3>4.3 Bluetooth</h3>
    <p>
      Bluetooth is used only for connecting and printing via POS/thermal printers.
    </p>
    <ul>
      <li>No Bluetooth data is collected or stored</li>
      <li>Nearby devices may be scanned only for printer detection</li>
    </ul>

    <h3>4.4 Notifications</h3>
    <p>
      Notifications are used for attendance updates, exam notices, payments, and institutional announcements.
      Users can disable notifications anytime from device settings.
    </p>

    <hr>

    <h2>5. Data Storage & Security</h2>
    <p>
      All institutional data is stored securely in a MySQL database hosted on
      <strong>https://eimbox.com</strong> over HTTPS encrypted connection.
    </p>

    <p>
      We use commercially acceptable security measures to protect data.
      However, no method of transmission over the internet is 100% secure.
    </p>

    <hr>

    <h2>6. Cookies & WebView</h2>
    <p>
      The application uses WebView-based sessions and cookies to improve user experience,
      including session management, class, section, and exam-related data.
    </p>

    <hr>

    <h2>7. Data Retention</h2>
    <p>
      Data is retained as long as the institution account remains active.
      After account deactivation, data is stored for up to 180 days and then permanently deleted.
    </p>

    <hr>

    <h2>8. Data Sharing</h2>
    <p>
      We do not share, sell, or transfer any user or institutional data to third parties.
      Data is strictly accessible only to authorized users of the respective institution.
    </p>

    <hr>

    <h2>9. Account Management & Deletion</h2>
    <p>
      Institution administrators can create, edit, and delete teacher and student accounts.
      Users may request account deletion through the following link:
    </p>

    <p>
      <a href="https://playconsole.eimbox.com/delete-my-account.php">
        Account Deletion Request
      </a>
    </p>

    <p>
      Account logout will clear session data immediately.
    </p>

    <hr>

    <h2>10. Third-Party Services</h2>
    <p>
      We use only the following services:
    </p>
    <ul>
      <li>Google Sign-In</li>
      <li>Firebase Cloud Messaging (FCM)</li>
    </ul>

    <p>No advertising or analytics SDKs are used.</p>

    <hr>

    <h2>11. Children’s Privacy</h2>
    <p>
      This application is intended for supervised use under registered educational institutions.
      Student accounts are managed and created by institution authorities.
    </p>

    <hr>

    <h2>12. Security</h2>
    <p>
      We take reasonable measures to protect data using secure HTTPS communication and server-side protections.
    </p>

    <hr>

    <h2>13. Changes to This Policy</h2>
    <p>
      We may update this Privacy Policy from time to time.
      Users are advised to review this page periodically.
    </p>

    <hr>

    <h2>14. Contact Us</h2>
    <p>
      If you have any questions, contact us:
    </p>

    <ul>
      <li><strong>Developer:</strong> Engr. Reazul Hoque</li>
      <li><strong>Organization:</strong> Reaz IT (“EIMBox”)</li>
      <li><strong>Website:</strong> https://eimbox.com</li>
      <li><strong>Email:</strong> engrreaz@gmail.com</li>
      <li><strong>Phone:</strong> 01919629672</li>
      <li><strong>Address:</strong> Reaz IT, Batakandi, Titas, Cumilla, Bangladesh</li>
    </ul>

  </div>




  <?php 
  if(isset($_SESSION["user"])) {
   include 'footer.php';
} else {
    include 'footer.all.php';
}
?>
</body>

</html>