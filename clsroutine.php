<?php
$page_title = "Class Routine";
include 'inc.php';

$classname = $_GET['cls'] ?? ($cteacher_data[0]['cteachercls'] ?? '');
$sectionname = $_GET['sec'] ?? ($cteacher_data[0]['cteachersec'] ?? '');
$viewday = $_GET['day'] ?? 'today';

$days_order = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
$routine_data = [];

/* ================= FETCH ROUTINE ================= */

$sql = "
SELECT
r.day,r.wday,r.period, r.tid,
cs.timestart,cs.timeend,
COALESCE(sub_local.subject,sub_global.subject) subject,
t.tname teachername

FROM clsroutine r

LEFT JOIN classschedule cs
ON cs.period=r.period
AND cs.sccode=r.sccode
AND cs.sessionyear=r.sessionyear

LEFT JOIN subjects sub_local
ON sub_local.subcode=r.subcode
AND sub_local.sccode=?
AND sub_local.sccategory=?
AND (sub_local.sup_class IS NULL OR FIND_IN_SET(?,sub_local.sup_class))

LEFT JOIN subjects sub_global
ON sub_global.subcode=r.subcode
AND sub_global.sccode=0
AND sub_global.sccategory=?
AND (sub_global.sup_class IS NULL OR FIND_IN_SET(?,sub_global.sup_class))

LEFT JOIN teacher t
ON t.tid=r.tid AND t.sccode=r.sccode

WHERE
r.sccode=? AND
r.sessionyear=? AND
r.classname=? AND
r.sectionname=?

ORDER BY r.wday,r.period
";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "issssisss",
    $sccode,
    $sctype,
    $classname,
    $sctype,
    $classname,
    $sccode,
    $sessionyear,
    $classname,
    $sectionname
);
$stmt->execute();
$res = $stmt->get_result();

while ($row = $res->fetch_assoc()) {
    $routine_data[$row['day']][] = $row;
}
$stmt->close();
?>


<style>
    :root {
        --bottom-safe: 0px;
    }

    .period-card.active {
        box-shadow: 0 0 14px var(--m3-primary);
        border: 2px solid var(--m3-primary);
    }

    .progress {
        height: 6px;
        background: #eee;
        border-radius: 10px;
        margin-top: 6px;
        overflow: hidden
    }

    .bar {
        height: 100%;
        background: var(--m3-primary);
        width: 0%
    }

    .countdown {
        font-size: .7rem;
        font-weight: 800;
        color: var(--m3-primary);
        margin-top: 4px;
    }

    .btn-day {
        margin: 2px;
        padding: 6px 10px;
        border: none;
        border-radius: 8px;
        background: var(--m3-tonal-container);
        cursor: pointer;
        font-weight: 700;
    }

    .time-strip {
        width: 80px;
        text-align: center;
        border-right: 2px solid var(--m3-tonal-container);
        margin-right: 16px;
    }

    .day-separator {
        font-weight: 900;
        margin: 18px 10px 8px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--m3-primary);
    }

    .day-separator::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--m3-tonal-container);
    }
</style>

<style>
    .teacher-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        margin-right: 6px;
        object-fit: cover;
        background: #ddd;
    }

    .period-card {
        transition: .3s;
    }

    .period-card.active {
        transform: scale(1.02);
    }
</style>


<style>
    .m3-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, .35);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .m3-modal-box {
        background: #fff;
        padding: 16px;
        border-radius: 14px;
        width: 90%;
        max-width: 380px;
        max-height: 80vh;
        overflow: auto;
    }

    .m3-modal-title {
        font-weight: 900;
        margin-bottom: 10px;
    }

    /* ===== Bottom Sheet ===== */

    .m3-sheet {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: var(--bottom-safe);
        /* ⭐ magic line */

        background: rgba(0, 0, 0, .3);
        display: none;
        align-items: flex-end;
        z-index: 9999;
    }

    .sheet-panel {
        background: #fff;
        width: 100%;
        border-radius: 18px 18px 0 0;
        padding: 12px;
        max-height: 75vh;
        overflow: auto;

        transform: translateY(100%);
        transition: .35s cubic-bezier(.2, .8, .2, 1);
    }

    .m3-sheet.active {
        display: flex;
    }

    .m3-sheet.active .sheet-panel {
        transform: translateY(0);
    }

    /* item */

    .sheet-item {
        padding: 12px;
        border-radius: 10px;
        margin: 4px 0;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;

        transition: .25s;
    }

    .sheet-item:hover {
        background: #f2f6ff;
    }


    .sheet-item.active {
        background: #dbe5ff;
        transform: scale(1.02);
    }

    /* pin */

    .pin-btn {
        border: none;
        background: none;
        font-size: 18px;
        cursor: pointer;
    }

    /* animation highlight */

    .period-active {
        animation: pulseGlow 1.4s infinite;
    }

    @keyframes pulseGlow {
        0% {
            box-shadow: 0 0 0px #6ea8ff
        }

        50% {
            box-shadow: 0 0 12px #6ea8ff
        }

        100% {
            box-shadow: 0 0 0px #6ea8ff
        }
    }
