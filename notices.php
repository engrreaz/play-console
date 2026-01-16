<?php
include 'inc.php'; // Contains header, DB connection, and session data

$sccode = $_SESSION['sccode'];

// --- Data Fetching (Optimized with JOIN) ---
$notices = [];
$sql = "SELECT n.title, n.descrip, n.icon, n.color, n.entrytime, u.profilename 
        FROM notice n
        LEFT JOIN usersapp u ON n.entryby = u.email AND n.sccode = u.sccode
        WHERE n.sccode = ?
        ORDER BY n.entrytime DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $sccode);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $notices[] = $row;
}
$stmt->close();
?>

<style>
    body { background-color: #f0f2f5; }
    .accordion-button {
        font-weight: 600;
    }
    .accordion-button:not(.collapsed) {
        background-color: #e9f5ff;
        color: #0d6efd;
    }
    .notice-meta {
        font-size: 0.8rem;
        color: #6c757d;
    }
</style>

<main class="container mt-4">

    <div class="card mb-4">
        <div class="card-body d-flex align-items-center">
            <i class="bi bi-bell-fill text-primary me-3" style="font-size: 2.5rem;"></i>
            <div>
                <h1 class="h4 mb-0">Notices</h1>
                <p class="mb-0 text-muted">Latest announcements and updates from the institution.</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-2">
            <?php if (count($notices) > 0): ?>
                <div class="accordion" id="noticesAccordion">
                    <?php 
                    $sl = 0;
                    foreach ($notices as $notice):
                        $sl++;
                        $icon = $notice['icon'] ?: 'bell';
                        $color = $notice['color'] ?: '#0d6efd';
                        $author = $notice['profilename'] ?: 'System';
                    ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading<?php echo $sl; ?>">
                                <button
                                    class="accordion-button collapsed"
                                    type="button"
                                    data-mdb-toggle="collapse"
                                    data-mdb-target="#collapse<?php echo $sl; ?>"
                                    aria-expanded="false"
                                    aria-controls="collapse<?php echo $sl; ?>"
                                >
                                    <i class="bi bi-<?php echo htmlspecialchars($icon); ?> me-3" style="color: <?php echo htmlspecialchars($color); ?>;"></i>
                                    <?php echo htmlspecialchars($notice['title']); ?>
                                </button>
                            </h2>
                            <div id="collapse<?php echo $sl; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $sl; ?>" data-mdb-parent="#noticesAccordion">
                                <div class="accordion-body">
                                    <div class="notice-meta mb-3">
                                        Posted on <?php echo date('d M Y, h:i A', strtotime($notice['entrytime'])); ?>
                                        by <span class="fw-bold"><?php echo htmlspecialchars($author); ?></span>
                                    </div>
                                    <hr>
                                    <p><?php echo nl2br(htmlspecialchars($notice['descrip'])); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info m-0">
                    There are no notices to display at the moment.
                </div>
            <?php endif; ?>
        </div>
    </div>

</main>

<div style="height:52px;"></div>

<?php include 'footer.php'; ?>
