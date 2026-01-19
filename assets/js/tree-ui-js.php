<script>
    $(function () {
        let isAutoLoad = true; // 🔐 only for first load
        /* ===============================
           1️⃣ Cookie → Select value set
           =============================== */
        const cookieMap = {
            '#slot-main': <?= json_encode($_COOKIE['chain-slot'] ?? '') ?>,
            '#session-main': <?= json_encode($_COOKIE['chain-session'] ?? '') ?>,
            '#exam-main': <?= json_encode($_COOKIE['chain-exam'] ?? '') ?>,
            '#class-main': <?= json_encode($_COOKIE['chain-class'] ?? '') ?>,
            '#section-main': <?= json_encode($_COOKIE['chain-section'] ?? '') ?>,
            '#subject-main': <?= json_encode($_COOKIE['chain-subject'] ?? '') ?>,
            '#date-from-main': <?= json_encode($_COOKIE['chain-date-from'] ?? '') ?>,
            '#date-to-main': <?= json_encode($_COOKIE['chain-date-to'] ?? '') ?>
        };

        $.each(cookieMap, function (selector, value) {
            if ($(selector).length && value) {
                $(selector).val(value);
            }
        });

        /* ===============================
           2️⃣ Session → Class
           =============================== */
        $('#session-main').on('change', function () {
            let slot = $('#slot-main').val();
            let session = $(this).val();
            if (!session) return;


            // Class (existing)
            $('#class-main').html('<option value="">Loading...</option>');
            $('#section-main').html('<option value="">Select class first</option>');

            $.post('component/get-class.php', { slot: slot, session: session }, function (res) {
                $('#class-main').html(res);

                if (isAutoLoad && cookieMap['#class-main']) {
                    $('#class-main').val(cookieMap['#class-main']).trigger('change');
                }
            });

            // 🔥 NEW : Exam load
            $('#exam-main').html('<option value="">Loading...</option>');
            $.post('component/get-exam.php', { slot, session }, function (res) {
                $('#exam-main').html(res);

                if (isAutoLoad && cookieMap['#exam-main']) {
                    $('#exam-main').val(cookieMap['#exam-main']).trigger('change');
                }
            });

            document.cookie = "chain-session=" + session + "; path=/";
        });


        /* ===============================
           3️⃣ Class → Section
           =============================== */
        $('#class-main').on('change click', function () {

            let slot = $('#slot-main').val();
            let session = $('#session-main').val();
            let cls = $(this).val();
            if (!cls) return;

            $('#section-main').html('<option value="">Loading...</option>');

            $.post('component/get-section.php', { slot, session, cls }, function (res) {

                $('#section-main').html(res);

                if (isAutoLoad && cookieMap['#section-main']) {
                    setTimeout(function () {
                        console.log('Auto loading section from cookie');
                        $('#section-main').val(cookieMap['#section-main']).trigger('change');
                        $('#section-main').val(cookieMap['#section-main']).trigger('click');
                    }, 50); // 🔑 small delay
                }
            });


            // 🔄 update cookie on manual change
            document.cookie = "chain-class=" + cls + "; path=/";
        });

        /* ===============================
           4️⃣ Section change → cookie save
           =============================== */
        $('#section-main').on('change click', function () {
            let section = $(this).val();
            if (!section) return;

            $('#subject-main').html('<option value="">Loading...</option>');

            $.post('component/get-subject.php', {
                session: $('#session-main').val(),
                cls: $('#class-main').val(),
                sec: section,
                slot: $('#slot-main').val()
            }, function (res) {

                $('#subject-main').html(res);

                if (isAutoLoad && cookieMap['#subject-main']) {
                    setTimeout(function () {
                        $('#subject-main').val(cookieMap['#subject-main']);
                    }, 50);
                }

                isAutoLoad = false; // 🔓 only after subject done
            });
        });



        $('#session-main').on('change', function () {
            $('#class-main').trigger('click');
            document.cookie = "chain-session=" + $(this).val() + "; path=/";
        });

        $('#slot-main').on('change', function () {
            document.cookie = "chain-slot=" + $(this).val() + "; path=/";
        });

        $('#exam-main').on('change', function () {
            document.cookie = "chain-exam=" + $(this).val() + "; path=/";
        });

        $('#subject-main').on('change', function () {
            document.cookie = "chain-subject=" + $(this).val() + "; path=/";
        });

        $('#date-from-main').on('change', function () {
            document.cookie = "chain-date-from=" + $(this).val() + "; path=/";
        });

        $('#date-to-main').on('change', function () {
            document.cookie = "chain-date-to=" + $(this).val() + "; path=/";
        });

        /* ===============================
           5️⃣ Auto trigger only once
           =============================== */
        if ($('#session-main').val()) {
            $('#session-main').trigger('change');
        }

    });
</script>


<script>
    $(document).ready(function () {

        let nodeTreeBlock = document.getElementById('nodeTreeModal');

        if (nodeTreeBlock) {
            let modal = new bootstrap.Modal(nodeTreeBlock);

            $('#openTree').on('click', function () {
                $('#treeRoot').html('');
                modal.show();
                loadNodes('slot', {}, $('#treeRoot'));
            });

        } else {
            console.log('nodeTreeModal এখনো DOM এ নাই');
        }

    });

</script>

