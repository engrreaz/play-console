<?php

?>
<script>
    var user_level = '<?php echo $userlevel; ?>';

    // Front page block
    //Schedule
    function oneSecondFunction() {



        //********************************** */
        const dx = new Date();
        let timex = dx.getTime();
        document.getElementById("cur-time").innerHTML = timex;


        var d2 = document.getElementById("next-event").innerHTML;
        var d1 = document.getElementById("last-event").innerHTML;
        var date1 = new Date(d1);
        var date2 = new Date(d2);
        var whole_time_line = (date2.getTime() - date1.getTime()) / 1000;
        var rest_time_line = (date2.getTime() - timex) / 1000;
        if (rest_time_line <= 0) {
            window.location.href = 'index.php';
        }
        document.getElementById("ramadan-dur").innerHTML = rest_time_line;
        var rama_rate = 100 - Math.round(100 * rest_time_line / whole_time_line);
        document.getElementById("ramadan-rate").innerHTML = rama_rate;
        document.getElementById("waiting-line").style.width = rama_rate + '%';

        var ram_h = 0, ram_m = 0, ram_s = 0;
        ram_h = Math.floor(rest_time_line / 3600);
        rest_time_line = rest_time_line - ram_h * 3600;
        ram_m = Math.floor(rest_time_line / 60);
        ram_s = Math.floor(rest_time_line - ram_m * 60);

        if (ram_h < 10) ram_h = '0' + ram_h;
        if (ram_m < 10) ram_m = '0' + ram_m;
        if (ram_s < 10) ram_s = '0' + ram_s;
        document.getElementById("wait-time").innerHTML = 'Time Remaining : <b>' + ram_h + ':' + ram_m + ':' + ram_s + '</b>';

        //********************************** */


        var x = document.getElementById("kk").innerHTML;
        x = parseInt(x) - 1;
        var cd = document.getElementById("class-dur").innerHTML;
        cd = parseInt(cd) * 1;

        if (x < 1) {
            if (cd > 0) {
                window.location.href = 'index.php';
            } else {
                document.getElementById("jj").innerHTML = 'Class Routine & Schedule are missing.';
                document.getElementById("class-progress-bar").style.display = 'none';
                document.getElementById("class-bar-val").innerHTML = '';
                document.getElementById("jj").innerHTML += '<br><a href="clsroutine-setup.php" class="text-small text-danger"><b>Click Here </b></a> to setup class schedule and routine.';
            }
        } else {
            var txt = '';
            var d, h, m, s;
            if (x > 3600 * 24) {
                d = Math.floor(x / (3600 * 24));
                s = x - (d * 3600 * 24);
                h = Math.floor(s / 3600);
                s = s - h * 3600;
                m = Math.floor(s / 60);
                s = s - m * 60;

                txt = txt + d + " Days " + h + " Hours " + m + " Min " + s + " Sec.";
            } else if (x > 3600) {
                h = Math.floor(x / 3600);
                s = x - h * 3600;
                m = Math.floor(s / 60);
                s = s - m * 60;
                txt = txt + h + " Hours " + m + " Min " + s + " Sec.";

            } else if (x > 60) {
                m = Math.floor(x / 60);
                s = x - m * 60;
                txt = txt + m + " Min " + s + " Sec.";
            } else {
                txt = txt + s + " Sec.";
            }

            var class_perc = 0;
            if (cd > 0) {
                class_perc = x * 100 / cd;
            }
            document.getElementById("kk").innerHTML = x;
            document.getElementById("jj").innerHTML = '<b>' + txt + '</b> Remaining';
            document.getElementById("class-progress-bar").style.width = class_perc + '%';
            document.getElementById("class-bar-val").innerHTML = class_perc + '%';


            var period = document.getElementById("main-29").innerHTML;
            var times = document.getElementById("main-30").innerHTML;
            var timee = document.getElementById("main-31").innerHTML;
            // document.getElementById("time-slot").innerHTML =  times + ' - ' + timee;
            document.getElementById("time-frame").innerHTML = 'Period : ' + times + ' - ' + timee;
            document.getElementById("period-icon").innerHTML = '<i class="bi bi-' + period + '-circle-fill period-icon"></i>';

        }


        var car_sec_val = parseInt(document.getElementById("career_rest_val").innerHTML);
        var convertsec = convertSeconds(car_sec_val);
        document.getElementById("career_rest").innerHTML = convertsec;
        if (car_sec_val > 0) {
            car_sec_val--;
        }

        document.getElementById("career_rest_val").innerHTML = car_sec_val;

    }


</script>

