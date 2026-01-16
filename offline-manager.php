<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে
include 'datam/datam-stprofile.php';

// ১. ক্লাস ও সেকশন প্যারামিটার হ্যান্ডলিং
if (isset($_GET['cls']) && isset($_GET['sec'])) {
    $classname = $_GET['cls'];
    $sectionname = $_GET['sec'];
    $cteacher_data = [['cteachercls' => $classname, 'cteachersec' => $sectionname]];
}

$data_store = $_GET['store'] ?? 0;
$data_sync = $_GET['sync'] ?? 0;
$count_class = count($cteacher_data);

// ২. পারমিশন চেক (Optimized Mapping)
$settings_map = array_column($ins_all_settings, 'settings_value', 'setting_title');
$collection_permission = (isset($settings_map['Collection']) && strpos($settings_map['Collection'], $userlevel) !== false) ? 1 : 0;
$profile_entry_permission = (isset($settings_map['Profile Entry']) && strpos($settings_map['Profile Entry'], $userlevel) !== false) ? 1 : 0;
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface Background */

    /* Top App Bar */
    .m3-app-bar {
        background-color: #FFFFFF;
        padding: 16px;
        border-radius: 0 0 24px 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        position: sticky;
        top: 0;
        z-index: 1020;
    }

    /* Action Card Styling */
    .sync-card {
        background: #F3EDF7;
        border-radius: 28px;
        padding: 20px;
        margin: 15px;
        border: none;
    }

    .action-btn-circle {
        width: 60px; height: 60px;
        border-radius: 18px;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        border: none; transition: 0.2s;
        font-size: 0.6rem; font-weight: 700;
    }
    .action-btn-circle:active { transform: scale(0.9); }
    .action-btn-circle i { font-size: 1.5rem; margin-bottom: 2px; }

    /* Student Row Style */
    .st-row {
        background: white;
        border-radius: 20px;
        padding: 12px 16px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .st-avatar {
        width: 48px; height: 48px;
        border-radius: 12px;
        object-fit: cover;
        margin-right: 12px;
        background: #eee;
    }

    .status-dot {
        font-size: 1.2rem;
        margin-left: 8px;
    }

    /* Class Tab Styling */
    .class-tabs {
        display: flex; overflow-x: auto;
        padding: 10px 15px; gap: 8px;
    }
    .class-tabs::-webkit-scrollbar { display: none; }
    .btn-tab {
        border-radius: 100px; padding: 6px 16px;
        border: 1px solid #79747E; background: transparent;
        white-space: nowrap; font-size: 0.85rem;
    }
    .btn-tab.active { background: #EADDFF; border-color: #6750A4; color: #21005D; font-weight: 600; }
</style>

<main class="pb-5">
    <div class="m3-app-bar mb-3">
        <div class="d-flex align-items-center">
            <a href="build.php" class="btn btn-link text-dark p-0 me-3"><i class="bi bi-arrow-left fs-4"></i></a>
            <div>
                <h4 class="fw-bold mb-0">Offline Manager</h4>
                <small class="text-muted">Attendance Sync Tool</small>
            </div>
        </div>
    </div>

    <?php if ($count_class > 1): ?>
        <div class="class-tabs">
            <?php for ($h = 0; $h < $count_class; $h++): ?>
                <button id="btn<?php echo $h; ?>" class="btn-tab <?php echo ($h == 0) ? 'active' : ''; ?>" 
                        onclick="switchClass('<?php echo $h; ?>', '<?php echo $count_class; ?>');">
                    <?php echo $cteacher_data[$h]['cteachercls'] . " | " . $cteacher_data[$h]['cteachersec']; ?>
                </button>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

    <div class="sync-card shadow-sm text-center">
        <div class="d-flex justify-content-around mb-0">
            <button class="action-btn-circle bg-primary text-white" onclick="data_get();">
                <i class="bi bi-cloud-download"></i> GET
            </button>
            <button class="action-btn-circle bg-success text-white" onclick="data_sync();">
                <i class="bi bi-arrow-repeat"></i> SYNC
            </button>
            <button class="action-btn-circle bg-danger text-white" onclick="data_store();">
                <i class="bi bi-trash3"></i> RESET
            </button>
        </div>
        <div id="jsondatablock" class="mt-3 small text-muted text-truncate" style="font-size: 0.6rem;"></div>
    </div>

    <div class="container-fluid px-3">
        <?php 
        for ($h2 = 0; $h2 < $count_class; $h2++):
            $classname = $cteacher_data[$h2]['cteachercls'];
            $sectionname = $cteacher_data[$h2]['cteachersec'];
            $display = ($h2 == 0) ? "block" : "none";
        ?>
            <div id="clssecblock<?php echo $h2; ?>" style="display:<?php echo $display; ?>">
                <div class="d-flex justify-content-between align-items-center mb-3 px-2">
                    <h6 class="fw-bold text-secondary small text-uppercase mb-0">Student List</h6>
                    <span class="badge rounded-pill bg-primary-subtle text-primary px-3">
                        Total: <span id="cnt<?php echo $h2; ?>">0</span>
                    </span>
                </div>

                <div id="list-container-<?php echo $h2; ?>">
                    <?php
                    // ৩. স্টুডেন্ট লিস্ট ফেচিং
                    $cnt = 0;
                    $sy_param = "%$sy%";
                    $stmt = $conn->prepare("SELECT * FROM sessioninfo WHERE sccode = ? AND classname = ? AND sectionname = ? AND sessionyear LIKE ? ORDER BY rollno ASC");
                    $stmt->bind_param("ssss", $sccode, $classname, $sectionname, $sy_param);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()):
                        $stid = $row["stid"];
                        $roll = $row["rollno"];
                        $cnt++;

                        // প্রোফাইল ডাটা লুকআপ
                        $st_idx = array_search($stid, array_column($datam_st_profile, 'stid'));
                        $neng = $datam_st_profile[$st_idx]["stnameeng"] ?? 'Unknown';
                        
                        $photo = "https://eimbox.com/students/noimg.jpg";
                        if (file_exists('../students/' . $stid . '.jpg')) {
                            $photo = $BASE_PATH_URL_FILE . 'students/' . $stid . '.jpg';
                        }
                    ?>
                        <div class="st-row shadow-sm">
                            <img src="<?php echo $photo; ?>" class="st-avatar shadow-sm" onerror="this.src='https://eimbox.com/students/noimg.jpg'">
                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark small"><?php echo $neng; ?></div>
                                <div class="text-muted" style="font-size: 0.7rem;">Roll: <?php echo $roll; ?> | ID: <?php echo $stid; ?></div>
                                <div id="rollno<?php echo $cnt; ?>" hidden><?php echo $roll; ?></div>
                                <div id="stid<?php echo $cnt; ?>" hidden><?php echo $stid; ?></div>
                                <div id="stname<?php echo $cnt; ?>" hidden><?php echo $neng; ?></div>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-cloud-check-fill text-success status-dot" id="on<?php echo $cnt; ?>" title="Synced"></i>
                                <i class="bi bi-wifi-off text-danger status-dot" id="off<?php echo $cnt; ?>" title="Offline Data Available"></i>
                            </div>
                        </div>
                    <?php endwhile; $stmt->close(); ?>
                </div>

                <script>document.getElementById("cnt<?php echo $h2; ?>").innerText = "<?php echo $cnt; ?>";</script>
            </div>
        <?php endfor; ?>
    </div>
</main>

<div style="height: 60px;"></div>



<script>
    // ১. ইউআই সুইচিং ফাংশন
    function switchClass(cur, total) {
        for (let i = 0; i < total; i++) {
            document.getElementById('clssecblock' + i).style.display = 'none';
            document.getElementById('btn' + i)?.classList.remove("active");
        }
        document.getElementById('clssecblock' + cur).style.display = 'block';
        document.getElementById('btn' + cur)?.classList.add("active");
    }

    // ২. ডাটা অপারেশন কন্ট্রোল
    function data_get() { window.location.href = 'offline-manager.php'; }
    function data_store() { 
        Swal.fire({
            title: 'Reset Local Data?',
            text: "This will clear your local attendance cache.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#B3261E'
        }).then((res) => { if(res.isConfirmed) window.location.href = '?store=1'; });
    }
    function data_sync() { window.location.href = '?sync=1'; }

    // ৩. অফলাইন লজিক (LocalStorage & Android Integration)
    (function() {
        const email = '<?php echo explode("@", $usr)[0]; ?>';
        const cls = '<?php echo $cteacher_data[0]['cteachercls']; ?>';
        const sec = '<?php echo $cteacher_data[0]['cteachersec']; ?>';
        const stCount = <?php echo $cnt; ?>;

        // JSON অবজেক্ট তৈরি (Array based - Better approach)
        let studentsArray = [];
        for (let i = 1; i <= stCount; i++) {
            studentsArray.push({
                rollno: document.getElementById('rollno' + i).innerText,
                stid: document.getElementById('stid' + i).innerText,
                stname: document.getElementById('stname' + i).innerText,
                yn: 0
            });
        }

        const offlinePayload = {
            [email]: {
                classname: cls,
                sectionname: sec,
                stcount: stCount,
                attnddate: '<?php echo $td; ?>',
                lastsync: '<?php echo $cur; ?>',
                [cls]: { [sec]: studentsArray }
            }
        };

        // ব্রাউজার লোকাল স্টোরেজে রাখা
        localStorage.setItem("webData", JSON.stringify(offlinePayload));

        // অ্যান্ড্রয়েড নেটিভ স্টোরেজে পাঠানো (যদি থাকে)
        if (window.Android && <?php echo $data_store; ?> == 1) {
            window.Android.saveToSharedPreferences("webData", JSON.stringify(offlinePayload));
            Swal.fire('Stored!', 'Offline data saved to device.', 'success');
        }

        // ৪. ডাটা সিনক্রোনাইজেশন লজিক
        let rawData = localStorage.getItem("webData");
        if (window.Android) {
            const androidData = window.Android.getFromSharedPreferences("webData");
            if(androidData) rawData = androidData;
        }

        if (rawData) {
            const jsonPata = JSON.parse(rawData);
            let syncQuery = `count=${stCount}&cls=${cls}&sec=${sec}&adate=${jsonPata[email]["attnddate"]}&eby=${email}`;

            for (let d = 0; d < stCount; d++) {
                const k = d + 1;
                const stid = document.getElementById("stid" + k).innerText;
                const status = jsonPata[email][cls][sec][d]["yn"];
                
                // ইউআই আপডেট
                document.getElementById('off' + k).style.color = (status == 1) ? "#B3261E" : "#79747E";
                document.getElementById('off' + k).style.opacity = (status == 1) ? "1" : "0.2";
                
                syncQuery += `&stid${d}=${stid}&yn${d}=${status}`;
            }

            // যদি সিঙ্ক কমান্ড থাকে
            if (<?php echo $data_sync; ?> == 1) {
                fetch("backend/offline.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: syncQuery
                })
                .then(res => res.text())
                .then(response => {
                    Swal.fire('Sync Complete', 'Server Response: ' + response, 'success');
                })
                .catch(err => Swal.fire('Sync Failed', 'Check your connection.', 'error'));
            }
        }
    })();
</script>

<?php include 'footer.php'; ?>