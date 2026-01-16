<?php
// File: front-page-block/head-block.php

$teacher_application_count = 0;

// ১. রোল ভেরিফিকেশন (নির্দিষ্ট উচ্চপদস্থ কর্মকর্তাদের জন্য)
$allowed_roles = ['Head Teacher', 'Asstt. Head Teacher', 'Principal', 'Super Administrator'];

if (isset($userlevel) && in_array($userlevel, $allowed_roles)) {
    
    // ২. অপ্টিমাইজড ডাটা ফেচিং
    if (isset($conn, $sccode)) {
        // স্ট্যাটাস ০ মানে নতুন, ৩ এর উপরে মানে রি-সাবমিট বা বিশেষ কিছু
        $stmt = $conn->prepare("
            SELECT COUNT(*) as app_count 
            FROM teacher_leave_app 
            WHERE sccode = ? AND (status = 0 OR status >= 3)
        ");
        
        if ($stmt) {
            $stmt->bind_param("s", $sccode);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result) {
                $data = $result->fetch_assoc();
                $teacher_application_count = (int)($data['app_count'] ?? 0);
            }
            $stmt->close();
        }
    }
}

// --- Presentation (Material 3 Action Card) ---
if ($teacher_application_count > 0):
?>

<div class="m-card elevation-1 border-0 mb-4" style="background-color: #FFF4E5; border-radius: 24px;">
    <div class="card-body p-4">
        <div class="d-flex align-items-start">
            <div class="rounded-4 bg-warning d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 56px; height: 56px; min-width: 56px;">
                <i class="bi bi-envelope-exclamation-fill text-white fs-3"></i>
            </div>
            
            <div class="flex-grow-1">
                <h6 class="fw-bold text-warning-emphasis mb-1" style="letter-spacing: 0.5px;">Pending Action</h6>
                <p class="text-dark-emphasis mb-3" style="font-size: 0.95rem; line-height: 1.4;">
                    You have <span class="fw-bold text-dark"><?php echo $teacher_application_count; ?> new leave application(s)</span> waiting for your approval.
                </p>
                
                <div class="d-flex gap-2">
                    <a href="leave-application-response.php" class="btn btn-warning rounded-pill px-4 py-2 fw-bold shadow-sm d-flex align-items-center">
                        <i class="bi bi-reply-all-fill me-2 fs-5"></i>
                        Review Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* অতিরিক্ত টাচ অপ্টিমাইজেশন */
    .btn-warning {
        background-color: #FF9800; /* M3 Warning Primary */
        border: none;
        color: white;
        transition: all 0.2s ease;
    }
    .btn-warning:active {
        transform: scale(0.96);
        background-color: #E68900;
    }
</style>

<?php endif; ?>