<script>
    function convertSeconds(seconds) {
        const years = Math.floor(seconds / (365 * 24 * 60 * 60)); // 1 বছর = 365 দিন
        seconds %= (365 * 24 * 60 * 60);

        const months = Math.floor(seconds / (30 * 24 * 60 * 60)); // 1 মাস = ৩০ দিন (গড়)
        seconds %= (30 * 24 * 60 * 60);

        const days = Math.floor(seconds / (24 * 60 * 60)); // 1 দিন = ২৪ ঘণ্টা
        seconds %= (24 * 60 * 60);

        const hours = Math.floor(seconds / (60 * 60)); // 1 ঘণ্টা = ৬০ মিনিট
        seconds %= (60 * 60);

        const minutes = Math.floor(seconds / 60); // 1 মিনিট = ৬০ সেকেন্ড
        seconds %= 60;

        return `${years}Y ${months}M ${days}D ${hours}h ${minutes}m ${seconds}s`;
    }

    // 🔥 উদাহরণ:
</script>


<script>
    // Navigation Functions
    // function report_menu_my_class() { window.location.href = "my-class-report.php"; }
    // function report_menu_student_list() { window.location.href = "student-list.php"; }

    function report_menu_ebooks_x() { window.location.href = "e-books.php"; }
    function report_menu_syllabus() { window.location.href = "syllabus.php"; }
    function report_menu_lesson_plan() { window.location.href = "lesson-plan.php"; }
    function report_menu_examination() { window.location.href = "exam-manager.php"; }
</script>



