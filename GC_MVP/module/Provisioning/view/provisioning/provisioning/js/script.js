jQuery(document).ready(function() {

    // --------------------------------------------------- //
    // global                                              //
    // --------------------------------------------------- //

    // show width ---------------------------------------- //
    var autoWidthElems = $('.calc-widths .content').filter(function(index) {
        var h2 = $(this).find('h2').text().toLowerCase();
        var skip = ['top/global nav', 'main nav', 'breadcrumb', 'footer', 'sub nav', 'pagination', 'selection breadcrumb', 'filter/sort'];
        if ($.inArray(h2, skip) >= 0) {
            return false;
        }
        return true;
    })

    var imgElems = $('img.responsive');
    var widthElems = $('.show-width').add(autoWidthElems, imgElems);

    widthElems.each(showWidth);
    $(window).resize(function() {
        widthElems.each(showWidth);
    });

    function showWidth() {
        var e = $(this).prop('tagName').toLowerCase();
        var w = $(this).outerWidth();

        var className = 'width';
        var target = $(this);
        var icon = '<span class="glyphicon glyphicon-resize-horizontal"></span> ';

        if (e == 'img') {
            className = 'img-width';
            target = $(this).parent();
            icon += 'img ';
        }

        var p = target.find('p.' + className);
        if (!p.length) {
            p = $('<p class="' + className + '">');
        }

        p.html(icon + w + 'px');

        if (e == 'img') {
            $(this).after(p);
        } else {
            target.append(p);
        }
    }

    // read query parameters
    function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
        var regexS = "[\\?&]" + name + "=([^&#]*)";
        var regex = new RegExp(regexS);
        var results = regex.exec(window.location.href);
        if (results == null) {
            return "";
        } else {
            return decodeURIComponent(results[1].replace(/\+/g, " "));
        }
    }

    // initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // --------------------------------------------------- //
    // challenges home                                     //
    // --------------------------------------------------- //

    // full screen hero (v4a) ---------------------------- //
    if ($('.full-screen-hero').length) {
        var jumbotronContainer = $('.jumbotron-container');
        var jumbotron = $('.jumbotron');

        // set height
        var h = $(window).height() - 80;
        var headerH = $('header').height();
        var padding = $('.jumbotron').innerHeight() - $('.jumbotron').height();

        h -= headerH;
        jumbotronContainer.height(h);
        h -= padding;
        jumbotron.height(h);


        // parallax scroll
        var scrollTop;
        $(window).scroll(function() {
            scrollTop = $(window).scrollTop() - headerH;

            if (scrollTop > 0) {
                jumbotron.css('top', scrollTop / 2)
            } else {
                jumbotron.css('top', 0)
            }
        });

    }

    // manual carousel (v5) ------------------------------ //
    var carousel = $('.carousel.manual');
    var visible = 0;
    var targetH = 0;

    if (carousel.length) {
        targetH = carousel.offset().top + carousel.height();

        // pause button
        $('a.pause-carousel').click(function(e) {
            e.preventDefault();
            var active = $(this).attr('data-active');
            if (active == 'true') {
                $(this)
                        .attr('data-active', 'false')
                        .text('play');
                carousel.carousel('pause');
            } else {
                $(this)
                        .attr('data-active', 'true')
                        .text('pause');
                carousel.carousel('cycle');
            }
        });

        // attach the start functtion to window scroll, and run it once to check if it's already visible
        $(window).on('scroll', startCarousel);
        startCarousel();
    }

    // check if carousel is visible and start it
    function startCarousel() {
        var scrollTop = $(window).scrollTop();
        var windowHeight = $(window).height();
        visible = scrollTop + windowHeight;

        if (visible >= targetH) {
            $(window).off('scroll');
            $('a.pause-carousel').fadeIn().attr('data-active', 'true');
            carousel.carousel({
                interval: 1000,
                pause: "hover"
            });
        }
    }

    // upcoming challenges expand (v5a) ------------------ //
    var challenges = $('.upcoming-challenges');

    if (challenges.length) {
        var trigger = challenges.find('a.expand');
        var items = challenges.find('.items .item:gt(1)');
        var active = false;
        var showText = trigger.text();
        var hideText = trigger.attr('data-hide-text');

        trigger.click(function(e) {
            e.preventDefault();
            if (!active) {
                items.show(200);
                active = true;
                $(this).text(hideText);
            } else {
                items.hide(200);
                active = false;
                $(this).text(showText);
            }
        });
    }


    // --------------------------------------------------- //
    // join                                                //
    // --------------------------------------------------- //

    if ($('.non-auth-bck').length) {

        var currentStep = 1;
        var currentSlide;
        var role = 'all';
        var roleIndex = 0;
        var accountCreated = false;
        var alerts = $('.step .alert');
        var username = '';
        var age;
        var studentInfo;

        var btnBack = $('#join-modal .back');
        var btnContinue = $('#join-modal .continue');
        var btnCancel = $('#join-modal .cancel');
        var btnContBrowsing = $('#join-modal .continue-browsing');
        var btnUAContBrowsing = $('#join-modal .ua-continue-browsing');
        var btnDashboard = $('#join-modal .dashboard');
        var btnParentEmail = $('#join-modal .send-email-to-parent');
        var btnAddStudent = $('#join-modal .add-student');
        var btnFinish = $('#join-modal .finish');

        var btnJoin = $('header .btn-join');
        var btnComplete = $('header .btn-complete');
        var btnUser = $('header .btn-user');

        var roles = ['teacher', 'hs-teacher', 'parent', 'student', 'ua-student', 'mentor', 'other', 'form-demo'];
        var buttons = [btnCancel, btnContBrowsing, btnBack, btnContinue, btnAddStudent, btnFinish, btnParentEmail, btnDashboard, btnUAContBrowsing, btnJoin, btnComplete, btnUser];
        var visibleButtons = [
            [// teacher
                [1, 0, 0, 1, 0, 0, 0, 0, 0, 1, 0, 0],
                [1, 0, 1, 1, 0, 0, 0, 0, 0, 1, 0, 0],
                [0, 1, 1, 1, 0, 0, 0, 0, 0, 0, 1, 1],
                [0, 1, 1, 1, 0, 0, 0, 0, 0, 0, 1, 1],
                [0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 1, 1],
                [0, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 1]
            ],
            [// hs-teacher
                [1, 0, 0, 1, 0, 0, 0, 0, 0, 1, 0, 0],
                [1, 0, 1, 1, 0, 0, 0, 0, 0, 1, 0, 0],
                [0, 1, 1, 1, 0, 0, 0, 0, 0, 0, 1, 1],
                [0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 1, 1],
                [0, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 1]
            ],
            [// parent
                [1, 0, 0, 1, 0, 0, 0, 0, 0, 1, 0, 0],
                [1, 0, 1, 1, 0, 0, 0, 0, 0, 1, 0, 0],
                [0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 1, 1],
                //[0, 0, 0, 0, 1, 1, 0, 0, 0, 0, 1, 1],
                [0, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 1]
            ],
            [// student
                [1, 0, 0, 1, 0, 0, 0, 0, 0, 1, 0, 0],
                [1, 0, 1, 1, 0, 0, 0, 0, 0, 1, 0, 0],
                [1, 0, 1, 1, 0, 0, 0, 0, 0, 1, 0, 0],
                [1, 0, 1, 1, 0, 0, 0, 0, 0, 1, 0, 0],
                [0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 1, 1],
                [0, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 1]
            ],
            [// ua-student
                [1, 0, 0, 1, 0, 0, 0, 0, 0, 1, 0, 0],
                [1, 0, 1, 1, 0, 0, 0, 0, 0, 1, 0, 0],
                [1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0]
            ],
            [// mentor

            ],
            [// other

            ],
            [// form-demo
                [1, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0],
                [1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
            ]
        ]

        // show modal on page load
        // $('header .btn-join').click();

        // step navigation --------------------------------- //

        // step ahead
        btnContinue.click(function() {


            if (currentStep == 1) {
                role = $('input[name="role"]:checked', '#join-modal').val();
                roleIndex = $.inArray(role, roles);

                currentStep++;
                $('.step[data-step="1"]').hide();
                currentSlide = $('.step[data-step="2"][data-role="' + role + '"]');
                currentSlide.show();

            } else {

                if (currentStep == 2 && role == 'student') {
                    age = 1 * $('#student-birthday').val().split('-')[0];
                    age = 2014 - age;
                    if (age < 14) {
                        $('.step').hide();
                        $('.underage').show();
                        currentStep++;
                        roleIndex = 4;
                        return;
                    }
                }

                currentStep++;
                //

                currentSlide = $('.step[data-step="' + currentStep + '"][data-role="' + role + '"]');
                //

                // create main account
                if (!accountCreated && currentSlide.attr('data-account-created') == 'main') {
                    accountCreated = true;
                    currentSlide.find('.alert-main-account').fadeIn(200);

                    username = $('.step[data-role="' + role + '"] #firstname').val();
                    //username = $('.step[data-role="' + role + '"] ipnut[id="firstname"]').val();
                    if (username == '') {
                        username = '{First Name}';
                    }

                    $('.btn-user .username').text(username);
                    $('.step[data-role="' + role + '"] h4 .name').text(username);
                }

                $('.step').hide();
                currentSlide.show();

                // parent flow - create student account
                if (currentSlide.attr('data-account-created') == 'student') {
                    currentSlide.find('.alert-student-account').fadeIn(200);

                    studentInfo = '';
                    tmp = $('.step[data-step="3"][data-role="parent"] input#student_first').val();
                    if (tmp == '') {
                        tmp = 'First Name';
                    }
                    studentInfo += tmp + ', ';

                    tmp = $('.step[data-step="3"][data-role="parent"] input#student_last').val();
                    if (tmp == '') {
                        tmp = 'Last Name';
                    }
                    studentInfo += tmp + '<br>';

                    tmp = $('.step[data-step="3"][data-role="parent"] #student-grade').val();
                    if (tmp == undefined || tmp == '') {
                        tmp = 'Grade';
                    }
                    studentInfo += tmp + ', ';

                    tmp = $('.step[data-step="3"][data-role="parent"] input#school_name').val();
                    if (tmp == '') {
                        tmp = 'School Name';
                    }
                    studentInfo += tmp + ', ';

                    tmp = $('.step[data-step="3"][data-role="parent"] input#school_zip').val();
                    if (tmp == '') {
                        tmp = 'School ZIP';
                    }
                    studentInfo += tmp + ', ';

                    tmp = $('.step[data-step="3"][data-role="parent"] input#school_address').val();
                    if (tmp == '') {
                        tmp = 'School Address';
                    }
                    studentInfo += tmp;

                    currentSlide
                            .find('ul.your-students')
                            .append('<li>' + studentInfo + '</li>');
                }

                // copy applicable fields to mailing address
                if (currentStep == 3 && role == 'hs-teacher') {
                    prevSlide = $('.step[data-step="2"][data-role="hs-teacher"]');
                    fieldsToCopy = ['#firstname', '#lastname', '#country'];
                    $.each(fieldsToCopy, function(index, val) {
                        $(val, currentSlide).val($(val, prevSlide).val());
                    });
                }
            }
        });

        btnCancel.click(function() {
            ajaxpost();
        });
        
        btnContBrowsing.click(function(){
            ajaxpost();
        });

        // show thank you screen
        btnFinish.click(function() {
            ajaxpost();
            //ajax request end


            currentStep++;
            $('.step').hide();

            currentSlide = $('.step[data-step="' + currentStep + '"][data-role="' + role + '"]');
            currentSlide.show();
        });

        // step back
        btnBack.click(function() {
            if (currentStep == 2) {
                role = 'all';
                accountCreated = false;
            }

            currentStep--;

            $('.step').hide();
            currentSlide = $('.step[data-step="' + currentStep + '"][data-role="' + role + '"]');
            currentSlide.show();
        });

        // add another student
        btnAddStudent.click(function() {
            currentStep -= 1;

            // reset form
            $('input, select', '.step[data-step="3"][data-role="parent"]').val('');

            // show slide
            $('.step').hide();
            currentSlide = $('.step[data-step="3"][data-role="parent"]');
            currentSlide.show();
        });

        // show underage student thank you screen
        btnParentEmail.click(function() {
            alert(role);
            var firstname = $('.step[data-role="' + role + '"] #firstname').val();
            var lastname = $('.step[data-role="' + role + '"] #lastname').val();
            var uemail = $('#uemail').val();
            var birthday = $('.step[data-role="' + role + '"] #student-birthday').val();

            $.ajax({
                type: 'post',
                url: 'http://localhost/GC_MVP/public/provisioning/Provisioning/ajaxregist',
                dataType: 'html',
                data: {
                    role: role,
                    currentStep: currentStep,
                    //common info
                    firstname: firstname,
                    lastname: lastname,
                    birthday: birthday,
                    uemail: uemail,
                },
                success: function(msg) {
                    //alert('response:' + msg);
                }
            });


            $('.step').hide();
            currentStep++;
            currentSlide = $('.email-confirmation');
            currentSlide.show();
        });

        // show buttons and fix header height
        $('.modal-footer .btn').click(function() {
            showButtons(roleIndex, currentStep - 1);
            setHeaderHeight(currentSlide);
        });

        // teacher step 4 - add new class
        $('.step .add-class').click(function() {
            var classDiv = $('.step[data-step="4"][data-role="teacher"] .class:first');
            classDiv
                    .clone()
                    .find('input').each(function(index) {
                $(this).val('').prop('checked', false);
            }).end()
                    .insertBefore(this);


        });

        // checkboxes drop down
        $('.dropdown-menu *', '.school-type, .your-student-grade').click(function(e) {
            e.stopPropagation(); // don't close dropdown when user clicks on checkbox label
        });

        // select country - if it's "US" show the state select
        $('select[name="country"]').change(function() {
            if ($(this).val() == "United States") {
                $(this).next('.select-state').show();
            } else {
                $(this).next('.select-state').hide();
            }
        });

        // student grade is dropdown for the us, input for other coutries
        $('#country', '.step[data-step="4"][data-role="student"], .step[data-step="3"][data-role="parent"]').change(function() {
            if ($(this).val() == "United States") {
                $(this).parents('.step')
                        .find('.grade-us').show().end()
                        .find('.grade-other').hide();
            } else {
                $(this).parents('.step')
                        .find('.grade-us').hide().end()
                        .find('.grade-other').show();
            }
        });

        var accordionOptions = {
            heightStyle: "content",
            active: -1
        };
        var accord = $(".accordion");


        $('.add-a-student').click(function() {

            $('.accordion.ui-accordion').accordion("destroy")
            var firstName = accord.find('.panel:last #student_first').val();
            firstName = firstName != '' ? firstName : '{First Name}';
            var lastName = accord.find('.panel:last #student_last').val();
            lastName = lastName != '' ? lastName : '{Last Name}';
            var headerText = firstName + ' ' + lastName;

            accord
                    .find('h5:last').text(headerText).end()
                    .append('<h5>New Student</h5>')
                    .find('.panel:first')
                    .clone()
                    .find('input').each(function(index) {
                $(this).val('').prop('checked', false);
            }).end()
                    .find('select').each(function(index) {
                $(this).find('option:first').prop('selected', true);
            }).end()
                    .appendTo(accord)



            accord.accordion(accordionOptions);
        });

        // validation -------------------------------------- //
        var passwordSuggestedChars = '1|2|3|4|5|6|7|8|9|0';

        // username
        $('input[name="username"]').keyup(function() {
            if ($(this).val() == '') {
                $(this).parent()
                        .find('.notice-fail').css('display', 'none').end()
                        .find('.notice-success').css('display', 'none');
            } else if ($(this).val().toLowerCase() == 'error') {
                $(this).parent()
                        .find('.notice-fail').css('display', 'inline-block').end()
                        .find('.notice-success').css('display', 'none');
            } else {
                $(this).parent()
                        .find('.notice-fail').css('display', 'none').end()
                        .find('.notice-success').css('display', 'inline-block');
            }
        });

        // password
        $('input[name="password"]').keyup(function() {
            var val = $(this).val();

            if (val == '') {
                $(this).parent()
                        .find('.notice-fail').css('display', 'none').end()
                        .find('.notice-weak').css('display', 'none').end()
                        .find('.notice-success').css('display', 'none');
            } else if (val.length < 8) {
                $(this).parent()
                        .find('.notice-fail').css('display', 'inline-block').end()
                        .find('.notice-weak').css('display', 'none').end()
                        .find('.notice-success').css('display', 'none');
            } else if (new RegExp(passwordSuggestedChars).test(val)) {
                $(this).parent()
                        .find('.notice-fail').css('display', 'none').end()
                        .find('.notice-weak').css('display', 'none').end()
                        .find('.notice-success').css('display', 'inline-block');
            } else {
                $(this).parent()
                        .find('.notice-fail').css('display', 'none').end()
                        .find('.notice-weak').css('display', 'inline-block').end()
                        .find('.notice-success').css('display', 'none');
            }
        });

        // open modal on step specified in query string ---- //
        var qRole = getParameterByName('role');
        var qStep = getParameterByName('step');

        roleIndex = $.inArray(qRole, roles);
        var uasIncrement = 10;

        if (roleIndex >= 0 && qStep != '') {
            $('header .btn-join').click();

            // set surrent step and role
            currentStep = 1 * qStep;
            if (qRole == 'ua-student') {
                role = 'student';

                if (currentStep > 2) {
                    currentStep += uasIncrement;
                }

            } else {
                role = qRole;
            }

            // set radio on the first slide
            $('.step[data-step="1"] input[name="role"][value="' + role + '"]').prop('checked', true);

            if (currentStep == 1) {
                role = 'all';
            }

            // show target slide
            $('.step').hide();
            currentSlide = $('.step[data-step="' + currentStep + '"][data-role="' + role + '"]');
            currentSlide.show();

            // show buttons
            showButtons(roleIndex, currentStep - 1);

            setHeaderHeight(currentSlide);
        }

    } // /join

    // set modal-header height and title position
    function setHeaderHeight(currentSlide) {
        var modalHeader = $('#join-modal .modal-header');
        var title = currentSlide.find('h4')
        title.appendTo('body');
        var titleHeight = title.height();
        currentSlide.prepend(title);
        modalHeader.height(titleHeight);
        title.css('top', -titleHeight - 16);
    }

    function showButtons(roleIndex, step) {
        $.each(buttons, function(index, val) {
            if (roleIndex == 4 && step > 10) {
                step -= 10;
            }
            if (visibleButtons[roleIndex][step][index]) {
                this.show();
            } else {
                this.hide();
            }
        });
    }

    function ajaxpost()
    {
        //step1 cu2
        var firstname = $('.step[data-role="' + role + '"] #firstname').val();
        var lastname = $('.step[data-role="' + role + '"] #lastname').val();
        var gender = $('.step[data-role="' + role + '"] #gender').val();
        var email = $('.step[data-role="' + role + '"] #email').val();
        var username = $('.step[data-role="' + role + '"] #username').val();
        var password = $('.step[data-role="' + role + '"] #password').val();
        var birthday = $('.step[data-role="' + role + '"] #birthday').val();
        var title = $('.step[data-role="' + role + '"] #title').val();
        var tcountry = $('.step[data-role="' + role + '"] select[name="tcountry"]').val();

        //step2 cu3
        var school_code = $('.step[data-role="' + role + '"] #school_code').val();
        var school_name = $('.step[data-role="' + role + '"] #school_name').val();
        var school_address = $('.step[data-role="' + role + '"] #school_address').val();
        var school_zip = $('.step[data-role="' + role + '"] #school_zip').val();
        var school_country = $('.step[data-role="' + role + '"] #country').val();
        var school_state = $('.step[data-role="' + role + '"] #state').val();
        var school_city = $('.step[data-role="' + role + '"] #city').val();
        var school_type = "";
        
        //school_type
        var temSelectedSchoolType = $('.step[data-role="' + role + '"] #school_type_div input:checked');
        if(temSelectedSchoolType.size()>0){
            temSelectedSchoolType.each(function(){
                school_type += $(this).attr("name")+",";
            });
        }
        var school_safe_number = $('.step[data-role="' + role + '"] #school-safe-number').val();


        //step3 cu4

        var school_code = $('.step[data-role="' + role + '"] #school_code').val();

        
        var $subjectArr = $('.step[data-step="4"][data-role="' + role + '"] .dynticSub');
        //var subCount = $subjectArr.size();

        var subArr = new Array();

        $subjectArr.each(function() {
            
            if ($(this).find("input#class_subject").val() != "") {

                var jsonSubObj = new Object();
                jsonSubObj['subjectName'] = $(this).find("input#class_subject").val();

                if ($(this).find("input#number_of_students").val()) {
                    jsonSubObj['studentsNumber'] = $(this).find("input#number_of_students").val();
                }
                
                //grade
                var multiSelectedGrade = $(this).find(".student-grade ul.dropdown-menu input[type='checkbox']:checked");
                if(multiSelectedGrade.size()>0){
                    jsonSubObj['grade'] = new Array();
                    multiSelectedGrade.each(function(){
                        jsonSubObj['grade'].push($(this).attr("name")); 
                    });
                }

                //student age
                var multiSelectedAge = $(this).find(".student-age ul.dropdown-menu input[type='checkbox']:checked");
                if(multiSelectedAge.size()>0){
                    jsonSubObj['studentAge'] = new Array();
                    multiSelectedAge.each(function(){
                        jsonSubObj['studentAge'].push($(this).attr("name")); 
                    });
                }

                subArr.push(jsonSubObj);
                //alert($(this).find("input#number_of_students").val());
            }

        });
        
        var students_language = "" ;
        var students_language_checked = $('.step[data-role="' + role + '"] #students_language_div input:checked');
        if(students_language_checked.size()>0){
            students_language_checked.each(function(){
                students_language += $(this).attr("name")+",";
            });
        }
        
        //step 4 cu5
        var more_about_you = $('.step[data-role="' + role + '"] #more-about-you').val();
        var years_of_xp = $('.step[data-role="' + role + '"] #years-of-xp').val();
        var degree = $('.step[data-role="' + role + '"] #degree').val();
        var match = $('.step[data-role="' + role + '"] #match').val();
        //step 3  ht               
        var teaching_environment = $('.step[data-role="' + role + '"] #teaching-environment').val();
        var mail_first_name = $('.step[data-role="' + role + '"] #mfirstname').val();
        var mail_last_name = $('.step[data-role="' + role + '"] #mlastname').val();
        var address_line_1 = $('.step[data-role="' + role + '"] #address_line_1').val();
        var address_line_2 = $('.step[data-role="' + role + '"] #address_line_2').val();
        var mail_city = $('.step[data-role="' + role + '"] #city').val();
        var mail_state = $('.step[data-role="' + role + '"] #state').val();
        var mail_zip = $('.step[data-role="' + role + '"] #zip').val();
        var mail_country = $('.step[data-role="' + role + '"] #mcountry').val();
        //step 4  ht          
        var class_title = $('.step[data-role="' + role + '"] #class_title').val();
        var student_age = $('.step[data-role="' + role + '"] #student-age').val();
        var hsstudentsNumber = $('.step[data-role="' + role + '"] #hsstudentsNumber').val();
        //step 2 student
        var grade_other = $('.step[data-role="' + role + '"] #grade_other').val();
        var your_grade = $('.step[data-role="' + role + '"] #your-grade').val();
        var sbirthday = $('.step[data-role="' + role + '"] #sbirthday').val();
        //step 3 underage
        var email = $('.step[data-role="' + role + '"] #email').val();
        var country = $('.step[data-role="' + role + '"] #country').val();
        //parent step 3
        var $psubjectArr = $('.step[data-step="3"][data-role="' + role + '"] .dynticSub1');
        //var subCount = $subjectArr.size();

        
        var psubArr = new Array();

        $psubjectArr.each(function() {
            
            if ($(this).find("input#student_first").val() != "") {

                var jsonSubObj = new Object();
                jsonSubObj['student_first'] = $(this).find("input#student_first").val();
                jsonSubObj['student_last'] = $(this).find("input#student_last").val();
                jsonSubObj['student_username'] = $(this).find("input#student_username").val();
                jsonSubObj['student_password'] = $(this).find("input#student_password").val();
                jsonSubObj['student_birthday'] = $(this).find("input#student_birthday").val();
                jsonSubObj['student_school_name'] = $(this).find("input#student_school_name").val();
                jsonSubObj['student_school_address'] = $(this).find("input#student_school_address").val();
                jsonSubObj['student_city'] = $(this).find("input#student_city").val();
                jsonSubObj['student_state'] = $(this).find("input#student_state").val();
                jsonSubObj['student_school_zip'] = $(this).find("input#student_school_zip").val();
                jsonSubObj['student_country'] = $(this).find("input#student_country").val();
                jsonSubObj['student_grade_other'] = $(this).find("input#student_grade_other").val();
                jsonSubObj['student_your_grade'] = $(this).find("input#student_your-grade").val();
                
               
                var multiSelectedGrade = $(this).find(".student-grade ul.dropdown-menu input[type='checkbox']:checked");
                if(multiSelectedGrade.size()>0){
                    jsonSubObj['grade'] = new Array();
                    multiSelectedGrade.each(function(){
                        jsonSubObj['grade'].push($(this).attr("name")); 
                    });
                }

                
                var multiSelectedAge = $(this).find(".student-age ul.dropdown-menu input[type='checkbox']:checked");
                if(multiSelectedAge.size()>0){
                    jsonSubObj['studentAge'] = new Array();
                    multiSelectedAge.each(function(){
                        jsonSubObj['studentAge'].push($(this).attr("name")); 
                    });
                }
                
                //if ($(this).find("input#number_of_students").val()) {
                //jsonSubObj['studentsNumber'] = $(this).find("input#number_of_students").val();
                //}
                psubArr.push(jsonSubObj);
                //alert($(this).find("input#number_of_students").val());
            }

        });

        $.ajax({
            type: 'post',
            url: 'http://localhost/GC_MVP/public/provisioning/Provisioning/ajaxregist',
            dataType: 'html',
            data: {
                role: role,
                currentStep: currentStep,
                //common info
                firstname: firstname,
                lastname: lastname,
                gender: gender,
                email: email,
                username: username,
                password: password,
                birthday: birthday,
                sbirthday: sbirthday,
                title: title,
                tcountry: tcountry,
                country: country,
                //tteacher step 3
                school_code: school_code,
                school_name: school_name,
                school_address: school_address,
                school_zip: school_zip,
                school_country: school_country,
                school_state: school_state,
                school_city: school_city,
                school_type: school_type,
                school_safe_number: school_safe_number,
                students_language:students_language,
                
                //teacher step 4
                more_about_you: more_about_you,
                years_of_xp: years_of_xp,
                degree: degree,
                match: match,
                jsonArr: subArr,
                //home teacher step 3
                teaching_environment: teaching_environment,
                mail_first_name: mail_first_name,
                mail_last_name: mail_last_name,
                address_line_1: address_line_1,
                address_line_2: address_line_2,
                mail_city: mail_city,
                mail_state: mail_state,
                mail_zip: mail_zip,
                mail_country: mail_country,
                //home teacher 4
                class_title: class_title,
                student_age: student_age,
                hsstudentsNumber: hsstudentsNumber,
                grade_other: grade_other,
                your_grade: your_grade,
                psubArr: psubArr

            },
            success: function(msg) {
                alert('response:' + msg);
            }
        });

        //ajax request end

    }

});