<script>





    function loadNodes(type, context, container) {



        let chainInput = $('#chainInput').val();

        $.post('component/node-tree.php', {
            type: type,
            context: context
        }, function (res) {

            let data = JSON.parse(res);

            // alert(JSON.stringify(data));
            data.forEach(item => {

                let li = $('<li>');
                let node = $('<div class="tree-node">');
                let toggle = $('<span class="toggle">+</span>');
                let text = $('<span>').text(item.text);

                node.append(toggle).append(text);
                li.append(node);

                let children = $('<ul>').hide();
                li.append(children);

                node.on('click', function (e) {
                    e.stopPropagation();

                    if (node.hasClass('disabled')) return;

                    // remove previous selection (same level)
                    node.closest('ul').find('.tree-node').removeClass('selected');

                    // mark selected
                    node.addClass('selected');

                    let ctx = Object.assign({}, context);

                    /* ---- context mapping ---- */
                    if (type === 'slot') ctx.slot = item.text;
                    if (type === 'session') ctx.sessionyear = item.text;
                    if (type === 'exam') ctx.exam = item.text;
                    if (type === 'class') ctx.areaname = item.text;
                    if (type === 'section') ctx.subarea = item.text;
                    if (type === 'subject') ctx.subject = item.text;


                    if (type === 'section' && chainInput.includes('subject')) {

                        // show right panel
                        $('#subjectColumn').removeClass('d-none');

                        // modal auto expand
                        $('#nodeTreeModal .modal-dialog')
                            .removeClass('modal-lg')
                            .addClass('modal-xl');

                        // clear old subject
                        $('#subjectList').html('');

                        // load subject into right panel
                        loadSubjectList(ctx);

                        return; // stop tree expansion here
                    }



                    /* ---- nextType resolver ---- */
                    let nextType = null;

                    // helper (exact match এড়াতে চাইলে)
                    const hasExam = /\bexam\b/.test(chainInput);
                    const hasClass = /\bclass\b/.test(chainInput);
                    const hasSubject = /\bsubject\b/.test(chainInput);
                    const hasReload = /\breload\b/.test(chainInput);

                    if (type === 'slot') {

                        nextType = 'session';

                    } else if (type === 'session') {

                        if (hasClass) {
                            nextType = null;

                        } else if (hasExam) {
                            nextType = 'exam';

                        } else {
                            nextType = 'class';
                        }

                    } else if (type === 'exam') {

                        nextType = 'class';

                    } else if (type === 'class') {

                        nextType = 'section';

                    } else if (type === 'section') {

                        nextType = hasSubject ? 'subject' : null;
                    }


                    // disable this node after selection
                    node.addClass('disabled');

                    if (nextType) {
                        // load only once
                        if (children.children().length === 0) {
                            loadNodes(nextType, ctx, children);
                        }
                        node.closest('ul').find('ul').not(children).slideUp();
                        node.closest('ul').find('.toggle').text('+');
                        children.slideDown();
                        toggle.text('-');
                    } else {

                        finalizeSelection(ctx, item);
                        alert('Selection completed: ' + item.text);
                        if (hasReload) {
                            window.location.reload();
                        }
                    }
                });


                container.append(li);


            });
        });

    }


    function finalizeSelection(ctx, item) {

        let selected = {
            slot: ctx.slot ?? null,
            session: ctx.sessionyear ?? null,
            exam: ctx.exam ?? null,
            class: ctx.areaname ?? null,
            section: ctx.subarea ?? null,
            subject: ctx.subject ? ctx.subject.split(' - ')[0] : null,
            final_text: item.text,
            final_id: item.id
        };
        alert(JSON.stringify(selected));


        if ($('#slot-main').length) {
            $('#slot-main').val(selected.slot);
            setCookie('chain-slot', selected.slot);
        }

        if ($('#session-main').length) {
            $('#session-main').val(selected.session);
            setCookie('chain-session', selected.session);
        }

        if ($('#exam-main').length) {
            $('#exam-main').val(selected.exam);
            setCookie('chain-exam', selected.exam);
        }

        if ($('#class-main').length) {
            $('#class-main').val(selected.class);
            setCookie('chain-class', selected.class);
        }

        if ($('#section-main').length) {
            $('#section-main').val(selected.section);
            setCookie('chain-section', selected.section);
        }

        if ($('#subject-main').length) {
            $('#subject-main').val(selected.subject);
            setCookie('chain-subject', selected.subject);
        }

        $('#selectedTree').val(JSON.stringify(selected));

        let nodeTreeBlock = document.getElementById('nodeTreeModal');

        // Bootstrap 5 modal hide করতে
        if (nodeTreeBlock) {
            let modal = bootstrap.Modal.getOrCreateInstance(nodeTreeBlock);
            modal.hide();
        }

    }



    function loadSubjectList(ctx) {
        // alert(JSON.stringify(ctx));
        $.post('component/node-tree.php', {
            type: 'subject',
            context: ctx
        }, function (res) {

            let data = JSON.parse(res);
            let ul = $('#subjectList');

            ul.html('');

            data.forEach(item => {

                let li = $('<li class="list-group-item">')
                    .text(item.text)
                    .on('click', function () {

                        ul.find('.list-group-item').removeClass('active');
                        $(this).addClass('active');

                        ctx.subject = item.text;

                        finalizeSelection(ctx, item);
                    });

                ul.append(li);
            });
        });
    }

    $('#nodeTreeModal').on('hidden.bs.modal', function () {

        $('#subjectColumn').addClass('d-none');
        $('#subjectList').html('');

        $('#nodeTreeModal .modal-dialog')
            .removeClass('modal-xl')
            .addClass('modal-lg');
    });

</script>