</style>



<main>

    <div class="hero-container">
        <div style="display:flex;justify-content:space-between">
            <div>
                <div style="font-size:1.5rem;font-weight:900"><?php echo $page_title; ?></div>
                <div style="font-size:.8rem">Weekly Academic Schedule</div>
            </div>
            <div style="z-index: 999;">
                <div class="session-pill">
                    Session : <?php echo $sessionyear; ?>
                </div>

                <div id="cls-day" style="display:flex;gap:6px;justify-content:flex-end">

                    <button class="btn-m3-submit" onclick="openSheet('classSheet')">
                        <i class="bi bi-collection"></i>
                    </button>

                    <button class="btn-m3-submit" onclick="openSheet('daySheet')">
                        <i class="bi bi-calendar3"></i>
                    </button>

                </div>


            </div>


        </div>
    </div>



    <!-- CLASS FILTER -->
    <div class="m3-card" style="margin-top:-20px;padding:16px 12px 0" hidden>
        <form id="filterForm" class="row gx-2">

            <div class="col-5">
                <select name="cls" class="m3-select-floating">
                    <?php foreach ($cteacher_data as $c): ?>
                        <option value="<?php echo $c['cteachercls']; ?>" <?php if ($classname == $c['cteachercls'])
                               echo 'selected'; ?>>
                            <?php echo $c['cteachercls']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-4">
                <select name="sec" class="m3-select-floating">
                    <?php foreach ($cteacher_data as $c): ?>
                        <option value="<?php echo $c['cteachersec']; ?>" <?php if ($sectionname == $c['cteachersec'])
                               echo 'selected'; ?>>
                            <?php echo $c['cteachersec']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-3">
                <button type="submit" class="btn-m3-submit" style="height:48px;width:100%">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>
    </div>
    <!-- DAY BUTTONS -->
    <div class="m3-card" style="padding:10px;margin-bottom:10px" hidden>
        <button class="btn-day" onclick="setDay('today')">Today</button>
        <button class="btn-day" onclick="setDay('all')">All</button>

        <?php foreach ($days_order as $d): ?>
            <button class="btn-day" onclick="setDay('<?php echo $d; ?>')">
                <?php echo substr($d, 0, 3); ?>
            </button>
        <?php endforeach; ?>
    </div>


    <div id="routine-area">
        <div class="routine-list" style="padding-bottom:40px">

            <?php
            $now = time();
            $currentDay = date('l');

            foreach ($days_order as $day) {

                if ($viewday != 'all') {
                    if ($viewday == 'today' && $day != $currentDay)
                        continue;
                    if ($viewday != 'today' && $viewday != $day)
                        continue;
                }

                if (!isset($routine_data[$day]))
                    continue;
                ?>

                <div class="day-separator"><?php echo strtoupper($day); ?></div>
                <div class="widget-grid">

                    <?php foreach ($routine_data[$day] as $p):

                        $start = strtotime($p['timestart']);
                        $end = strtotime($p['timeend']);

                        $isCurrent = ($day == $currentDay && $now >= $start && $now <= $end);
                        ?>

                        <div class="m3-list-item period-card" data-start="<?php echo $start; ?>" data-end="<?php echo $end; ?>">

                            <div class="time-strip">
                                <div style="font-weight:900"><?php echo date('h:i', $start); ?></div>
                                <div style="font-size:.7rem"><?php echo date('A', $start); ?></div>
                            </div>

                            <div class="item-info">
                                <div class="st-title"><?php echo $p['subject'] ?: 'Free Period'; ?></div>
                                <div class="st-desc" style="display:flex;align-items:center">

                                    <img class="teacher-avatar" src="<?= teacher_profile_image_path($p['tid']) ?>"
                                        alt="Teacher Avatar">

                                    <?php echo $p['teachername'] ?: '-'; ?>

                                </div>

                                <div class="progress">
                                    <div class="bar"></div>
                                </div>
                                <div class="countdown"></div>

                            </div>

                            <div class="period-pill">P-<?php echo $p['period']; ?></div>

                        </div>




                    <?php endforeach; ?>
                </div>
            <?php } ?>

        </div>
    </div>


