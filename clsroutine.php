<?php
include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে


// ২. ফিল্টার হ্যান্ডলিং
$classname = $_GET['cls'] ?? ($cteacher_data[0]['cteachercls'] ?? '');
$sectionname = $_GET['sec'] ?? ($cteacher_data[0]['cteachersec'] ?? '');
$page_title = "Class Routine";

// ৩. রুটিন ডাটা ফেচিং (অপরিবর্তিত লজিক)
$routine_data = [];
$days_order = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

$sql = "SELECT * FROM classschedule 
        WHERE sccode = ? AND sessionyear LIKE ? 
        ORDER BY period ";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $sccode, $sessionyear_param);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $routine_data[$row['dayname']][] = $row;
}
$stmt->close();
?>

<style>
    /* Routine Specific M3 Styling */
    .time-strip {
        width: 80px;
        text-align: center;
        border-right: 2px solid var(--m3-tonal-container);
        margin-right: 16px;
        flex-shrink: 0;
    }

    .time-main {
        font-size: 0.9rem;
        font-weight: 900;
        color: var(--m3-primary);
        display: block;
        line-height: 1.1;
    }

    .time-sub {
        font-size: 0.65rem;
        color: #777;
        font-weight: 700;
        text-transform: uppercase;
    }

    .period-pill {
        background: var(--m3-tonal-container);
        color: var(--m3-on-tonal-container);
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 800;
        white-space: nowrap;
    }

    .day-separator {
        font-size: 0.75rem;
        font-weight: 800;
        color: var(--m3-primary);
        margin: 20px 16px 10px;
        letter-spacing: 1.2px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .day-separator::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--m3-tonal-container);
    }
</style>

<main>
    <div class="hero-container">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div class="tonal-icon-btn" style="background: rgba(255,255,255,0.2); color: #fff; border:none;"
                    onclick="history.back()">
                    <i class="bi bi-arrow-left"></i>
                </div>
                <div>
                    <div style="font-size: 1.5rem; font-weight: 900; line-height: 1.1;"><?php echo $page_title; ?></div>
                    <div style="font-size: 0.8rem; opacity: 0.9; font-weight: 600;">Weekly Academic Schedule</div>
                </div>
            </div>
            <div style="text-align: right;">
                <span class="session-pill" style="background: rgba(255,255,255,0.15); color: #fff; border: none;">
                    <?php echo $sy; ?>
                </span>
            </div>
        </div>
    </div>

    <div class="m3-card" style="margin-top: -20px; position: relative; z-index: 10; padding: 16px 12px 0;">
        <form method="GET" class="row gx-2">
            <div class="col-5">
                <div class="m3-floating-group">
                    <select name="cls" class="m3-select-floating" onchange="this.form.submit()">
                        <?php foreach ($cteacher_data as $c): ?>
                            <option value="<?php echo $c['cteachercls']; ?>" <?php echo ($c['cteachercls'] == $classname) ? 'selected' : ''; ?>><?php echo $c['cteachercls']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label class="m3-floating-label">CLASS</label>
                </div>
            </div>
            <div class="col-4">
                <div class="m3-floating-group">
                    <label class="m3-floating-label">SECTION</label>
                    <i class="bi bi-book m3-field-icon"></i>
                    <select name="sec" class="m3-select-floating" onchange="this.form.submit()">
                        <?php foreach ($cteacher_data as $c): ?>
                            <option value="<?php echo $c['cteachersec']; ?>" <?php echo ($c['cteachersec'] == $sectionname) ? 'selected' : ''; ?>><?php echo $c['cteachersec']; ?></option>
                        <?php endforeach; ?>
                    </select>

                </div>
            </div>
            <div class="col-3">
                <button type="submit" class="btn-m3-submit" style="height: 48px; margin: 0; width: 100%;">
                    <i class="bi bi-search"></i>
                </button>
            </div>
            <input type="hidden" name="year" value="<?php echo $current_session; ?>">
        </form>
    </div>

    <div class="routine-list" style="padding-bottom: 30px;">
        <?php if (empty($routine_data)): ?>
            <div style="text-align: center; padding: 60px 20px; opacity: 0.4;">
                <i class="bi bi-calendar-x" style="font-size: 3.5rem;"></i>
                <div style="font-weight: 800; margin-top: 10px;">Routine Not Found</div>
                <div style="font-size: 0.75rem;">Please select another class or session.</div>
            </div>
        <?php else: ?>
            <?php foreach ($days_order as $day):
                if (!isset($routine_data[$day]))
                    continue;
                ?>
                <div class="day-separator">
                    <i class="bi bi-calendar-event"></i> <?php echo strtoupper($day); ?>
                </div>

                <div class="widget-grid">
                    <?php foreach ($routine_data[$day] as $p):
                        $time_s = date('h:i', strtotime($p['timestart']));
                        $time_ampm = date('A', strtotime($p['timestart']));
                        ?>
                        <div class="m3-list-item" style="padding: 14px; margin-bottom: 8px;">
                            <div class="time-strip">
                                <span class="time-main"><?php echo $time_s; ?></span>
                                <span class="time-sub"><?php echo $time_ampm; ?></span>
                            </div>

                            <div class="item-info">
                                <div class="st-title" style="font-size: 1rem; color: #1C1B1F;"><?php echo $p['subject']; ?></div>
                                <div class="st-desc" style="display: flex; align-items: center; gap: 5px; font-weight: 600;">
                                    <i class="bi bi-person-badge" style="color: var(--m3-primary);"></i>
                                    <?php echo $p['teachername'] ?: 'No Teacher Assigned'; ?>
                                </div>
                            </div>

                            <div class="period-pill">
                                P-<?php echo $p['period']; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>


<!-- <div style="height: 80px;"></div> -->

<?php include 'footer.php'; ?>



### ডিজাইনের প্রধান পরিবর্তনগুলো:
1. **স্মার্ট হিরো এরিয়া:** পেজ টাইটেল এবং সেশন ইনফোকে একটি মডার্ন গ্রাডিয়েন্ট হেডার বক্সে নিয়ে আসা হয়েছে।
2. **ফ্লোটিং ফিল্টার:** ক্লাস এবং সেকশন ড্রপডাউনগুলোতে আমাদের **M3 Floating Label** সিস্টেম ইন্টিগ্রেট করা হয়েছে।
3. **টাইম-স্ট্রিপ লেআউট:** প্রতিটি পিরিয়ডের সময়কে একটি ডেডিকেটেড বাম পাশের কলামে রাখা হয়েছে, যা রুটিন স্ক্যান করা সহজ
করে।
4. **ডে সেপারেটর:** সপ্তাহের প্রতিটি দিনের জন্য একটি স্টাইলিশ `day-separator` যোগ করা হয়েছে।
5. **পিরিয়ড ব্যাজ:** পিরিয়ড নম্বরটিকে ডানপাশে একটি টোনাল পিল (Pill) শেপে রাখা হয়েছে যা দেখতে প্রিমিয়াম লাগে।

এই কোডটি সরাসরি আপনার ফাইলে পেস্ট করতে পারেন। আপনার আগের সব জাভাস্ক্রিপ্ট বা ডাটাবেজ লজিকের সাথে এটি সামঞ্জস্যপূর্ণ।

**পরবর্তীতে কি আমরা এই রুটিনের জন্য কোনো 'Teacher Wise View' বা 'Current Period Highlight' ফিচার যোগ করব?**