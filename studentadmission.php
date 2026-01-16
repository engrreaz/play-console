<?php 
    include 'inc.php'; // header.php এবং DB কানেকশন লোড করবে
    
    $stid = $_GET['id'] ?? 0;
    
    // ফটো পাথ লজিক
    $pth = '../students/' . $stid . '.jpg';
    if(file_exists($pth)){
        $pth = 'https://eimbox.com/students/' . $stid . '.jpg';
    } else {
        $pth = 'https://eimbox.com/students/noimg.jpg';
    }
?>

<style>
    body { background-color: #FEF7FF; } /* M3 Surface Background */

    /* Profile Header Styling */
    .profile-header {
        background: linear-gradient(135deg, #6750A4, #9581CD);
        border-radius: 0 0 32px 32px;
        padding: 40px 20px 60px;
        position: relative;
        color: white;
        text-align: center;
        margin-bottom: 50px;
    }
    
    .profile-pic-container {
        width: 120px; height: 120px;
        border-radius: 50%;
        border: 4px solid #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        position: absolute;
        bottom: -40px;
        left: 50%;
        transform: translateX(-50%);
        background: white;
        overflow: hidden;
    }
    .profile-pic-container img { width: 100%; height: 100%; object-fit: cover; }

    /* Card Styling */
    .m3-card {
        background: white;
        border-radius: 28px;
        border: none;
        padding: 20px;
        margin-bottom: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    /* M3 Input Styles */
    .form-floating > .form-control, .form-floating > .form-select {
        border-radius: 12px;
        border: 1px solid #79747E;
        background: transparent;
    }
    .form-floating > .form-control:focus { border-color: #6750A4; box-shadow: 0 0 0 1px #6750A4; }

    .section-title {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #6750A4;
        margin: 20px 0 10px 10px;
        letter-spacing: 1px;
    }

    /* Recent Admission List Items */
    .adm-item {
        border-radius: 16px;
        padding: 12px;
        margin-bottom: 8px;
        border: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>

<main class="container-fluid p-0 pb-5">
    
    <div class="profile-header shadow">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="index.php" class="text-white"><i class="bi bi-arrow-left fs-4"></i></a>
            <h5 class="fw-bold mb-0">Admission Portal</h5>
            <i class="bi bi-three-dots-vertical fs-5"></i>
        </div>
        
        <div class="profile-pic-container">
            <img src="<?php echo $pth; ?>" onerror="this.src='https://eimbox.com/students/noimg.jpg'">
        </div>
    </div>

    <div class="container px-3">
        <?php
        // ১. স্টুডেন্ট ডাটা ফেচিং (Prepared Statement)
        $stnameeng = $stnameben = $fname = $mname = $guarmobile = $previll = $prepo = $preps = $predist = "";
        $religion = $gender = $admclass = $taka = $preins = "";
        $roll = $cls = $sec = "N/A";

        if ($stid > 0) {
            $stmt = $conn->prepare("SELECT * FROM admission WHERE stid = ? LIMIT 1");
            $stmt->bind_param("s", $stid);
            $stmt->execute();
            $row0 = $stmt->get_result()->fetch_assoc();
            if ($row0) {
                $stnameeng = $row0["stnameeng"]; $stnameben = $row0["stnameben"];
                $fname = $row0["fname"]; $mname = $row0["mname"];
                $guarmobile = $row0["guarmobile"];
                $previll = $row0["previll"]; $prepo = $row0["prepo"]; $preps = $row0["preps"]; $predist = $row0["predist"];
                $religion = $row0["religion"]; $gender = $row0["gender"]; $admclass = $row0["admclass"]; 
                $taka = $row0["openingfee"]; $preins = $row0["preins"];
            }
            $stmt->close();

            $stmt_sess = $conn->prepare("SELECT rollno, classname, sectionname FROM sessioninfo WHERE stid = ? AND sessionyear LIKE ? AND sccode = ? LIMIT 1");
            $sy_param = "%$sy%";
            $stmt_sess->bind_param("sss", $stid, $sy_param, $sccode);
            $stmt_sess->execute();
            $row_sess = $stmt_sess->get_result()->fetch_assoc();
            if ($row_sess) {
                $roll = $row_sess["rollno"]; $cls = $row_sess["classname"]; $sec = $row_sess["sectionname"];
            }
            $stmt_sess->close();
        }
        ?>

        <?php if($stid > 0): ?>
        <div class="m3-card shadow-sm text-center">
            <h5 class="fw-bold mb-1 text-dark"><?php echo $stnameeng; ?></h5>
            <div class="small text-muted">ID: <span class="fw-bold"><?php echo $stid; ?></span> | Roll: <span class="fw-bold"><?php echo $roll; ?></span></div>
            <div class="badge rounded-pill bg-primary-subtle text-primary px-3 mt-2">Class: <?php echo $cls; ?> (<?php echo $sec; ?>)</div>
        </div>
        <?php endif; ?>

        <form id="admissionForm">
            <div class="section-title">Academic Details</div>
            <div class="m3-card shadow-sm">
                <div class="form-floating mb-3">
                    <select class="form-select" id="admcls">
                        <option value="">Choose Class</option>
                        <?php foreach(['Six', 'Seven', 'Eight', 'Nine', 'XI'] as $c): ?>
                            <option value="<?php echo $c; ?>" <?php if($admclass==$c) echo 'selected';?>><?php echo $c; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label for="admcls">Admission Class</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" id="preins" class="form-control" placeholder="Inst" value="<?php echo $preins; ?>">
                    <label for="preins">Previous Institution</label>
                </div>
            </div>

            <div class="section-title">Personal Information</div>
            <div class="m3-card shadow-sm">
                <div class="form-floating mb-3">
                    <input type="text" id="nameeng" class="form-control" placeholder="Name Eng" value="<?php echo $stnameeng; ?>">
                    <label for="nameeng">Student Name (English)</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" id="nameben" class="form-control" placeholder="Name Ben" value="<?php echo $stnameben; ?>">
                    <label for="nameben">ছাত্র/ছাত্রীর নাম (বাংলা)</label>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="form-floating">
                            <select class="form-select" id="reli">
                                <?php foreach(['Islam', 'Hindu', 'Christian', 'Buddist'] as $r): ?>
                                    <option value="<?php echo $r; ?>" <?php if($religion==$r) echo 'selected';?>><?php echo $r; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label for="reli">Religion</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-floating">
                            <select class="form-select" id="gen">
                                <option value="Boy" <?php if($gender=='Boy') echo 'selected';?>>Boy</option>
                                <option value="Girl" <?php if($gender=='Girl') echo 'selected';?>>Girl</option>
                            </select>
                            <label for="gen">Gender</label>
                        </div>
                    </div>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" id="fname" class="form-control" placeholder="Father" value="<?php echo $fname; ?>">
                    <label for="fname">Father's Name</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" id="mname" class="form-control" placeholder="Mother" value="<?php echo $mname; ?>">
                    <label for="mname">Mother's Name</label>
                </div>
            </div>

            <div class="section-title">Contact & Address</div>
            <div class="m3-card shadow-sm">
                <div class="form-floating mb-3">
                    <input type="tel" id="mno" class="form-control" placeholder="Mobile" value="<?php echo $guarmobile; ?>">
                    <label for="mno">Guardian Mobile Number</label>
                </div>
                
                <div class="row g-2">
                    <div class="col-6">
                        <div class="form-floating mb-3">
                            <input type="text" id="vill" class="form-control" placeholder="Vill" value="<?php echo $previll; ?>" list="villList">
                            <label for="vill">Village</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-floating mb-3">
                            <input type="text" id="po" class="form-control" placeholder="PO" value="<?php echo $prepo; ?>">
                            <label for="po">Post Office</label>
                        </div>
                    </div>
                </div>
                
                <div class="row g-2">
                    <div class="col-6">
                        <div class="form-floating mb-3">
                            <input type="text" id="ps" class="form-control" placeholder="Upazila" value="<?php echo $preps; ?>">
                            <label for="ps">Upazila</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-floating mb-3">
                            <input type="text" id="dist" class="form-control" placeholder="Dist" value="<?php echo $predist; ?>">
                            <label for="dist">District</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-title">Finance</div>
            <div class="m3-card shadow-sm">
                <div class="form-floating mb-3">
                    <input type="number" id="taka" class="form-control" placeholder="Fee" value="<?php echo $taka; ?>">
                    <label for="taka">Opening Fee / Admission Fee</label>
                </div>
            </div>

            <div class="px-2 mt-4">
                <button type="button" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow" onclick="upd();">
                    <i class="bi bi-cloud-arrow-up-fill me-2"></i> UPDATE ADMISSION INFO
                </button>
                <div id="px" class="text-center mt-3 small fw-bold text-primary"></div>
            </div>
        </form>

        <div class="section-title mt-5">Recent Admissions</div>
        <div class="m3-card shadow-sm px-0">
            <div class="p-3 border-bottom bg-light">
                <small class="fw-bold text-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i> Wrong entries can be removed within 1 hour.</small>
            </div>
            <div class="list-group list-group-flush">
                <?php
                $ekhon = date('Y-m-d H:i:s');
                $stmt_rec = $conn->prepare("SELECT * FROM admission WHERE sccode = ? ORDER BY id DESC LIMIT 10");
                $stmt_rec->bind_param("s", $sccode);
                $stmt_rec->execute();
                $result_rec = $stmt_rec->get_result();

                while($row = $result_rec->fetch_assoc()):
                    $can_delete = ($row['admby'] == $usr && (strtotime($ekhon) - strtotime($row['admtime'])) < 3600);
                    $item_clr = $can_delete ? '#E8F5E9' : '#FFFFFF';
                ?>
                <div class="p-3 border-bottom" style="background-color: <?php echo $item_clr; ?>;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-bold text-dark"><?php echo $row['stnameeng']; ?></div>
                            <div class="small text-muted"><?php echo $row['previll']; ?> | <?php echo $row['guarmobile']; ?></div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-primary">৳<?php echo $row['openingfee']; ?></div>
                            <div class="small text-muted" style="font-size: 0.65rem;"><?php echo date('h:i A', strtotime($row['admtime'])); ?></div>
                        </div>
                    </div>
                    <?php if($can_delete): ?>
                    <div id="jav<?php echo $row['stid'];?>" class="mt-2 text-end">
                        <button class="btn btn-danger btn-sm rounded-pill px-3" onclick="delst(<?php echo $row['stid'];?>);">
                            <i class="bi bi-trash3 me-1"></i> Delete Entry
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endwhile; $stmt_rec->close(); ?>
            </div>
        </div>
    </div>
</main>

<div style="height: 70px;"></div>

<script>
    function upd() {
        const formData = {
            stid: '<?php echo $stid;?>',
            nameeng: document.getElementById("nameeng").value,
            nameben: document.getElementById("nameben").value,
            fname: document.getElementById("fname").value,
            mname: document.getElementById("mname").value,
            vill: document.getElementById("vill").value,
            po: document.getElementById("po").value,
            ps: document.getElementById("ps").value,
            dist: document.getElementById("dist").value,
            mno: document.getElementById("mno").value,
            reli: document.getElementById("reli").value,
            gen: document.getElementById("gen").value,
            admcls: document.getElementById("admcls").value,
            preins: document.getElementById("preins").value,
            taka: document.getElementById("taka").value
        };

        $.ajax({
            type: "POST",
            url: "saveadmission.php",
            data: formData,
            beforeSend: function () { $('#px').html('<div class="spinner-border spinner-border-sm me-1"></div> Updating...'); },
            success: function(html) {
                Swal.fire({ title: 'Success!', text: 'Admission data updated.', icon: 'success', confirmButtonColor: '#6750A4' })
                .then(() => { window.location.href = 'studentadmission.php'; });
            }
        });
    }

    function delst(id) {
        Swal.fire({
            title: 'Delete Entry?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#B3261E',
            confirmButtonText: 'Yes, Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "deladmission.php",
                    data: { stid: id },
                    success: function(html) {
                        location.reload();
                    }
                });
            }
        });
    }
</script>

<?php include 'footer.php'; ?>