</main>




<!-- CLASS SHEET -->
<div id="classSheet" class="m3-sheet">

    <div class="sheet-panel">

        <div class="sheet-header">
            Class Select
        </div>

        <div class="sheet-body" id="classList">

            <?php foreach ($cteacher_data as $c): ?>
                <?php
                $isActive = ($c['cteachercls'] == $classname && $c['cteachersec'] == $sectionname);
                ?>
                <div class="sheet-item <?= $isActive ? 'active' : '' ?>" data-cls="<?= $c['cteachercls'] ?>"
                    data-sec="<?= $c['cteachersec'] ?>">

                    <span>
                        <?= $c['cteachercls'] ?> - <?= $c['cteachersec'] ?>
                    </span>

                    <div>
                        <button class="pin-btn"
                            onclick="event.stopPropagation();pinClass('<?= $c['cteachercls'] ?>','<?= $c['cteachersec'] ?>')">
                            📌
                        </button>
                    </div>

                </div>
            <?php endforeach; ?>


        </div>

    </div>
</div>



<!-- DAY SHEET -->
<div id="daySheet" class="m3-sheet">

    <div class="sheet-panel">

        <div class="sheet-header">
            Day Select
        </div>

        <div class="sheet-body">

            <?php foreach ($days_order as $d): ?>
                <?php

                if ($viewday == 'today')
                    $viewday = date('l');
                $day_active = ($d == $viewday);
                ?>

                <div class="sheet-item day-item <?= $day_active ? 'active' : '' ?>" data-day="<?= $d ?>">
                    <?= $d ?>
                </div>
            <?php endforeach; ?>


        </div>

    </div>
</div>



<?php include 'footer.php'; ?>
<script>

    /* AJAX FILTER */
    document.getElementById("filterForm").addEventListener("submit", e => {
        e.preventDefault();
        const data = new FormData(e.target);
        fetch("?" + new URLSearchParams(data))
            .then(r => r.text())
            .then(html => {
                document.querySelector("main").innerHTML =
                    new DOMParser()
                        .parseFromString(html, "text/html")
                        .querySelector("main").innerHTML;
            });
    });

    /* DAY SWITCH */
    function setDay(d) {
        const url = new URL(window.location);
        url.searchParams.set("day", d);
        window.location = url;
    }

    /* LIVE PERIOD ENGINE */

    function updatePeriods() {
        const now = Math.floor(Date.now() / 1000);

        document.querySelectorAll(".period-card").forEach(card => {

            const start = parseInt(card.dataset.start);
            const end = parseInt(card.dataset.end);

            const bar = card.querySelector(".bar");
            const cd = card.querySelector(".countdown");

            if (now >= start && now <= end) {

                card.classList.add("active");

                const percent = ((now - start) / (end - start)) * 100;
                bar.style.width = percent + "%";

                const remain = end - now;
                const m = Math.floor(remain / 60);
                const s = remain % 60;

                cd.innerHTML = "Ends in " + m + "m " + s + "s";

            } else {
                card.classList.remove("active");
                bar.style.width = "0%";
                cd.innerHTML = "";
            }

        });

    }

    setInterval(updatePeriods, 1000);
    updatePeriods();

</script>

<script>

    /* ========= AUTO SCROLL ========= */

    function scrollCurrent() {
        const el = document.querySelector(".period-card.active");
        if (el) {
            el.scrollIntoView({ behavior: "smooth", block: "center" });
        }
    }
    setTimeout(scrollCurrent, 600);



    /* ========= SWIPE DAY CHANGE ========= */

    let touchStartX = 0;

    document.addEventListener("touchstart", e => {
        touchStartX = e.changedTouches[0].screenX;
    });

    document.addEventListener("touchend", e => {
        let diff = e.changedTouches[0].screenX - touchStartX;

        if (Math.abs(diff) < 50) return;

        const days = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        const url = new URL(window.location);
        let cur = url.searchParams.get("day") || 'today';

        let index = days.indexOf(cur);
        if (index == -1) index = days.indexOf(new Date().toLocaleDateString('en-US', { weekday: 'long' }));

        if (diff < 0) index++;
        else index--;

        if (index < 0) index = 0;
        if (index >= days.length) index = days.length - 1;

        url.searchParams.set("day", days[index]);
        window.location = url;

    });



    /* ========= NEXT CLASS ALERT ========= */

    function nextAlert() {

        const now = Math.floor(Date.now() / 1000);
        let next = null;

        document.querySelectorAll(".period-card").forEach(card => {
            const start = parseInt(card.dataset.start);
            if (start > now) {
                if (!next || start < next) next = start;
            }
        });

        if (!next) return;

        const mins = Math.floor((next - now) / 60);

        if (mins === 5) {
            alert("Next class starts in 5 minutes");
        }

    }
    setInterval(nextAlert, 60000);



    /* ========= OFFLINE CACHE ========= */

    function cacheRoutine() {
        localStorage.setItem("routineCache", document.querySelector(".routine-list").innerHTML);
    }

    function loadCache() {
        if (!navigator.onLine) {
            const cache = localStorage.getItem("routineCache");
            if (cache) {
                document.querySelector(".routine-list").innerHTML = cache;
            }
        }
    }

    window.addEventListener("load", () => {
        loadCache();
        setTimeout(cacheRoutine, 1500);
    });

