<?php
include 'inc.php'; 
include_once 'datam/datam-subject-list.php'; 

// ১. সেশন ইয়ার হ্যান্ডলিং (Priority: GET > COOKIE > Default $sy)
$current_session = $_GET['year'] ?? $_GET['y'] ?? $_GET['session'] ?? $_GET['sessionyear'] 
                   ?? $_COOKIE['query-session'] 
                   ?? $sy;

// ২. ফিল্টার হ্যান্ডলিং
$filter_class = $_GET['class'] ?? '';
$filter_search = $_GET['search'] ?? '';

$all_books = $datam_subject_list; 

// ফিল্টার অ্যাপ্লাই করা
$filtered_books = array_filter($all_books, function($book) use ($filter_class, $filter_search) {
    $match_class = empty($filter_class) || $book['classname'] == $filter_class;
    $match_search = empty($filter_search) || 
                    stripos($book['subject'], $filter_search) !== false || 
                    stripos($book['subben'], $filter_search) !== false;
    return $match_class && $match_search;
});

// ড্রপডাউনের জন্য ইউনিক ক্লাস বের করা
$unique_classes = array_unique(array_column($all_books, 'classname'));
sort($unique_classes);

$page_title = "E-Library";
?>

<style>
    body { background-color: #FEF7FF; font-size: 0.9rem; }

    /* Standard M3 Top Bar (8px Bottom Radius) */
    .m3-app-bar {
        background: #fff; height: 56px; display: flex; align-items: center; padding: 0 16px;
        position: sticky; top: 0; z-index: 1050; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-radius: 0 0 8px 8px;
    }
    .m3-app-bar .page-title { font-size: 1.1rem; font-weight: 700; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* Compact Filter Card (8px Radius) */
    .m3-card { background: #fff; border-radius: 8px; padding: 12px; margin: 8px 12px; border: 1px solid #eee; box-shadow: 0 1px 2px rgba(0,0,0,0.03); }
    
    /* Book Card Condensed */
    .book-card {
        background: #fff; border-radius: 8px; overflow: hidden; height: 100%;
        border: 1px solid #f0f0f0; transition: transform 0.2s;
    }
    .book-card:active { transform: scale(0.97); }
    
    .book-cover-wrapper {
        position: relative; height: 160px; background: #F3EDF7;
        display: flex; align-items: center; justify-content: center;
    }
    .book-cover-img { height: 100%; width: 100%; object-fit: cover; }
    
    .book-info { padding: 8px; }
    .book-title { font-weight: 800; font-size: 0.8rem; color: #1C1B1F; margin-bottom: 2px; line-height: 1.2; }
    .book-subtitle { font-size: 0.7rem; color: #49454F; margin-bottom: 5px; height: 2.4em; overflow: hidden; }
    
    .class-badge {
        background: #EADDFF; color: #21005D; padding: 2px 8px;
        border-radius: 4px; font-size: 0.6rem; font-weight: 800;
    }

    .btn-read {
        background: #6750A4; color: white; border: none; width: 100%;
        padding: 6px; font-size: 0.75rem; font-weight: 700; border-radius: 0 0 8px 8px;
    }

    .input-m3 { border-radius: 8px !important; border: 1px solid #79747E; font-size: 0.85rem; padding: 6px 10px; }
</style>

<header class="m3-app-bar shadow-sm">
    <a href="reporthome.php" class="back-btn"><i class="bi bi-arrow-left me-3 fs-4"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <div class="action-icons"><i class="bi bi-bookmark-star fs-5 text-primary"></i></div>
</header>

<main class="pb-5">
    <div class="m3-card shadow-sm">
        <form method="GET" action="">
            <div class="row g-2">
                <div class="col-8">
                    <input type="search" name="search" class="form-control input-m3" 
                           placeholder="Search books..." value="<?php echo htmlspecialchars($filter_search); ?>">
                </div>
                <div class="col-4">
                    <select name="class" class="form-select input-m3" onchange="this.form.submit()">
                        <option value="">Class</option>
                        <?php foreach ($unique_classes as $class_name): ?>
                            <option value="<?php echo $class_name; ?>" <?php echo ($class_name == $filter_class) ? 'selected' : ''; ?>>
                                <?php echo $class_name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <input type="hidden" name="year" value="<?php echo $current_session; ?>">
            </div>
        </form>
    </div>

    <div class="container-fluid px-3">
        <div class="row gx-2 gy-3">
            <?php if (count($filtered_books) > 0): ?>
                <?php foreach ($filtered_books as $book): 
                    $subcode = $book['subcode'];
                    $clsname = $book['classname'];
                    
                    // ফাইল পাথ লজিক
                    $book_pdf = strtolower('assets/ebooks/'.$sctype.'_'.$clsname.'_'.$subcode.'.pdf');
                    $cover_url = "https://eimbox.com/books/".strtolower($sctype.'_'.$clsname.'_'.$subcode)."_cover.jpg";
                ?>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="book-card shadow-sm">
                        <div class="book-cover-wrapper">
                            <img src="<?php echo $cover_url; ?>" class="book-cover-img" 
                                 onerror="this.src='https://eimbox.com/books/no-image.png'">
                            <div class="position-absolute bottom-0 start-0 p-1">
                                <span class="class-badge">Cls: <?php echo $clsname; ?></span>
                            </div>
                        </div>
                        <div class="book-info">
                            <div class="book-title text-truncate"><?php echo $book['subject']; ?></div>
                            <div class="book-subtitle small"><?php echo $book['subben']; ?></div>
                        </div>
                        <a href="<?php echo $book_pdf; ?>" target="_blank" class="text-decoration-none">
                            <button class="btn-read"><i class="bi bi-book me-1"></i> READ NOW</button>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5 opacity-50">
                    <i class="bi bi-journal-x display-4"></i>
                    <p class="fw-bold small mt-2">No digital resources found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<div style="height: 65px;"></div> <?php include 'footer.php'; ?>