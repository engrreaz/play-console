<?php
include 'inc.php';

if (isset($_GET['del_id']) && isset($sccode)) {

    $id = intval($_GET['del_id']);
    $sccode_pending = $sccode * 10;

    $sql = "DELETE FROM cashbook WHERE id = ? AND (sccode = ? OR sccode = ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iii", $id, $sccode, $sccode_pending);

        if ($stmt->execute()) {
            $stmt->close();

        } else {
            $stmt->close();

        }
    } else {

    }

} else {

}

?>

<script>
    document.location = "cashbookview.php";
</script>