<script>



    function kbase() {
        window.location.href = 'kbase.php';
    }




    // INDEX 
    function top_bar_issue() {
        window.location.href = '.php';
    }

    function top_bar_kbase() {
        window.location.href = 'kbase.php';
    }

    function top_bar_notification() {
        window.location.href = 'notification.php';
    }

    function top_bar_todo() {
        window.location.href = '.php';
    }

    function top_bar_message() {
        window.location.href = '.php';
    }


    // NAVIGATION...........
    function report_menu_daily_collection() {
        window.location.href = 'dailycollection.php';
    }
    function report_menu_my_class() {
        window.location.href = 'student-list.php';
    }
    function report_menu_student_list() {
        // if (user_level == 'Administrator' || user_level == 'Super Administrator' || user_level == 'Head Teacher' || user_level == 'Principal') {
        window.location.href = 'class-section.php';
        // } else {
        // window.location.href = 'students.php';
        // }
        // window.location.href = 'students.php';
    }
    function class_section_list_for_student_list_edit(id) {
        window.location.href = "students.php?" + id;
    }
    function report_menu_my_collection() {
        window.location.href = 'mypr.php';
    }
    function report_menu_attnd_register() {
        if (user_level == 'Administrator' || user_level == 'Super Administrator' || user_level == 'Head Teacher' || user_level == 'Principal') {
            window.location.href = 'attnd-cls-sec.php';
        } else {
            window.location.href = 'st-attnd-register.php';
        }
    }


    function report_menu_absent_bunk_list() {
        window.location.href = 'absent-bunk-list.php';
    }



    function send_absent_notice(stid, way, mobile) {
        event.stopPropagation();
        alert(stid + '/' + way + '/' + mobile);
        var message = "Dear Guardian,\\nYour child is not in school today.\\nHead Teacher";

        if (way == 0) {

            var lnk = "make-call.php?mobilenumber=" + mobile;
            window.location.href = lnk;

        } else if (way == 1) {
            console.log("hello");
            alert('---1');
            send_sms(mobile, message);

        } else if (way == 2) {

            alert('Under Construction-2.');

        } else if (way == 3) {

            alert('Under Construction-3.');

        } else if (way == 4) {

            alert('Under Construction-4.');

        }
    }

    function report_menu_cls_routine() {
        window.location.href = 'clsroutine.php';
    }
    function report_menu_my_subjects() {
        window.location.href = 'my-subject-list.php';
    }
    function report_menu_honorable_teachers() {
        window.location.href = 'teachers-list.php';
    }
    function report_menu_ebooks() {
        window.location.href = 'e-books.php';
    }
    function report_menu_calendar() {
        window.location.href = 'calendar.php';
    }
    function report_menu_notification() {
        window.location.href = 'notification.php';
    }
    function report_menu_notices() {
        window.location.href = 'notices.php';
    }


    function calendar_event(year, month, day, tail) {
        // alert('Year' + year + month + day + tail);

        var infor = "y=" + year + "&m=" + month + "&d=" + day + "&t=" + tail;
        // 		alert(infor);
        $("#fetch_data").html("");

        $.ajax({
            type: "POST",
            url: "backend/fetch_calendar_data.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                $('#fetch_data').html('');
            },
            success: function (html) {
                $("#fetch_data").html();
                Swal.fire({
                    title: "<small>" + html + "</small>",
                    draggable: true
                });
            }
        });
    }


    function my_class_attendance(stid) {
        Swal.fire({
            title: "<small>Attendance</small>",
            icon: "info",
            draggable: true
        });
    }

    function my_class_payment(stid) {
        Swal.fire({
            title: "<small>Payment's Info</small>",
            icon: "info",
            draggable: true
        });
    }
    function my_class_result(stid) {
        Swal.fire({
            title: "<small>Accademic Performance</small>",
            icon: "info",
            draggable: true
        });
    }
    function my_class_profile(stid) {
        // Swal.fire({
        //     title: "<small>Profile</small>",
        //     icon: "info",
        //     draggable: true
        // });
        window.location.href = 'studentedit.php?id=' + stid;
    }

    // Tools Navigation
    function module_menu_student_attendace(x1, x2) { window.location.href = 'stattnd.php?cls=' + x1 + '&sec=' + x2; }
    function module_menu_leave_app_response() {
        if (user_level == 'Head Teacher' || user_level == 'Asstt. Head Teacher' || user_level == 'Principal' || user_level == 'Vice Principal') {
            window.location.href = 'leave-application-response.php'
        } else {
            Swal.fire({
                title: "<small>You're not eligible to access</small>",
                icon: "info",
                draggable: true
            });
        }

    }

    function module_menu_co_curricular_entry() {
        Swal.fire({
            title: "<small>Currently Unavailable</small>",
            icon: "info",
            draggable: true
        });
    }

    function module_menu_marks_entry() { window.location.href = "markentryselect.php"; }

    function module_menu_class_test() {
        Swal.fire({
            title: "<small>Currently Unavailable</small>",
            icon: "info",
            draggable: true
        });

    }


    function profile_menu_my_attendance() { window.location.href = "tattnd.php"; }
    // function profile_menu_my_attendance_summery() { window.location.href = "my-attnd-teacher-summery.php"; }
    // function profile_menu_leave_application() { window.location.href = "leave-application.php"; }
    function profile_menu_offline_manager() { window.location.href = "offline-manager.php"; }


    // Settings Navigation
  
    function settings_menu_login_method() { window.location.href = "globalsetting.php"; }




    // SETTINGS --  ADMIN MENU
    function settings_admin_add_edit_teacher() { window.location.href = "settingsteacher.php"; }
    function settings_admin_cls_sec() { window.location.href = "settings-class.php"; }
    // function settings_admin_st_id_generate() { window.location.href = "settings-student.php"; }
    function settings_admin_st_id_payment_indivisula() { window.location.href = "st-payment-setup-indivisual.php"; }
    function settings_admin_subject_setup() { window.location.href = "settings-subject.php"; }
    function settings_admin_subject_list_teacher_bind() { window.location.href = "tools_all-subjects.php"; }
    function settings_admin_bind_teacher_subject() { window.location.href = "classes.php"; }
    function settings_admin_class_routine_setup() { window.location.href = "clsroutine-setup.php"; }
    function settings_sms_menu() { window.location.href = "sms-manager.php"; }
    // function settings_admin_user_manager() { window.location.href = "userlist.php"; }


    function sms_manager_send() { window.location.href = "sms-send.php"; }
    function sms_manager_campaign() { window.location.href = "sms-campaign.php"; }
    function sms_manager_history() { window.location.href = "sms-history.php"; }
    function sms_manager_templetes() { window.location.href = "sms-templetes.php"; }








    // Home Page
    function home_goclsatt(x1, x2) { window.location.href = 'stattnd.php?cls=' + x1 + '&sec=' + x2; }
    function home_cteacher_st_payment(x1, x2) { window.location.href = 'finstudents.php?cls=' + x1 + '&sec=' + x2; }
    function gocashbook() { window.location.href = 'bank-book.php'; }










    //*********************************************************************************************** */




    //********************************************************************* */


    function epos(prno = '', e) {
        if (e) e.stopPropagation();
        let uri = "backend/getprinfo.php";
        let lastpr = prno || '';

        $("#eposlink").html("");

        $.ajax({
            type: "POST",
            url: uri,
            data: { prno: lastpr },
            cache: false,
            beforeSend: function () {
                $("#eposlink").html('.....');
            },
            success: function (html) {
                $("#eposlink").html(html);
            }
        });
    }


    function send_sms(number, message) {

        infor = "number=" + number + "&message=" + message;
        alert(infor);
        $("#sms-send-response-block").html("");

        $.ajax({
            type: "POST",
            url: "backend/send-sms.php",
            data: infor,
            cache: false,
            beforeSend: function () {
                $("#sms-send-response-block").html('');
            },
            success: function (html) {
                // $("#sms-send-response-block").html(html);
                Swal.fire({
                    title: html,
                    draggable: true
                });
            }
        });
    }
</script>