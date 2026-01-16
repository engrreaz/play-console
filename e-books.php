<?php
include 'inc.php'; // Contains header, DB connection, and session data

// Include subject details for mapping codes to names
include_once 'datam/datam-subject-list.php'; // Provides $datam_subject_list

// --- Filter Handling ---
$filter_class = isset($_GET['class']) ? $_GET['class'] : '';
$filter_search = isset($_GET['search']) ? $_GET['search'] : '';

// In a real scenario, you'd query a dedicated 'ebooks' table.
// For now, we'll generate a list from the subjects data, assuming a file structure.
$all_books = $datam_subject_list; // Using subject list as the source of books

// Apply filters
$filtered_books = $all_books;
if (!empty($filter_class)) {
    $filtered_books = array_filter($filtered_books, fn($book) => $book['classname'] == $filter_class);
}
if (!empty($filter_search)) {
    $filtered_books = array_filter($filtered_books, fn($book) => stripos($book['subject'], $filter_search) !== false || stripos($book['subben'], $filter_search) !== false);
}

// Get unique classes for the filter dropdown
$unique_classes = array_unique(array_column($all_books, 'classname'));
sort($unique_classes);
?>

<style>
    body { background-color: #f0f2f5; }
    .book-card {
        transition: all 0.3s ease-in-out;
    }
    .book-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }
    .book-cover {
        height: 250px;
        object-fit: cover;
    }
</style>

<main class="container mt-4">

    <div class="card mb-4">
        <div class="card-body d-flex align-items-center">
            <i class="bi bi-book-half text-primary me-3" style="font-size: 2.5rem;"></i>
            <div>
                <h1 class="h4 mb-0">E-Library</h1>
                <p class="mb-0 text-muted">Browse and read digital books and resources.</p>
            </div>
        </div>
    </div>

    <!-- Filter and Search Card -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="search" class="form-label">Search by Title</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="search" id="search" name="search" class="form-control" placeholder="e.g., Physics" value="<?php echo htmlspecialchars($filter_search); ?>">
                    </div>
                </div>
                <div class="col-md-5">
                    <label for="class" class="form-label">Filter by Class</label>
                    <select id="class" name="class" class="form-select">
                        <option value="">All Classes</option>
                        <?php foreach ($unique_classes as $class_name): ?>
                            <option value="<?php echo $class_name; ?>" <?php echo ($class_name == $filter_class) ? 'selected' : ''; ?>>
                                Class <?php echo htmlspecialchars($class_name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- E-Books Grid -->
    <div class="row">
        <?php
        if (count($filtered_books) > 0) {
            foreach ($filtered_books as $book) {
                $subcode = $book['subcode'];
                $seng = $book['subject'];
                $sben = $book['subben'];
                $clsname = $book['classname'];

                // Assume a file path for the book PDF and cover
                $book_pdf_path = strtolower('assets/ebooks/' . $sctype . '_' . $clsname . '_' . $subcode . '.pdf');
                $cover_jpg_path = strtolower($BASE_PATH_URL . 'books/' . $sctype . '_' . $clsname . '_' . $subcode . '_cover.jpg');
                $cover_png_path = strtolower($BASE_PATH_URL . 'books/' . $sctype . '_' . $clsname . '_' . $subcode . '_cover.png');
                
                $cover_url = $BASE_PATH_URL_FILE . 'books/no-image.png'; // Default
                if (file_exists($cover_jpg_path)) {
                    $cover_url = strtolower($BASE_PATH_URL_FILE . 'books/' . $sctype . '_' . $clsname . '_' . $subcode . '_cover.jpg');
                } elseif (file_exists($cover_png_path)) {
                    $cover_url = strtolower($BASE_PATH_URL_FILE . 'books/' . $sctype . '_' . $clsname . '_' . $subcode . '_cover.png');
                }
        ?>
            <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100 book-card">
                    <img src="<?php echo $cover_url; ?>" class="card-img-top book-cover" alt="Cover of <?php echo htmlspecialchars($seng); ?>">
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title fw-bold"><?php echo htmlspecialchars($seng); ?></h6>
                        <p class="card-text small text-muted"><?php echo htmlspecialchars($sben); ?></p>
                        <div class="mt-auto">
                            <span class="badge bg-primary">Class: <?php echo htmlspecialchars($clsname); ?></span>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="<?php echo $book_pdf_path; ?>" class="btn btn-primary btn-sm w-100" target="_blank">
                            <i class="bi bi-eye-fill me-2"></i>View Book
                        </a>
                    </div>
                </div>
            </div>
        <?php
            } // end foreach
        } else {
            echo '<div class="col-12"><div class="alert alert-warning">No books found matching your criteria.</div></div>';
        }
        ?>
    </div>
</main>

<div style="height:52px;"></div>

<?php include 'footer.php'; ?>
