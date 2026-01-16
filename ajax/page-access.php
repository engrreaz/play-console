<?php
session_start();

// Assuming inc.inc.php is in the root and establishes a DB connection ($conn)
if (file_exists('../inc.inc.php')) {
    include_once '../inc.inc.php';
} else {
    // Handle error if the include file is not found
    die("Critical file 'inc.inc.php' not found.");
}

// Ensure session sccode is set and we have a valid DB connection
if (!isset($_SESSION['sccode']) || empty($_SESSION['sccode']) || !$conn) {
    die("Error: Missing session data or database connection.");
}

$sccode = $_SESSION['sccode'];
$curfile = isset($_POST['page_name']) ? $_POST['page_name'] : '';
$stay_update = isset($_POST['stay']) ? floatval($_POST['stay']) : 0;

if (empty($curfile)) {
    die("Error: Page name not provided.");
}

// Sanitize the filename to prevent directory traversal attacks
$curfile = basename($curfile);

if ($stay_update > 0) {
    // This is a stay time update.
    $sql = "UPDATE package_limit_data SET total_stay = total_stay + ? WHERE sccode = ? AND page_name = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("dss", $stay_update, $sccode, $curfile);
        $stmt->execute();
        $stmt->close();
        // echo "Stay time updated."; // Optional: response for debugging
    }
} else {
    // This is an initial page access.
    // Check if the page entry already exists.
    $sql_check = "SELECT id FROM package_limit_data WHERE sccode = ? AND page_name = ?";
    $stmt_check = $conn->prepare($sql_check);
    if ($stmt_check) {
        $stmt_check->bind_param("ss", $sccode, $curfile);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            // Entry exists, so update the access_count.
            $sql_update = "UPDATE package_limit_data SET access_count = access_count + 1 WHERE sccode = ? AND page_name = ?";
            $stmt_update = $conn->prepare($sql_update);
            if ($stmt_update) {
                $stmt_update->bind_param("ss", $sccode, $curfile);
                $stmt_update->execute();
                $stmt_update->close();
                // echo "Access count updated."; // Optional: response for debugging
            }
        } else {
            // Entry does not exist, so insert a new record.
            $sql_insert = "INSERT INTO package_limit_data (sccode, page_name, access_count, total_stay) VALUES (?, ?, 1, 0)";
            $stmt_insert = $conn->prepare($sql_insert);
            if ($stmt_insert) {
                $stmt_insert->bind_param("ss", $sccode, $curfile);
                $stmt_insert->execute();
                $stmt_insert->close();
                // echo "New page access recorded."; // Optional: response for debugging
            }
        }
        $stmt_check->close();
    }
}

// The connection is likely closed by a script included in inc.inc.php or at the end of script execution.
// If not, uncomment the line below.
// $conn->close();
?>