</script>

<script>

    /* ===== MODAL ENGINE ===== */

    function openClassModal() {
        document.getElementById("classModal").style.display = "flex";
    }

    function openDayModal() {
        document.getElementById("dayModal").style.display = "flex";
    }

    function closeModal() {
        document.querySelectorAll(".m3-modal")
            .forEach(m => m.style.display = "none");
    }


    /* ===== NAVIGATION ===== */

    function goClass(cls, sec) {

        const url = new URL(window.location);

        url.searchParams.set("cls", cls);
        url.searchParams.set("sec", sec);

        window.location = url;

    }

    function goDay(day) {

        const url = new URL(window.location);
        url.searchParams.set("day", day);

        window.location = url;

    }


    /* click outside close */

    window.addEventListener("click", e => {
        if (e.target.classList.contains("m3-modal")) {
            closeModal();
        }
    });




    /* ========= SHEET CONTROL ========= */

    function openSheet(id) {
        const el = document.getElementById(id);
        el.classList.add("active");
    }

    function closeSheets() {
        document.querySelectorAll(".m3-sheet")
            .forEach(s => s.classList.remove("active"));
    }

    window.addEventListener("click", e => {
        if (e.target.classList.contains("m3-sheet")) {
            closeSheets();
        }
    });


    /* ========= FAVORITE PIN ========= */

    function pinClass(cls, sec) {
        localStorage.setItem("favClass", cls);
        localStorage.setItem("favSec", sec);
        alert("Pinned ⭐");
    }


    /* ========= AJAX LOAD ========= */




    /* ========= CLICK EVENTS ========= */




    /* ========= AUTO LOAD PIN ========= */





    function adjustBottomSheet() {
        const nav = document.getElementById("bottom-nav-bar");
        if (!nav) return;

        // হাইট ধরার আগে DOM rendering complete হওয়া নিশ্চিত করা
        const h = nav.getBoundingClientRect().height;
        document.documentElement.style.setProperty("--bottom-safe", h + "px");
    }

    // run on load, resize, orientationchange
    window.addEventListener("DOMContentLoaded", adjustBottomSheet);
    window.addEventListener("load", adjustBottomSheet);
    window.addEventListener("resize", adjustBottomSheet);
    window.addEventListener("orientationchange", adjustBottomSheet);



    /* ========= CLASS SHEET ITEM ========= */
    document.querySelectorAll("#classList .sheet-item").forEach(item => {
        item.addEventListener("click", () => {
            const cls = item.dataset.cls;
            const sec = item.dataset.sec;
            if (!cls || !sec) return;

            // পেইজ reload করে রুটিন দেখাবে
            const url = new URL(window.location);
            url.searchParams.set("cls", cls);
            url.searchParams.set("sec", sec);
            url.searchParams.set("day", 'today'); // default today
            window.location = url;
        });
    });

    /* ========= DAY SHEET ITEM ========= */
    document.querySelectorAll("#daySheet .sheet-item").forEach(item => {
        item.addEventListener("click", () => {
            const day = item.dataset.day;
            if (!day) return;

            const cls = document.querySelector("#classList .sheet-item.active")?.dataset.cls || '<?= $classname ?>';
            const sec = document.querySelector("#classList .sheet-item.active")?.dataset.sec || '<?= $sectionname ?>';

            const url = new URL(window.location);
            url.searchParams.set("cls", cls);
            url.searchParams.set("sec", sec);
            url.searchParams.set("day", day);
            window.location = url;
        });
    });


    /* run */

</script>