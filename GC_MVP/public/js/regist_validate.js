/* 
 regist validate
 
 */

var userExistURL = 'http://'+window.location.hostname+'/provisioning/Provisioning/ifUserNotExist';

jQuery(document).ready(function() {
    
    //set validator plugin globble paremeter ;
    
    jQuery.validator.setDefaults({
        submitHandler: function(form) {
            
        },
        errorPlacement:function(error,element){
            
            var errorSize = error.html().length ;
            
            if(element.attr("id") == "teaching_environment"){
                
            }else if( element.width()>166 || errorSize >=33 ){
                    
                //error.css("margin-left","156px");  
                
            }
            error.appendTo(element.parent());
            
        },
    });
    
    jQuery.validator.addMethod("isZipCode", function(value, element) {  
        var tel = /^[0-9]{6}$/;
        return this.optional(element) || (tel.test(value));
    }, "wrong zip code");
    
    
    jQuery.validator.addMethod("regexASCII",function(value,element,params){
        
        var regForASCII = /^[x00-x7f]+$/;
        
        var exp = new RegExp(regForASCII);
        
        return exp.test(value);
    });
    
    //set our own REG
    
    jQuery.validator.addMethod("regex",function(value,element,params){
        
        var exp = new RegExp(params);
        
        return exp.test(value);
    });
    
    jQuery.validator.addMethod("notUnOne", function(value, element) {  
        var tel = "-1"
        return this.optional(element) || (value != tel);
    }, "Please select a option");


    //teacher validate
    
    //teacher step1

    $("#form-teacher-1").validate({
        submitHandler: function(form) {
            
        },
        debug:true,
        rules: {
            firstname: {
                required: true
            },
            lastname: {
                required: true
            },
            username: {
                required: true, 
                remote:userExistURL
            },
            gender: "notUnOne",
            password:{
                required: true,
                minlength: 6,
                regexASCII: true
            },
            password_vertify:{
                required: true,
                minlength: 6,
                equalTo: "#form-teacher-1 #password",
                regexASCII: true
            },
            email: {
                required: true,
                email: true
            },
            email_vertify: {
                required: true,
                email: true,
                equalTo: "#form-teacher-1 #email"
            },
            birthday: {
                required: true,
                dateISO:true
            },
            country: {
                notUnOne: true,
            }
        },
        messages: {
            gender:"Please select your gender",
            firstname: "Please enter your firstname",
            lastname: "Please enter your lastname",
            username: {
                required: "Please enter a username",
                remote:"The username you've requested is already taken, please choose another."
                
            },
            password: {
                required: "Please provide a password",
                minlength: "The password you've requested does not match the ePals password requirements, please choose another password.",
                regexASCII: "The password you've requested does not match the ePals password requirements, please choose another password."
            },
            password_vertify:{
                required: "Please provide a password",
                minlength: "The password you've requested does not match the ePals password requirements, please choose another password.",
                equalTo: "The passwords you have entered do not match, please re-enter them.",
                regexASCII: "The password you've requested does not match the ePals password requirements, please choose another password."
            },
            email: {
                required: "Please enter your email",
                email:"The email address you have entered is not in the proper format, please re-enter it."
            },
            email_vertify: {
                required: "Please enter your email",
                equalTo:"The email addresses you have entered do not match, please re-enter them.",
                email: "The email address you have entered is not in the proper format, please re-enter it."
            },
            birthday: {
                required: "Please provide your birthday",
                dateISO: "Please provide validate date"
                
            },
            country: {
                notUnOne: "Please select country" 
            }
            
           
        }
    });

    
    //teacher step 2
    
    $("#form-teacher-2").validate({
        
        debug:true,
        rules: {
            school_name: {
                required: true
            },
            school_address:{
                required: true
            },
            city: {
                required: true
            },
            state: {
                required: true
            },
            school_zip: {
                required: true
            },
            country: {
                notUnOne: true,
            }
        },
        messages: {
            school_name: {
                required: "Please enter your school name"
            },
            school_address:{
                required: "Please enter your school address"
            },
            city: {
                required: "Please enter your city of school"
            },
            state: {
                required: "Please enter your state of school"
            },
            school_zip: {
                required: "Please enter your school ZIP"
            },
            country: {
                notUnOne: "Please select country",
            }
        }
    });
    
    //teacher step 3
    $("#form-teacher-3").validate();

    $("#form-teacher-3").each(function() {
        rules1 = {
            class_subject: {
                required: true
            }
        };
        
        messages1 = {
            class_subject: {
                required: "Please enter class subject."
            }
        };

        // input blue validate
        for(var data in rules1) {
            $(this).on("blur", "[name='" + data + "']", function() {
                inputCheck($(this));
            });
        }

        // form submit validate
        $(this).bind('submit', function() {
            for(var data in rules1) {
                $(this).find("[name='" + data + "']").each(function() {
                    inputCheck($(this));
                });
            }
        });

        /**
        * input validate
        * @param {selector} thisItem this input
        */
        function inputCheck(thisItem) {
            var itemName = thisItem.attr("name");

            var isError = false;
            // error label
            var errorLabel = thisItem.next("label.error");
            var hasErrorLabel = errorLabel.length;
            // error message
            var msg = "";
            // trim this value
            var thisValue = thisItem.val().trim();

            // null validate
            if (rules1[itemName].required) {
                if (!isError && !thisValue) {
                    isError = true;
                    msg = messages1[itemName].required;
                }
            }
            // error
            if (isError) {
                if (!hasErrorLabel) {
                    // no error label then init error label
                    thisItem.after("<label class='error' for='" + itemName + "'></label>");
                    errorLabel = thisItem.next("label.error");
                }
                errorLabel.html(msg);
                errorLabel.show();
            } else {
                if (hasErrorLabel) {
                    errorLabel.hide();
                }
            }

            return isError;
        }
    });
    
    $("#form-teacher-4").validate({
        debug:true
    });
    
    
    
    //teacher_homeschool step 1
    $("#form-teacher_homeschool-1").validate({
        debug:true,

        rules: {
            firstname: "required",
            lastname: "required",
            username: {
                required: true,
                remote:userExistURL
            },
            gender: "notUnOne",
            password:{
                required: true,
                minlength: 6,
                regexASCII: true
            },
            password_vertify: {
                required: true,
                minlength: 6,
                equalTo: "#form-teacher_homeschool-1 #password",
                regexASCII: true
            },
            email: {
                required: true,
                email: true
            },
            email_vertify: {
                required: true,
                email: true,
                equalTo: "#form-teacher_homeschool-1 #email"
            },
            birthday: {
                required: true                
            },
            country: {
                notUnOne: true,
            }
            
        },
        messages: {
            gender:"Please select your gender",
            firstname: "Please enter your firstname",
            lastname: "Please enter your lastname",
            username: {
                required: "Please enter a username",
                remote:"The username you've requested is already taken, please choose another."
            },
            password: {
                required: "Please provide a password",
                minlength: "The password you've requested does not match the ePals password requirements, please choose another password.",
                regexASCII: "The password you've requested does not match the ePals password requirements, please choose another password."
            },
            password_vertify:{
                required: "Please provide a password",
                minlength: "The password you've requested does not match the ePals password requirements, please choose another password.",
                equalTo: "The passwords you have entered do not match, please re-enter them.",
                regexASCII: "The password you've requested does not match the ePals password requirements, please choose another password."
            },
            email: {
                required: "Please enter your email",
                email:"The email address you have entered is not in the proper format, please re-enter it."
            },
            email_vertify: {
                required: "Please enter your email",
                equalTo:"The email addresses you have entered do not match, please re-enter them.",
                email: "The email address you have entered is not in the proper format, please re-enter it."
            },
            birthday:"Please provide your birthday",
            country: {
                notUnOne: "Please select country"
            }
           
        }
    });
    //teacher_homeschool step 2
    $("#form-teacher_homeschool-2").validate({
        debug:true,
        rules: {
            teaching_environment: {
                notUnOne : true
            },
            firstname: "required",
            lastname: "required",
            address_line_1: "required",
            address_line_2: "required",
            city: "required",
            state: "required",
            zip: "required",
            country: "notUnOne"    
        },
        messages: {
            teaching_environment:"Please select teaching environment",
            firstname: "Please enter your firstname",
            lastname: "Please enter your lastname",
            address_line_1: "Please enter your first address",
            address_line_2: "Please enter your second address",
            city: "Please enter your city",
            state: "Please enter your state",
            zip: "Please enter your zip",
            country: "Please select your country"
           
        }
    });
    //teacher_homeschool step 3 spam[]
     $("#form-teacher_homeschool-3").validate();

    $("#form-teacher_homeschool-3").each(function() {
        rules2 = {
            class_title: {
                required: true
            }
        };
        
        messages2 = {
            class_title: {
                required: "Please enter class subject."
            }
        };

        // input blue validate
        for(var data in rules2) {
            $(this).on("blur", "[name='" + data + "']", function() {
                inputCheck($(this));
            });
        }

        // form submit validate
        $(this).bind('submit', function() {
            for(var data in rules2) {
                $(this).find("[name='" + data + "']").each(function() {
                    inputCheck($(this));
                });
            }
        });

        /**
        * input validate
        * @param {selector} thisItem this input
        */
        function inputCheck(thisItem) {
            var itemName = thisItem.attr("name");

            var isError = false;
            // error label
            var errorLabel = thisItem.next("label.error");
            var hasErrorLabel = errorLabel.length;
            // error message
            var msg = "";
            // trim this value
            var thisValue = thisItem.val().trim();

            // null validate
            if (rules2[itemName].required) {
                if (!isError && !thisValue) {
                    isError = true;
                    msg = messages2[itemName].required;
                }
            }
            // error
            if (isError) {
                if (!hasErrorLabel) {
                    // no error label then init error label
                    thisItem.after("<label class='error' for='" + itemName + "'></label>");
                    errorLabel = thisItem.next("label.error");
                }
                errorLabel.html(msg);
                errorLabel.show();
            } else {
                if (hasErrorLabel) {
                    errorLabel.hide();
                }
            }

            return isError;
        }
    });
    
    //student
    
    //student step 1
    $("#form-student-1").validate({
        debug:true,
        rules: {
            firstname: "required",
            lastname: "required",
            birthday: {
                required: true                
            }
        },
        messages: {
            firstname: "Please provide your firstname",
            lastname: "Please provide your lastname",
            birthday:"Please provide your birthday"          
        }
    });
    
    //student step 2
    $("#form-student-2").validate({
       debug:true,
        rules: {
            username: {
                required: true,
                remote:userExistURL
            },
            password:{
                required: true,
                minlength: 6,
                regexASCII: true
            },
            password_vertify: {
                required: true,
                minlength: 6,
                equalTo: "#form-student-2 #password",
                regexASCII: true
            },
            email: {
                required: true,
                email: true
            },
            email_vertify: {
                required: true,
                email: true,
                equalTo: "#form-student-2 #email"
            },
            country:{
                notUnOne: true
            }
            
        },
        messages: {
            username: {
                required: "Please enter a username",
                remote:"The username you've requested is already taken, please choose another."
            },
            password: {
                required: "Please provide a password",
                minlength: "The password you've requested does not match the ePals password requirements, please choose another password.",
                regexASCII: "The password you've requested does not match the ePals password requirements, please choose another password."
            },
            password_vertify:{
                required: "Please provide a password",
                minlength: "The password you've requested does not match the ePals password requirements, please choose another password.",
                equalTo: "The passwords you have entered do not match, please re-enter them.",
                regexASCII: "The password you've requested does not match the ePals password requirements, please choose another password."
            },
            email: {
                required: "Please enter your email",
                email:"The email address you have entered is not in the proper format, please re-enter it."
            },
            email_vertify: {
                required: "Please enter your email",
                equalTo:"The email addresses you have entered do not match, please re-enter them.",
                email: "The email address you have entered is not in the proper format, please re-enter it."

            },
            country: "Please select your country"
           
        }
    });
    
    //student step 3
    $("#form-student-3").validate({
        
        debug:true,
        rules: {
            school_name: {
                required: true
            },
            school_address:{
                required: true
            },
            city: {
                required: true
            },
            state: {
                required: true
            },
            school_zip: {
                required: true
            },
            country: {
                notUnOne: true,
            },
            your_grade: {
                notUnOne: true,
            }
        },
        messages: {
            school_name: {
                required: "Please enter your school name"
            },
            school_address:{
                required: "Please enter your school address"
            },
            city: {
                required: "Please enter your city of school"
            },
            state: {
                required: "Please enter your state of school"
            },
            school_zip: {
                required: "Please enter your school ZIP"
            },
            country: "Please select your country",
            your_grade: "Please select your grade"
        }
    });
    
    //student step 4
    $("#form-student-4").validate({
        
        debug:true,
        rules: {
           gender: "notUnOne"
        },
        messages: {
           gender:"Please select your gender"
        }
    });
    
    //parent
    
    //parent step 1
    $("#form-parent-1").validate({
        debug:true,
        rules: {
            firstname: {
                required: true
            },
            lastname: {
                required: true
            },
            username: {
                required: true,
                remote:userExistURL
                
            },
            gender: "notUnOne",
            password:{
                required: true,
                minlength: 6,
                regexASCII: true
            },
            password_vertify:{
                required: true,
                minlength: 6,
                equalTo: "#form-parent-1 #password",
                regexASCII: true
            },
            email: {
                required: true,
                email: true
            },
            email_vertify: {
                required: true,
                email: true,
                equalTo: "#form-parent-1 #email"
            },
            birthday: {
                required: true,
                dateISO:true
            },
            country: "notUnOne"
        },
        messages: {
            gender:"Please select your gender",
            country:"Please select your country",
            firstname: "Please enter your firstname",
            lastname: "Please enter your lastname",
            username: {
                required: "Please enter a username",
                remote:"The username you've requested is already taken, please choose another."
                
            },
            password: {
                required: "Please provide a password",
                minlength: "The password you've requested does not match the ePals password requirements, please choose another password.",
                regexASCII: "The password you've requested does not match the ePals password requirements, please choose another password."
            },
            password_vertify:{
                required: "Please provide a password",
                minlength: "The password you've requested does not match the ePals password requirements, please choose another password.",
                equalTo: "The passwords you have entered do not match, please re-enter them.",
                regexASCII: "The password you've requested does not match the ePals password requirements, please choose another password."
            },
            email: {
                required: "Please enter your email",
                email:"The email address you have entered is not in the proper format, please re-enter it."
            },
            email_vertify: {
                required: "Please enter your email",
                equalTo:"The email addresses you have entered do not match, please re-enter them.",
                email: "The email address you have entered is not in the proper format, please re-enter it."
            },
            birthday: {
                required: "Please provide your birthday",
                dateISO: "Please provide validate date"
            }
        }
    });
    
    $("#form-parent-2").each(function() {
        rules = {
            student_first: {
                required: true
            },
            student_last: {
                required: true
            },
            student_username: {
                required: true,
                remote: userExistURL
            },
            password: {
                required: true,
                minlength: 6,
                regexASCII: true
            },
            password_vertify:{
                required: true,
                minlength: 6,
                equalTo: "#student_password",
                regexASCII: true
            },
            student_birthday: {
               required: true,
               dateISO: true
            },
            student_school_name: {
               required: true
            },
            student_school_address: {
               required: true
            },
            student_city: {
               required: true
            },
            student_state: {
               required: true
            },
            student_school_zip: {
               required: true
            },
            student_your_grade: {
                required: true
            },
            student_country: {
                required: true
            }
        };
        
        messages = {
            student_first: {
                required: "Please enter your student firstname."
            },
            student_last: {
                required: "Please enter your student lastname."
            },
            student_username: {
                required: "Please enter a username.",
                remote: "The username you've required is already taken, please choose another."
            },
            password: {
                required: "Please provide a password.",
                minlength: "The password you've requested does not match the ePals password requirements, please choose another password.",
                regexASCII: "The password you've requested does not match the ePals password requirements, please choose another password."
            },
            password_vertify:{
                required: "Please provide a password.",
                minlength: "The password you've requested does not match the ePals password requirements, please choose another password.",
                equalTo: "The passwords you have entered do not match, please re-enter them.",
                regexASCII: "The password you've requested does not match the ePals password requirements, please choose another password."
            },
            student_birthday: {
               required: "Please provide your birthday.",
               dateISO: "Please provide validate date"
            },
            student_school_name: {
               required: "Please enter your student school name."
            },
            student_school_address: {
               required: "Please enter your student address."
            },
            student_city: {
               required: "Please enter your student city."
            },
            student_state: {
               required: "Please enter your student state."
            },
            student_school_zip: {
               required: "Please enter your student zip."
            },
            student_your_grade: {
                required: "Please select your student grade."
            },
            student_country: {
                required: "Please select your student country."
            }
        };

        // input blue validate
        for(var data in rules) {
            $(this).on("blur", "[name='" + data + "']", function() {
                inputCheck($(this));
            });
        }

        // form submit validate
        $(this).bind('submit', function() {
            for(var data in rules) {
                $(this).find("[name='" + data + "']").each(function() {
                    inputCheck($(this));
                });
            }
            $(this).find(".school_type_div").each(function() {
                // error label
                var errorLabel = $(this).next("label.error");
                var hasErrorLabel = errorLabel.length;
                if (!$(this).find("input[type='checkbox']:checked").size()) {
                    
                    // error
                    // has error label
                    if (!hasErrorLabel) {
                        // no error label init error label
                        $(this).after("<label class='error' for='publictype[]'></label>");
                        errorLabel = $(this).next("label.error");
                    }
                    errorLabel.html("Please choose school type.");
                    errorLabel.show();

                } else {
                    if (hasErrorLabel) {
                        errorLabel.hide();
                    }
                }
                
                // hide error label in div
                $(this).find("label.error").hide();
            });
            if($(this).find("label.error:visible").size()>0){
                return false;
            }
        });

        /**
        * input validate
        * @param {selector} thisItem this input
        */
        function inputCheck(thisItem) {
            var itemName = thisItem.attr("name");

            var isError = false;
            // error label
            var errorLabel = thisItem.next("label.error");
            var hasErrorLabel = errorLabel.length;
            // error message
            var msg = "";
            // trim this value
            var thisValue = thisItem.val().trim();

            // null validate
            if (rules[itemName].required) {
                if (!isError && !thisValue) {
                    isError = true;
                    msg = messages[itemName].required;
                }
            }
            // minlength validate
            if (rules[itemName].minlength) {
                if (!isError && (thisValue.length < rules[itemName].minlength)) {
                    isError = true;
                    msg = messages[itemName].minlength;
                }
            }
            // same value validate
            if (rules[itemName].equalTo) {
                // password value
                var pValue = thisItem.parents("div.panel").find(rules[itemName].equalTo).val();
                if (!isError && (thisValue != pValue)) {
                    isError = true;
                    msg = messages[itemName].equalTo;
                }
            }

            // date validate
            if (rules[itemName].dateISO) {
                if (!isError && !(/^\d{4}[\/\-]\d{1,2}[\/\-]\d{1,2}$/.test(thisValue))) {
                    isError = true;
                    msg = messages[itemName].dateISO;
                }
            }
            
            // ascii validate
            if (rules[itemName].regexASCII) {
                if (!isError && !(/^[x00-x7f]+$/.test(thisValue))) {
                    isError = true;
                    msg = messages[itemName].regexASCII;
                }
            }
            
            // remote repeat 
            if (rules[itemName].remote) {
                if (!isError) {
                    $.ajax({
                        type: 'get',
                        url: rules[itemName].remote,
                        dataType: 'json',
                        async: false,
                        data: {"username": thisValue},
                        success: function(response) {
                            if(!(response === true || response === "true")) {
                                isError = true;
                                msg = messages[itemName].remote;
                            }
                        }
                    });
                }
            }


            // error
            if (isError) {
                if (!hasErrorLabel) {
                    // no error label then init error label
                    thisItem.after("<label class='error' for='" + itemName + "'></label>");
                    errorLabel = thisItem.next("label.error");
                }
                errorLabel.html(msg);
                errorLabel.show();
            } else {
                if (hasErrorLabel) {
                    errorLabel.hide();
                }
            }

            return isError;
        }
    });

});
