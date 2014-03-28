/* 
 regist validate
 
 */

jQuery(document).ready(function() {
    (function ($) {
	$.extend($.validator.messages, {
		required: "必填信息",
		remote: "请修正该信息",
		email: "请输入正确格式的电子邮件",
		url: "请输入合法的网址",
		date: "请输入合法的日期",
		dateISO: "请输入合法的日期 (ISO).",
		number: "请输入合法的数字",
		digits: "只能输入整数",
		creditcard: "请输入合法的信用卡号",
		equalTo: "请再次输入相同的值",
		accept: "请输入拥有合法后缀名的字符串",
		maxlength: $.validator.format("请输入一个长度最多是 {0} 的字符串"),
		minlength: $.validator.format("请输入一个长度最少是 {0} 的字符串"),
		rangelength: $.validator.format("请输入一个长度介于 {0} 和 {1} 之间的字符串"),
		range: $.validator.format("请输入一个介于 {0} 和 {1} 之间的值"),
		max: $.validator.format("请输入一个最大为 {0} 的值"),
		min: $.validator.format("请输入一个最小为 {0} 的值")
	});
}(jQuery));
    
    //set validator plugin globble paremeter ;
    
    jQuery.validator.setDefaults({
        submitHandler: function(form) {
            
        }
    });
    
    jQuery.validator.addMethod("isZipCode", function(value, element) {  
        var tel = /^[0-9]{6}$/;
        return this.optional(element) || (tel.test(value));
    }, "wrong zip code");
    
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
                required: true
                
            },
            gender: "notUnOne",
            password:{
                required: true,
                minlength: 6
            },
            password_vertify:{
                required: true,
                minlength: 6,
                equalTo: "#form-teacher-1 #password"
                
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
            country:{
                
            }
        },
        messages: {
            country: "请选择一个城市",
            gender:"请选择您的性别",
            firstname: "请输入您的姓氏",
            lastname: "请输入您的名字",
            username: {
                required: "请输入您的用户名"
                
            },
            password: {
                required: "请提供一个密码",
                minlength: "您的密码至少六位长"
            },
            password_vertify:{
                required: "请提供一个密码",
                minlength: "您的密码至少六位长",
                equalTo: "请输入与上面相同的密码"
                
            },
            email: {
                required: "请输入您的邮箱",
                email:"请提供一个可用的邮箱"
            },
            email_vertify: {
                required: "请输入您的邮箱",
                email:"请提供一个可用的邮箱",
                equalTo: "请提供与上面相同的邮箱"
            },
            birthday: {
                required: "请提供您的生日",
                dateISO: "请输入合法的日期"
                
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
            }
        }
    });
    
    //teacher step 3
    $("#form-teacher-3").validate();
    
    
    
    
    //teacher_homeschool step 1
    $("#form-teacher_homeschool-1").validate({
        debug:true,

        rules: {
            firstname: "required",
            lastname: "required",
            username: {
                required: true                
            },
            gender: "notUnOne",
            password:{
                required: true,
                minlength: 6
            },
            password_vertify: {
                required: true,
                minlength: 6,
                equalTo: "#form-teacher_homeschool-1 #password"
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
            country:{
                
            }
            
        },
        messages: {
            country: "请选择一个城市",
            gender:"请选择您的性别",
            firstname: "请输入您的姓氏",
            lastname: "请输入您的名字",
            username: {
                required: "请输入您的用户名"
                
            },
            password: {
                required: "请提供一个密码",
                minlength: "您的密码至少六位长"
            },
            password_vertify:{
                required: "请提供一个密码",
                minlength: "您的密码至少六位长",
                equalTo: "请输入与上面相同的密码"
                
            },
            email: {
                required: "请输入您的邮箱",
                email:"请提供一个可用的邮箱"
            },
            email_vertify: {
                required: "请输入您的邮箱",
                email:"请提供一个可用的邮箱",
                equalTo: "请提供与上面相同的邮箱"
            },
            birthday: {
                required: "请提供您的生日",
                dateISO: "请输入合法的日期"
                
            }          
        }
    });
    //teacher_homeschool step 2
    $("#form-teacher_homeschool-2").validate({
        debug:true,
        rules: {
            teaching_environment: "notUnOne",
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
            teaching_environment:"Please select your teaching environment",
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
            firstname: "请输入您的姓氏",
            lastname: "请输入您的名字",
            birthday: {
                required: "请提供您的生日",
                dateISO: "请输入合法的日期"
                
            }          
        }
    });
    
    //student step 2
    $("#form-student-2").validate({
       debug:true,
        rules: {
            username: {
                required: true                
            },
            password:{
                required: true,
                minlength: 6
            },
            password_vertify: {
                required: true,
                minlength: 6,
                equalTo: "#form-student-2 #password"
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
                
            }
            
        },
        messages: {    
            username: {
                required: "请输入您的用户名"
                
            },
            password: {
                required: "请提供一个密码",
                minlength: "您的密码至少六位长"
            },
            password_vertify:{
                required: "请提供一个密码",
                minlength: "您的密码至少六位长",
                equalTo: "请输入与上面相同的密码"
                
            },
            email: {
                required: "请输入您的邮箱",
                email:"请提供一个可用的邮箱"
            },
            email_vertify: {
                required: "请输入您的邮箱",
                email:"请提供一个可用的邮箱",
                equalTo: "请提供与上面相同的邮箱"
            }       ,
            country: "请选择一个城市"
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
            }
        }
    });
    
    //student step 4
    $("#form-student-4").validate({
        
        debug:true,
        rules: {
           gender: "notUnOne"
        },
        messages: {
           gender:"请选择您的性别"
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
                required: true
                
            },
            gender: "notUnOne",
            password:{
                required: true,
                minlength: 6
            },
            password_vertify:{
                required: true,
                minlength: 6,
                equalTo: "#form-parent-1 #password"
                
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
            gender:"请选择您的性别",
            firstname: "请输入您的姓氏",
            lastname: "请输入您的名字",
            country: "请选择一个城市",
            username: {
                required: "请输入您的用户名"
                
            },
            password: {
                required: "请提供一个密码",
                minlength: "您的密码至少六位长"
            },
            password_vertify:{
                required: "请提供一个密码",
                minlength: "您的密码至少六位长",
                equalTo: "请输入与上面相同的密码"
                
            },
            email: {
                required: "请输入您的邮箱",
                email:"请提供一个可用的邮箱"
            },
            email_vertify: {
                required: "请输入您的邮箱",
                email:"请提供一个可用的邮箱",
                equalTo: "请提供与上面相同的邮箱"
            },
            birthday: {
                required: "请提供您的生日",
                dateISO: "请输入合法的日期"
                
            }          
        }
    });
    
    //parent step 2
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
                remote: "http://localhost/provisioning/Provisioning/ifUserNotExist"
            },
            password: {
                required: true,
                minlength: 6
            },
            password_vertify:{
                required: true,
                minlength: 6,
                equalTo: "#student_password"
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
                minlength: "The password you've requested does not match the ePals password requirements, please choose another password."
            },
            password_vertify:{
                required: "Please provide a password.",
                minlength: "The password you've requested does not match the ePals password requirements, please choose another password.",
                equalTo: "The passwords you have entered do not match, please re-enter them."
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


