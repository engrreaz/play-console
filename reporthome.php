<?php

$page_title = "Information Hub";
include_once 'inc.php';

/* ============================
   MENU CONFIG
============================ */
$categories = [];

$sql = "
SELECT 
    c.id             AS category_id,
    c.name           AS category_name,

    m.id             AS module_id,
    m.title,
    m.icon,
    m.onclick,
    m.active,

    GROUP_CONCAT(DISTINCT p.role) AS roles

FROM hub_categories c

JOIN hub_modules m 
    ON c.id = m.category_id

LEFT JOIN hub_module_permissions p 
    ON p.module_id = m.id
   AND (p.sccode = $sccode OR p.sccode = 0)

WHERE c.status = 1

GROUP BY m.id

ORDER BY c.sort_order, m.sort_order
";

$res = $conn->query($sql);

while ($row = $res->fetch_assoc()) {

    $cat = $row['category_name'];

    if (!isset($categories[$cat])) {
        $categories[$cat] = [];
    }

    // ---- permission logic ----
    if (!empty($row['roles'])) {

        $roles = explode(',', $row['roles']);

        $level = $roles;

    } else {

        // no permission row found = everyone
        $level = 'any';
    }

    $categories[$cat][] = [
        'onclick' => $row['onclick'],
        'icon' => $row['icon'],
        'title' => $row['title'],
        'level' => $level,
        'active' => (bool) $row['active']
    ];
}


/* ============================
   MY CLASS CONDITION
============================ */

$count_class = count($cteacher_data ?? []);

if ($count_class > 0) {

    array_unshift(
        $categories['Academic'],
        [
            'onclick' => 'report_menu_my_class();',
            'icon' => 'bi-microsoft-teams',
            'title' => 'My Class',
            'level' => 'any',
            'active' => true
        ]
    );
}

?>

<style>
    .m3-disabled {
        opacity: .45;
        filter: grayscale(1);
        /* pointer-events: none; */
        background: #f4f4f4 !important;
        pointer-events: none;
        cursor: default;
    }



    .m3-disabled .icon-box {
        background: #ddd !important;
        color: #999 !important;
    }

    .m3-disabled .report-title {
        color: #888 !important;
    }
</style>


<main class="pb-0">

    <?php foreach ($categories as $cat_name => $items): ?>

        <?php
        $visible_count = 0;

        foreach ($items as $item) {

            if (
                $item['level'] === 'any' ||
                (is_array($item['level']) && in_array($userlevel, $item['level']))
            ) {
                $visible_count++;
            }
        }

        if ($visible_count === 0)
            continue;
        ?>

        <div class="m3-category-lbl"><?= $cat_name ?></div>

        <div class="m3-grid">

            <?php foreach ($items as $item): ?>

                <?php
                $is_allowed = (
                    $item['level'] === 'any' ||
                    (is_array($item['level']) && in_array($userlevel, $item['level']))
                );

                if (!$is_allowed)
                    continue;
                $active = $item['active'] ?? true;
                ?>




                <a href="javascript:void(0);" class="m3-report-card shadow-sm <?= !$active ? 'm3-disabled' : '' ?>" <?= $active ? 'onclick="' . $item['onclick'] . '"' : '' ?>>


                    <div class="icon-box" style="margin-right:0;margin-bottom:10px;">
                        <i class="bi <?= $item['icon'] ?>"></i>
                    </div>

                    <span class="report-title"><?= $item['title'] ?></span>

                </a>

            <?php endforeach; ?>

        </div>

    <?php endforeach; ?>

</main>


<?php include 'footer.php'; ?>

<script>
    function report_menu_tattnd_month(month, year) {
        const now = new Date();
        const targetMonth = String(month || (now.getMonth() + 1)).padStart(2, '0'); // 2-digit
        const targetYear = year || now.getFullYear();
        window.location.href = `tattnd-month.php?month=${targetMonth}&year=${targetYear}`;
    }

    function tattnd_tid(tid) {
        window.location.href = `tattnd-tid.php?tid=${tid}`;
    }
    function tattnd_manager() {
        window.location.href = `tattnd-manager.php`;
    }
</script>