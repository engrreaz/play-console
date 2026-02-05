<?php
include_once '../inc.light.php';

// ফিল্টার ভ্যালু রিসিভ করা
$session = $_POST['session'] ?? '';
$cls = $_POST['cls'] ?? '';

// কুয়েরি ফিল্টার সেটআপ
$where = "sccode='$sccode'";
if (!empty($session)) $where .= " AND sessionyear='$session'";
if (!empty($cls))     $where .= " AND classname='$cls'";

// এক্সাম লিস্ট কুয়েরি
$sql = "SELECT * FROM examlist WHERE $where ORDER BY datestart DESC";
$res = $conn->query($sql);

if ($res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        
        // ১. ওই পরীক্ষার এভারেজ প্রগ্রেস ক্যালকুলেশন (Sub-query)
        $exam_code = $row['examcode'];
        $prog_q = $conn->query("SELECT AVG(progress) as avg_p FROM examroutine WHERE sccode='$sccode' AND examname='$exam_code'");
        $prog_data = $prog_q->fetch_assoc();
        $percent = round($prog_data['avg_p'] ?? 0);
        
        // সার্কুলার প্রগ্রেস অফসেট (Circumference 157)
        $offset = 157 - ($percent / 100 * 157);

        // --- HTML কার্ড শুরু ---
        echo '
        <div class="exam-card shadow-sm border-0 mb-3" style="background: #fff; border-radius: 16px; padding: 16px; display: flex; align-items: center;">
            
            <div class="circular-progress-sm" style="position: relative; width: 60px; height: 60px;">
                <svg viewBox="0 0 60 60" style="transform: rotate(-90deg); width: 60px; height: 60px;">
                    <circle cx="30" cy="30" r="25" stroke="#F3EDF7" stroke-width="5" fill="none"></circle>
                    <circle cx="30" cy="30" r="25" stroke="var(--m3-primary)" stroke-width="5" fill="none" 
                            style="stroke-dasharray: 157; stroke-dashoffset: '.$offset.'; transition: 1s; stroke-linecap: round;"></circle>
                </svg>
                <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); font-size:0.65rem; font-weight:900;">'.$percent.'%</div>
            </div>

            <div style="flex-grow: 1; margin-left: 15px;">
                <div style="font-weight: 800; color: #1C1B1F; font-size: 1rem;">'.$row['examtitle'].'</div>
                <div style="font-size: 0.75rem; color: #79747E; font-weight: 600;">
                    <i class="bi bi-calendar3 me-1"></i> '.date("d M Y", strtotime($row['datestart'])).' 
                    <span class="mx-1">•</span> Class '.$row['classname'].'
                </div>
            </div>

            <div class="d-flex flex-column align-items-end gap-2">
                <span style="font-size: 0.6rem; padding: 3px 10px; border-radius: 100px; font-weight: 800; background:#EADDFF; color:#21005D;">
                    '.strtoupper($row['exam_type'] ?? 'EXAM').'
                </span>
                
                <div class="d-flex gap-1">';
                
                // বাটন ব্লকটি সরাসরি এখানে ইকো (echo) করা হয়েছে
                echo '
                    <button class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm" 
                            onclick="viewRoutine(\''.$row['examcode'].'\', \''.$row['classname'].'\')">
                        <i class="bi bi-calendar-range me-1"></i> Routine
                    </button>
                    
                    <button class="btn btn-sm btn-light border rounded-pill" 
                            onclick="editExam('.$row['id'].')">
                        <i class="bi bi-pencil"></i>
                    </button>
                    
                    <button class="btn btn-sm btn-light border text-danger rounded-pill" 
                            onclick="deleteExam('.$row['id'].')">
                        <i class="bi bi-trash"></i>
                    </button>';
                
                echo '
                </div>
            </div>
        </div>';
        // --- HTML কার্ড শেষ ---
    }
} else {
    echo '<div class="text-center py-5 opacity-50">No exams found for selected criteria.</div>';
}
?>