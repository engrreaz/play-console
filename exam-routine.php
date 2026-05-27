<?php
$page_title = "Exam Routine";
include 'inc.php';

$current_session = $_GET['year'] ?? $_GET['y'] ?? $_COOKIE['query-session'] ?? $sy;

$slot        = $_COOKIE['chain-slot'] ?? 'School';
$session     = $_COOKIE['chain-session'] ?? $current_session;
$classname   = $_COOKIE['chain-class'] ?? '';
$sectionname = $_COOKIE['chain-section'] ?? '';
$exam        = $_COOKIE['chain-exam'] ?? '';
?>

<style>
    body{
        background:#FEF7FF;
    }

    .m3-section-label{
        font-size: .72rem;
        font-weight: 800;
        text-transform: uppercase;
        color:#6750A4;
        margin:20px 16px 10px;
        letter-spacing: .8px;
    }

    .m3-routine-shell{
        padding:0 10px 80px;
    }

    .m3-routine-card{
        background:#fff;
        border-radius:16px;
        padding:14px;
        margin-bottom:12px;
        border:1px solid #EADDFF;
        box-shadow:0 2px 6px rgba(0,0,0,.05);
        animation:fadeIn .25s ease;
    }

    .m3-date-chip{
        background:#EADDFF;
        color:#21005D;
        border-radius:999px;
        padding:6px 12px;
        display:inline-flex;
        align-items:center;
        gap:6px;
        font-size:.72rem;
        font-weight:700;
    }

    .m3-subject-title{
        font-size:1rem;
        font-weight:800;
        color:#1D192B;
        margin-top:10px;
        line-height:1.2;
    }

    .m3-subcode{
        font-size:.75rem;
        color:#6750A4;
        font-weight:700;
    }

    .m3-meta-row{
        margin-top:10px;
        display:flex;
        flex-wrap:wrap;
        gap:8px;
    }

    .m3-meta-pill{
        background:#F6EDFF;
        border:1px solid #E8DEF8;
        color:#49454F;
        border-radius:8px;
        padding:6px 10px;
        font-size:.72rem;
        font-weight:600;
    }

    .m3-empty-state{
        background:#fff;
        border:1px dashed #D0BCFF;
        border-radius:16px;
        padding:50px 20px;
        text-align:center;
        margin:20px 10px;
    }

    .m3-empty-state i{
        font-size:60px;
        color:#B69DF8;
    }

    .m3-loading{
        text-align:center;
        padding:60px 20px;
    }

    @keyframes fadeIn{
        from{
            opacity:0;
            transform:translateY(10px);
        }
        to{
            opacity:1;
            transform:translateY(0);
        }
    }
</style>

<main class="pb-5 mt-2">

    <div class="selection-card shadow-sm">
        <?php
        $chain_param = '-c 4 -t Choose Parameters -u  -b View Routine -h exam';
        include 'component/tree-ui.php';
        ?>
    </div>

    <div class="m3-section-label">
        Examination Routine
    </div>

    <div id="routine-block" class="m3-routine-shell">

        <div class="m3-empty-state">
            <i class="bi bi-calendar2-week"></i>

            <div class="fw-bold mt-3">
                Select Parameters
            </div>

            <div class="small text-muted mt-1">
                Choose class, section and exam to load routine
            </div>
        </div>

    </div>

</main>

<?php include 'footer.php'; ?>

<script>

    function loadExamRoutine(){

        const slot        = $('#slot-main').val();
        const session     = $('#session-main').val();
        const classname   = $('#class-main').val();
        const sectionname = $('#section-main').val();
        const exam        = $('#exam-main').val();

        if(!classname || !exam){

            $('#routine-block').html(`

                <div class="m3-empty-state">
                    <i class="bi bi-ui-checks-grid"></i>

                    <div class="fw-bold mt-3">
                        Missing Parameters
                    </div>

                    <div class="small text-muted mt-1">
                        Please select exam and class
                    </div>
                </div>

            `);

            return;
        }

        $.ajax({

            type:'POST',

            url:'exam/get_exam_routine.php',

            data:{
                slot,
                session,
                classname,
                sectionname,
                exam
            },

            beforeSend:function(){

                $('#routine-block').html(`

                    <div class="m3-loading">

                        <div class="spinner-border text-primary"></div>

                        <div class="fw-bold mt-3">
                            Loading Routine...
                        </div>

                    </div>

                `);

            },

            success:function(res){

                $('#routine-block')
                    .hide()
                    .html(res)
                    .fadeIn(200);

            }

        });

    }

    function btn_chain_function(){

        loadExamRoutine();

    }

    $(document).ready(function(){

        loadExamRoutine();

    });

</script>

</body>
</html>