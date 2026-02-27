<?php
ob_start();
include 'inc.php';



$page_to_edit = $_GET['page'] ?? 'index.php';

// ১. আপডেট লজিক
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_doc'])) {
    $p_name = $_POST['pagename'];
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $tips = $_POST['tips'];
    $notes = $_POST['notes'];
    $video_id = $_POST['video_id'];


    // ডুপ্লিকেট চেক করে ইনসার্ট বা আপডেট
    $stmt = $conn->prepare("INSERT INTO page_docs (pagename, title, description, tips, notes, video_id) 
                            VALUES (?, ?, ?, ?, ?, ?) 
                            ON DUPLICATE KEY UPDATE 
                            title=VALUES(title), description=VALUES(description), 
                            tips=VALUES(tips), notes=VALUES(notes), video_id=VALUES(video_id)");
    $stmt->bind_param("ssssss", $p_name, $title, $desc, $tips, $notes, $video_id);

    $conn->query("UPDATE permission_map_app set page_title='$title' where page_name='$p_name'");

    if ($stmt->execute()) {
        header("Location: $p_name?msg=doc_updated");
        exit;
    }
}

// ২. বর্তমান ডাটা ফেচ করা
$stmt = $conn->prepare("SELECT * FROM page_docs WHERE pagename = ?");
$stmt->bind_param("s", $page_to_edit);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
?>


<main class="container py-5">
    <div class="card border-0 shadow-sm rounded-4 p-4">
        <h4 class="fw-bold text-primary mb-4">Edit Documentation for: <span
                class="text-dark"><?= $page_to_edit ?></span></h4>

        <form action="" method="POST">
            <input type="hidden" name="pagename" value="<?= $page_to_edit ?>">

            <div class="m3-floating-group mb-3">
                <label class="m3-floating-label">Page Title</label>
                <i class="bi bi-file-earmark-text m3-field-icon ps-1 text-primary"></i>
                <input type="text" name="title" class="form-control m3-input-floating"
                    value="<?= $data['title'] ?? $page_to_edit ?>" required>
            </div>

            <div class="mb-3">
                <label class="fw-bold mb-1">Detailed Guide (Heading, List, Image)</label>
                <textarea id="editor" name="description"><?= $data['description'] ?? '' ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="fw-bold mb-1 text-success">Quick Tips</label>
                    <textarea name="tips" class="form-control rounded-3" rows="4"><?= $data['tips'] ?? '' ?></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="fw-bold mb-1 text-warning">Important Notes</label>
                    <textarea name="notes" class="form-control rounded-3"
                        rows="4"><?= $data['notes'] ?? '' ?></textarea>
                </div>

                <div class="col-md-6 mb-3 m3-floating-group">
                    <label class="m3-floating-label">Video Guide ID (Optional)</label>
                    <i class="bi bi-youtube m3-field-icon ps-1 text-danger"></i>
                    <input type="text" name="video_id" class="form-control m3-input-floating"
                        value="<?= $data['video_id'] ?? '' ?>">
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" name="save_doc" class="btn btn-primary px-5 rounded-pill fw-bold shadow">Save &
                    Return</button>
                <a href="<?= $page_to_edit ?>" class="btn btn-light px-4 rounded-pill fw-bold">Cancel</a>
            </div>
        </form>
    </div>
</main>

<?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script></script></script></script>
<script>
    tinymce.init({
        selector: '#editor',
        plugins: 'lists link image table code media help wordcount',
        toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | image | removeformat',
        height: 450,
        border_radius: 12,
        content_style: 'body { font-family:Inter,Helvetica,Arial,sans-serif; font-size:14px }'
    });
</script>
</body>

</html>