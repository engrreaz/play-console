<?php 
$page_title = "System Protocols"; 
include 'inc.php'; 

// ডাটা ফেচিং
$sql = "SELECT geolat, geolon, dista_differ, intime, outtime, time_differ, theme FROM scinfo WHERE sccode = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $sccode);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
?>

<style>
    /* Protocol Hero - Slate/Carbon Gradient */
    .m3-hero-proto {
        background: linear-gradient(180deg, #455A64 0%, #263238 100%);
        padding: 50px 24px 80px; color: #fff; border-radius: 0 0 40px 40px; text-align: center;
    }
    .proto-container { margin-top: -60px; padding: 0 16px 50px; position: relative; z-index: 10; }
    .m3-card { background: #fff; border-radius: 28px; padding: 24px; border: 1px solid #E7E0EC; margin-bottom: 16px; }
    
    /* Layout Helpers */
    .proto-label { font-size: 0.65rem; color: #79747E; font-weight: 800; text-transform: uppercase; display: block; margin-bottom: 4px; }
    .proto-val { font-size: 1rem; color: #1C1B1F; font-weight: 700; }
</style>

<main>
    <div class="m3-hero-proto shadow">
        <h3 class="fw-black mb-1">System Protocols</h3>
        <p class="small opacity-75 fw-bold mb-0">Thresholds, GPS Fencing & UI Preferences</p>
    </div>

    <div class="proto-container">
        <div class="m3-card shadow-sm">
            <h6 class="fw-black text-primary mb-3"><i class="bi bi-geo-alt-fill me-2"></i>Geo-Fencing Setup</h6>
            <div class="row g-3">
                <div class="col-6">
                    <span class="proto-label">Latitude</span>
                    <span class="proto-val"><?= $row['geolat'] ?></span>
                </div>
                <div class="col-6">
                    <span class="proto-label">Longitude</span>
                    <span class="proto-val"><?= $row['geolon'] ?></span>
                </div>
                <div class="col-12">
                    <span class="proto-label">Fence Radius</span>
                    <span class="proto-val text-success"><?= $row['dista_differ'] ?> Meters</span>
                </div>
            </div>
        </div>

        <div class="m3-card shadow-sm">
            <h6 class="fw-black text-warning mb-3"><i class="bi bi-clock-history me-2"></i>Attendance Timings</h6>
            <div class="row g-3">
                <div class="col-4 border-end">
                    <span class="proto-label">In-Time</span>
                    <span class="proto-val text-dark"><?= date('h:i A', strtotime($row['intime'])) ?></span>
                </div>
                <div class="col-4 border-end">
                    <span class="proto-label">Out-Time</span>
                    <span class="proto-val text-dark"><?= date('h:i A', strtotime($row['outtime'])) ?></span>
                </div>
                <div class="col-4">
                    <span class="proto-label">Buffer</span>
                    <span class="proto-val text-danger"><?= $row['time_differ'] ?>s</span>
                </div>
            </div>
        </div>

        <div class="m3-card shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-black text-info m-0"><i class="bi bi-palette-fill me-2"></i>System Theme</h6>
                    <span class="small text-muted fw-bold">Active: <?= strtoupper($row['theme']) ?></span>
                </div>
                <div class="icon-box-m3 bg-info-subtle text-info rounded-circle" style="width:44px; height:44px; display:flex; align-items:center; justify-content:center;">
                    <i class="bi <?= $row['theme'] == 'Dark' ? 'bi-moon-stars-fill' : 'bi-sun-fill' ?>"></i>
                </div>
            </div>
        </div>

        <button class="btn btn-dark w-100 rounded-pill py-3 fw-black shadow mt-2" data-bs-toggle="modal" data-bs-target="#protoEditModal">
            <i class="bi bi-sliders me-2"></i>RE-CONFIGURE PROTOCOLS
        </button>
    </div>
</main>

<div class="modal fade" id="protoEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-5 shadow-lg">
            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <h5 class="fw-black text-dark">Update Protocols</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="protoForm" class="modal-body p-4">
                
                <p class="small fw-bold text-primary mb-3 mt-0">GPS COORDINATES & RADIUS</p>
                <div class="row g-2">
                    <div class="col-6"><div class="m3-input-box mb-3"><label>LATITUDE</label><input type="text" name="geolat" value="<?= $row['geolat'] ?>" class="m3-clean-input"></div></div>
                    <div class="col-6"><div class="m3-input-box mb-3"><label>LONGITUDE</label><input type="text" name="geolon" value="<?= $row['geolon'] ?>" class="m3-clean-input"></div></div>
                </div>
                <div class="m3-input-box mb-3"><label>RADIUS DIFFERENCE (METERS)</label><input type="number" name="dista_differ" value="<?= $row['dista_differ'] ?>" class="m3-clean-input"></div>

                <p class="small fw-bold text-warning mb-3 mt-4">OFFICE HOURS & SYNC BUFFER</p>
                <div class="row g-2">
                    <div class="col-6"><div class="m3-input-box mb-3"><label>IN-TIME</label><input type="time" name="intime" value="<?= $row['intime'] ?>" class="m3-clean-input"></div></div>
                    <div class="col-6"><div class="m3-input-box mb-3"><label>OUT-TIME</label><input type="time" name="outtime" value="<?= $row['outtime'] ?>" class="m3-clean-input"></div></div>
                </div>
                <div class="m3-input-box mb-3"><label>TIME BUFFER (SECONDS)</label><input type="number" name="time_differ" value="<?= $row['time_differ'] ?>" class="m3-clean-input"></div>

                <p class="small fw-bold text-info mb-3 mt-4">UI ENVIRONMENT</p>
                <div class="m3-input-box mb-4">
                    <label>DEFAULT THEME</label>
                    <select name="theme" class="m3-clean-input border-0 bg-transparent">
                        <option value="Light" <?= $row['theme'] == 'Light' ? 'selected' : '' ?>>Light Mode (Clean & Bright)</option>
                        <option value="Dark" <?= $row['theme'] == 'Dark' ? 'selected' : '' ?>>Dark Mode (Elegant & Focused)</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-dark w-100 rounded-pill py-3 fw-black shadow">
                    <i class="bi bi-check2-circle me-2"></i>SYNC ALL PROTOCOLS
                </button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
    $('#protoForm').on('submit', function(e) {
        e.preventDefault();
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<div class="spinner-border spinner-border-sm me-2"></div> UPDATING...');

        $.ajax({
            url: 'backend/save-institute-info.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if(res.status == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Protocols Synced',
                        text: 'System core settings updated.',
                        timer: 1500,
                        showConfirmButton: false,
                        border_radius: '28px'
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message, 'error');
                    submitBtn.prop('disabled', false).html('<i class="bi bi-check2-circle me-2"></i>SYNC ALL PROTOCOLS');
                }
            },
            error: function() {
                Swal.fire('Connection Error', 'Failed to reach server.', 'error');
                submitBtn.prop('disabled', false).text('SYNC ALL PROTOCOLS');
            }
        }, 'json');
    });
</script>