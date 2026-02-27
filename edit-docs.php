<?php
ob_start();
$page_title = "Edit Documentation";
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

<style>


    /* M3 Hero Section */
    .doc-hero {
        background: linear-gradient(135deg, #6750A4 0%, #4F378B 100%);
        color: white;
        padding: 30px 24px 80px;
        border-radius: 0 0 40px 40px;
        text-align: left;
        position: relative;
    }

    .hero-content {
        max-width: 900px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .back-circle {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: 0.3s;
    }

    .back-circle:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        transform: translateX(-5px);
    }

    .doc-status-badge {
        background: #EADDFF;
        color: #21005D;
        padding: 4px 12px;
        border-radius: 100px;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
    }

    /* Card Adjustment */
    .editor-container {
        margin-top: -60px;
        position: relative;
        z-index: 10;
        max-width: 950px;
    }

    .m3-input-box {
        background: #F3EDF7;
        border-radius: 12px;
        padding: 10px 16px;
        border: 1px solid #E7E0EC;
    }
</style>


<main>
    <section class="doc-hero shadow">
        <div class="hero-content">
            <div class="d-flex align-items-center gap-3">
                <div>
                    <div class="doc-status-badge mb-2">
                        <i class="bi bi-pencil-square me-1"></i> Editor Mode
                    </div>
                    <h2 class="fw-black m-0" style="letter-spacing: -0.5px;">
                        <?= $data['title'] ?? 'Setup Guide' ?>
                    </h2>
                    <p class="small opacity-75 fw-bold mb-0">
                        Editing: <span class="font-monospace text-warning"><?= $page_to_edit ?></span>
                    </p>
                </div>
            </div>
            <div class="d-none d-md-block opacity-25">
                <i class="bi bi-journal-code" style="font-size: 80px;"></i>
            </div>
        </div>
    </section>

    <div class="container editor-container">
        <div class="card border-0 shadow-lg rounded-5 p-2 p-md-4">
            <form action="" method="POST" class="card-body">
                <input type="hidden" name="pagename" value="<?= $page_to_edit ?>">

                <div class="m3-input-box mb-4">
                    <label class="small fw-black text-primary text-uppercase mb-1" style="font-size: 10px;">Display
                        Title</label>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-type-h1 me-2 text-muted"></i>
                        <input type="text" name="title" class="form-control border-0 bg-transparent fw-bold fs-5 p-0"
                            value="<?= $data['title'] ?? $page_to_edit ?>" placeholder="Enter page title..." required >
                    </div>
                </div>

                <div class="mb-4">
                    <label class="fw-black mb-2 text-dark d-flex align-items-center">
                        <i class="bi bi-layers-half me-2 text-primary"></i> Detailed Documentation
                    </label>
                    <div class="rounded-4 overflow-hidden border">
                        <textarea id="editor" name="description"><?= $data['description'] ?? '' ?></textarea>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded-4" style="background: #E8F5E9;">
                            <label class="fw-black mb-2 text-success small">
                                <i class="bi bi-lightbulb me-1"></i> QUICK TIPS
                            </label>
                            <textarea name="tips" class="form-control border-0 bg-white rounded-3 shadow-sm" rows="4"
                                placeholder="Add helpful shortcuts..."><?= $data['tips'] ?? '' ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-4" style="background: #FFF3E0;">
                            <label class="fw-black mb-2 text-warning small">
                                <i class="bi bi-exclamation-triangle me-1"></i> IMPORTANT NOTES
                            </label>
                            <textarea name="notes" class="form-control border-0 bg-white rounded-3 shadow-sm" rows="4"
                                placeholder="Critical warnings..."><?= $data['notes'] ?? '' ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-4 p-3 rounded-4 bg-light">
                    <div class="d-flex align-items-center gap-3">
                        <div class="m3-icon-circle bg-danger text-white rounded-3"
                            style="width:40px; height:40px; display:flex; align-items:center; justify-content:center;">
                            <i class="bi bi-youtube"></i>
                        </div>
                        <div class="flex-grow-1">
                            <label class="small fw-black text-muted text-uppercase" style="font-size: 10px;">YouTube
                                Video ID</label>
                            <input type="text" name="video_id" class="form-control border-0 bg-transparent p-0 fw-bold"
                                value="<?= $data['video_id'] ?? '' ?>" placeholder="e.g. dQw4w9WgXcQ">
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-5">
                    <button type="submit" name="save_doc"
                        class="btn btn-primary px-5 rounded-pill py-1 fw-black shadow">
                        <i class="bi bi-cloud-arrow-up-fill me-2 fs-1"></i>
                    </button>
                    <a href="<?= $page_to_edit ?>"
                        class="btn btn-link text-muted text-decoration-none fw-bold px-4">Discard Changes</a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
</script>
</script>
</script>
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