<?php
/**
 * Offline Manager - M3-EIM-Floating Style
 * Standards: 8px Radius | Tonal Containers | Android Native Sync Integration
 */
$page_title = "Offline Manager";
include 'inc.php'; 
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

// ২. পারমিশন চেক
$settings_map = array_column($ins_all_settings, 'settings_value', 'setting_title');
$collection_permission = (isset($settings_map['Collection']) && strpos($settings_map['Collection'], $userlevel) !== false) ? 1 : 0;
?>

<style>
    body { background-color: #FEF7FF; margin: 0; padding: 0; }

    /* M3 App Bar */
    .m3-app-bar {
        width: 100%; position: sticky; top: 0; z-index: 1050;
        background: #fff; height: 60px; display: flex; align-items: center; 
        padding: 0 16px; border-radius: 0 0 8px 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .m3-app-bar .page-title { font-size: 1.1rem; font-weight: 700; color: #1C1B1F; flex-grow: 1; margin: 0; }

    /* Action Card (M3-EIM-Floating) */
    .m3-sync-card {
        background-color: #F3EDF7;
        border-radius: 8px !important;
        padding: 20px; margin: 16px;
        border: 1px solid #EADDFF;
        box-shadow: 0 1px 3px rgba(103, 80, 164, 0.05);
    }

    .btn-m3-action {
        width: 70px; height: 70px; border-radius: 8px !important;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        border: none; transition: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 0.65rem; font-weight: 800; text-transform: uppercase;
    }
    .btn-m3-action:active { transform: scale(0.95); opacity: 0.9; }
    .btn-m3-action i { font-size: 1.6rem; margin-bottom: 4px; }

    /* Class Tabs (8px Box Style) */
    .m3-tabs-container { display: flex; overflow-x: auto; padding: 10px 16px; gap: 8px; }
    .m3-tabs-container::-webkit-scrollbar { display: none; }
    
    .btn-m3-tab {
        border-radius: 8px !important; padding: 8px 16px;
        border: 1px solid #CAC4D0; background: #fff;
        white-space: nowrap; font-size: 0.8rem; font-weight: 700; color: #49454F;
    }
    .btn-m3-tab.active { background: #EADDFF; border-color: #6750A4; color: #21005D; }

    /* Student List Items */
    .m3-st-card {
        background: #fff; border-radius: 8px; padding: 12px 16px;
        margin: 0 16px 10px; display: flex; align-items: center;
        border: 1px solid #f0f0f0; box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }
    .m3-st-avatar {
        width: 50px; height: 50px; border-radius: 8px; /* Strict 8px */
        object-fit: cover; margin-right: 14px; background: #F3EDF7;
        border: 1px solid #EADDFF;
    }

    .status-indicator { font-size: 1.2rem; margin-left: 10px; }
</style>



<main class="pb-5">
    <?php if ($count_class > 1): ?>
        <div class="m3-tabs-container">
            <?php for ($h = 0; $h < $count_class; $h++): ?>
                <button id="btn<?php echo $h; ?>" class="btn-m3-tab <?php echo ($h == 0) ? 'active' : ''; ?>" 
                        onclick="switchClass('<?php echo $h; ?>', '<?php echo $count_class; ?>');">
                    <i class="bi bi-mortarboard me-1"></i>
                    <?php echo $cteacher_data[$h]['cteachercls'] . " | " . $cteacher_data[$h]['cteachersec']; ?>
                </button>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

    <div class="m3-sync-card shadow-sm text-center">
        <div class="d-flex justify-content-around">
            <button class="btn-m3-action bg-primary text-white shadow-sm" onclick="data_get();">
                <i class="bi bi-cloud-arrow-down-fill"></i> GET
            </button>
            <button class="btn-m3-action bg-success text-white shadow-sm" onclick="data_sync();">
                <i class="bi bi-arrow-repeat"></i> SYNC
            </button>
            <button class="btn-m3-action bg-danger text-white shadow-sm" onclick="data_store();">
                <i class="bi bi-trash3-fill"></i> RESET
            </button>
        </div>
        <div id="jsondatablock" class="mt-3 small text-muted text-truncate fw-bold" style="font-size: 0.6rem; letter-spacing: 0.5px;">READY TO SYNC</div>
    </div>

    <div class="container-fluid p-0">
        <?php 
        for ($h2 = 0; $h2 < $count_class; $h2++):
            $classname = $cteacher_data[$h2]['cteachercls'];
            $sectionname = $cteacher_data[$h2]['cteachersec'];
            $display = ($h2 == 0) ? "block" : "none";
        ?>
            <div id="clssecblock<?php echo $h2; ?>" style="display:<?php echo $display; ?>">
                <div class="px-4 mb-3 d-flex justify-content-between align-items-center">
                    <h6 class="text-secondary fw-bold small text-uppercase mb-0" style="letter-spacing: 1px;">Class Register</h6>
                    <span class="badge bg-primary-subtle text-primary m3-8px px-3 py-2 fw-bold">
                        TOTAL: <span id="cnt<?php echo $h2; ?>">0</span>
                    </span>
                </div>

                <div id="list-container-<?php echo $h2; ?>">
                    <?php
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

                        $st_idx = array_search($stid, array_column($datam_st_profile, 'stid'));
                        $neng = $datam_st_profile[$st_idx]["stnameeng"] ?? 'Unknown Student';
                        
                        $photo = "https://eimbox.com/students/noimg.jpg";
                        if (file_exists('../students/' . $stid . '.jpg')) {
                            $photo = $BASE_PATH_URL_FILE . 'students/' . $stid . '.jpg';
                        }
                    ?>
                        <div class="m3-st-card shadow-sm">
                            <img src="<?php echo $photo; ?>" class="m3-st-avatar shadow-sm" onerror="this.src='https://eimbox.com/students/noimg.jpg'">
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="fw-bold text-dark small text-truncate"><?php echo $neng; ?></div>
                                <div class="text-muted fw-bold" style="font-size: 0.65rem;">ROLL: <?php echo $roll; ?> | ID: <?php echo $stid; ?></div>
                                
                                <div id="rollno<?php echo $cnt; ?>" hidden><?php echo $roll; ?></div>
                                <div id="stid<?php echo $cnt; ?>" hidden><?php echo $stid; ?></div>
                                <div id="stname<?php echo $cnt; ?>" hidden><?php echo $neng; ?></div>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-cloud-check-fill text-success status-indicator" id="on<?php echo $cnt; ?>" title="Synced"></i>
                                <i class="bi bi-wifi-off text-danger status-indicator" id="off<?php echo $cnt; ?>" title="Offline Cached" style="opacity: 0.2;"></i>
                            </div>
                        </div>
                    <?php endwhile; $stmt->close(); ?>
                </div>

                <script>document.getElementById("cnt<?php echo $h2; ?>").innerText = "<?php echo $cnt; ?>";</script>
            </div>
        <?php endfor; ?>
    </div>
</main>

<div style="height: 75px;"></div>

<?php 
// আপনার নির্দেশ অনুযায়ী JS স্ক্রিপ্ট শুরু করার আগে footer.php ইনক্লুড করা হলো
include 'footer.php'; 
?>

<script>
    /**
     * UI Switching Function
     */
    function switchClass(cur, total) {
        for (let i = 0; i < total; i++) {
            document.getElementById('clssecblock' + i).style.display = 'none';
            document.getElementById('btn' + i)?.classList.remove("active");
        }
        document.getElementById('clssecblock' + cur).style.display = 'block';
        document.getElementById('btn' + cur)?.classList.add("active");
    }

    /**
     * Data Operation Controls
     */
    function data_get() { window.location.href = 'offline-manager.php'; }
    
    function data_store() { 
        Swal.fire({
            title: 'Reset Local Cache?',
            text: "This will clear offline attendance data from this device.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#B3261E',
            cancelButtonColor: '#79747E',
            confirmButtonText: 'YES, RESET'
        }).then((res) => { if(res.isConfirmed) window.location.href = '?store=1'; });
    }
    
    function data_sync() { window.location.href = '?sync=1'; }

    /**
     * Android & LocalStorage Integration Logic
     */
    (function() {
        const email = '<?php echo explode("@", $usr)[0]; ?>';
        const cls = '<?php echo $cteacher_data[0]['cteachercls']; ?>';
        const sec = '<?php echo $cteacher_data[0]['cteachersec']; ?>';
        const stCount = <?php echo $cnt; ?>;

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

        // ব্রাউজার লোকাল স্টোরেজ আপডেট
        localStorage.setItem("webData", JSON.stringify(offlinePayload));

        // অ্যান্ড্রয়েড শেয়ারড প্রিফারেন্স ইন্টিগ্রেশন (যদি নেটিভ অ্যাপে থাকে)
        if (window.Android && <?php echo $data_store; ?> == 1) {
            window.Android.saveToSharedPreferences("webData", JSON.stringify(offlinePayload));
            Swal.fire({ title: 'Device Synced', text: 'Data cached for offline use.', icon: 'success', timer: 2000 });
        }

        // ডাটা রিকোভারি এবং ইউআই ইন্ডিকেটর আপডেট
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
                
                // অফলাইন ইন্ডিকেটর আপডেট
                if (status == 1) {
                    document.getElementById('off' + k).style.color = "#B3261E";
                    document.getElementById('off' + k).style.opacity = "1";
                    document.getElementById('on' + k).style.opacity = "0.2";
                }
                
                syncQuery += `&stid${d}=${stid}&yn${d}=${status}`;
            }

            // সার্ভার সিঙ্ক্রোনাইজেশন শুরু (যদি ইউআরএল প্যারামিটার থাকে)
            if (<?php echo $data_sync; ?> == 1) {
                fetch("backend/offline.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: syncQuery
                })
                .then(res => res.text())
                .then(response => {
                    Swal.fire('Cloud Sync Complete', 'Server Response: ' + response, 'success');
                })
                .catch(err => Swal.fire('Sync Error', 'Check server connection.', 'error'));
            }
        }
    })();
</script>