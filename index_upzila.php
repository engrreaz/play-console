

<div class="container-fluid mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1 text-primary-emphasis">উপজেলা ড্যাসবোর্ড</h4>
            <p class="text-body-secondary mb-0">স্বাগতম, উপজেলা শিক্ষা অফিসার (<?= $userps ?? 'মডেল থানা' ?>, <?= $userdist ?? 'ঢাকা' ?>)</p>
        </div>
        <div>
            <span class="badge rounded-pill bg-success-subtle text-success-emphasis px-3 py-2 border border-success-subtle">
                <i class="bi bi-circle-fill small text-success me-1"></i> রিয়েল-টাইম সিঙ্ক চালু
            </span>
        </div>
    </div>

    <!-- Alert for Holiday/Weekend -->
    <div class="alert bg-warning-subtle text-warning-emphasis border-0 rounded-4 d-flex align-items-center mb-4 shadow-sm" role="alert">
        <i class="bi bi-info-circle-fill fs-3 me-3"></i>
        <div>
            <strong class="d-block mb-1">আজ কর্মদিবস (বিদ্যালয় খোলা)</strong> 
            <span class="small">অধীনস্থ সকল প্রতিষ্ঠানের ডেটা স্বাভাবিকভাবে সিঙ্ক হচ্ছে। কোনো সরকারি ছুটির দিন বা উইকেন্ড নয়।</span>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Teachers Presence Card -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card bg-primary-subtle text-primary-emphasis border-0 rounded-4 h-100 material-card">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-person-video3 fs-5"></i>
                        </div>
                        <i class="bi bi-arrow-up-right-circle text-primary-emphasis fs-4 opacity-75"></i>
                    </div>
                    <div>
                        <h6 class="text-primary-emphasis opacity-75 fw-semibold mb-1">উপস্থিত শিক্ষক</h6>
                        <h2 class="fw-bold mb-0">৪,৫২০ <span class="fs-6 fw-normal text-primary-emphasis opacity-75">/ ৫,০০০</span></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teachers on Leave Card -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card bg-danger-subtle text-danger-emphasis border-0 rounded-4 h-100 material-card">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-person-fill-dash fs-5"></i>
                        </div>
                        <i class="bi bi-journal-text text-danger-emphasis fs-4 opacity-75"></i>
                    </div>
                    <div>
                        <h6 class="text-danger-emphasis opacity-75 fw-semibold mb-1">শিক্ষক ছুটিতে আছেন</h6>
                        <h2 class="fw-bold mb-0">১৫০ <span class="fs-6 fw-normal text-danger-emphasis opacity-75">জন</span></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students Presence Card -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card bg-info-subtle text-info-emphasis border-0 rounded-4 h-100 material-card">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-people-fill fs-5"></i>
                        </div>
                        <i class="bi bi-graph-up-arrow text-info-emphasis fs-4 opacity-75"></i>
                    </div>
                    <div>
                        <h6 class="text-info-emphasis opacity-75 fw-semibold mb-1">উপস্থিত শিক্ষার্থী</h6>
                        <h2 class="fw-bold mb-0">৮৫,২০০ <span class="fs-6 fw-normal text-info-emphasis opacity-75">/ ১,০০,০০০</span></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Institutions Card -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card bg-success-subtle text-success-emphasis border-0 rounded-4 h-100 material-card">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-bank fs-5"></i>
                        </div>
                        <i class="bi bi-buildings text-success-emphasis fs-4 opacity-75"></i>
                    </div>
                    <div>
                        <h6 class="text-success-emphasis opacity-75 fw-semibold mb-1">মোট প্রতিষ্ঠান (সিঙ্কড)</h6>
                        <h2 class="fw-bold mb-0">১২০ <span class="fs-6 fw-normal text-success-emphasis opacity-75">টি</span></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Schools Real-time Events / Activities -->
        <div class="col-12 col-lg-7">
            <div class="card bg-light border-0 rounded-4 h-100 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold text-dark mb-0">প্রতিষ্ঠানের কার্যক্রম ও ইভেন্ট</h5>
                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3">সব দেখুন</button>
                    </div>
                    
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center p-3 bg-white rounded-4 border-0 hover-bg-light transition-all">
                            <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                                <i class="bi bi-trophy-fill fs-5"></i>
                            </div>
                            <div class="ms-3 flex-grow-1">
                                <h6 class="mb-1 fw-bold">বার্ষিক ক্রীড়া প্রতিযোগিতা</h6>
                                <p class="text-muted small mb-0">মডেল সরকারি উচ্চ বিদ্যালয় &bull; সেশন: ১০:০০ - ০৪:০০</p>
                            </div>
                            <span class="badge bg-primary-subtle text-primary-emphasis rounded-pill px-3 py-2">আজ</span>
                        </div>
                        
                        <div class="d-flex align-items-center p-3 bg-white rounded-4 border-0 hover-bg-light transition-all">
                            <div class="bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                                <i class="bi bi-tree-fill fs-5"></i>
                            </div>
                            <div class="ms-3 flex-grow-1">
                                <h6 class="mb-1 fw-bold">বৃক্ষরোপণ কর্মসূচি</h6>
                                <p class="text-muted small mb-0">আদর্শ বালিকা বিদ্যালয় &bull; প্রাঙ্গন</p>
                            </div>
                            <span class="badge bg-success-subtle text-success-emphasis rounded-pill px-3 py-2">চলমান</span>
                        </div>

                        <div class="d-flex align-items-center p-3 bg-white rounded-4 border-0 hover-bg-light transition-all">
                            <div class="bg-warning-subtle text-warning-emphasis rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                                <i class="bi bi-megaphone-fill fs-5"></i>
                            </div>
                            <div class="ms-3 flex-grow-1">
                                <h6 class="mb-1 fw-bold">অভিভাবক সমাবেশ</h6>
                                <p class="text-muted small mb-0">শহীদ জিয়াউর রহমান কলেজ</p>
                            </div>
                            <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3 py-2">আগামীকাল</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Sync Status / Real time logs -->
        <div class="col-12 col-lg-5">
            <div class="card bg-secondary-subtle border-0 rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-4">রিয়েল-টাইম ডেটা সিঙ্ক লগ</h5>
                    
                    <div class="position-relative ms-2 mt-2">
                        <!-- Timeline line -->
                        <div class="border-start border-2 border-secondary border-opacity-25 position-absolute top-0 bottom-0 ms-2" style="left: -1px; z-index: 0;"></div>
                        
                        <div class="d-flex mb-4 position-relative z-1">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mt-1 border border-3 border-white shadow-sm" style="width: 24px; height: 24px; margin-left: -11px;">
                                <i class="bi bi-check fs-6"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1 fw-bold text-dark">উপস্থিতি ডেটা সিঙ্ক সম্পন্ন</h6>
                                <p class="text-muted small mb-0">১১৪ টি প্রতিষ্ঠান সফলভাবে সকাল ৯:৩০ এর মধ্যে ডেটা পাঠিয়েছে।</p>
                                <span class="text-body-tertiary small" style="font-size: 0.75rem;"><i class="bi bi-clock me-1"></i>৯:৩০ এএম</span>
                            </div>
                        </div>

                        <div class="d-flex mb-4 position-relative z-1">
                            <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center mt-1 border border-3 border-white shadow-sm" style="width: 24px; height: 24px; margin-left: -11px;">
                                <i class="bi bi-exclamation fs-6"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1 fw-bold text-danger">৬ টি প্রতিষ্ঠান ডেটা দেয়নি</h6>
                                <p class="text-muted small mb-0">সকাল ১০:০০ পেরিয়ে গেলেও ডেটা আসেনি। <a href="#" class="text-decoration-none fw-semibold">তালিকা দেখুন</a></p>
                                <span class="text-body-tertiary small" style="font-size: 0.75rem;"><i class="bi bi-clock me-1"></i>১০:০৫ এএম</span>
                            </div>
                        </div>

                        <div class="d-flex position-relative z-1">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mt-1 border border-3 border-white shadow-sm" style="width: 24px; height: 24px; margin-left: -11px;">
                                <i class="bi bi-arrow-repeat fs-6"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1 fw-bold text-dark">অপেক্ষমান কার্যক্রম</h6>
                                <p class="text-muted small mb-0">উপজেলা পর্যায়ের ২৩ টি ছুটির আবেদন অনুমোদনের অপেক্ষায় আছে।</p>
                                <span class="text-body-tertiary small" style="font-size: 0.75rem;"><i class="bi bi-clock me-1"></i>এখন</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom Material 3 Tonal style adjustments */
.material-card {
    transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
}
.material-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
}
.hover-bg-light:hover {
    background-color: var(--bs-light) !important;
}
.transition-all {
    transition: all 0.2s ease-in-out;
}
</style>
